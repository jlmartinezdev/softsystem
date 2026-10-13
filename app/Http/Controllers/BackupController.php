<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Support\DatabaseBackup;
use Carbon\Carbon;
use Exception;

class BackupController extends Controller
{
    /**
     * Muestra la vista principal de gestión de copias de seguridad.
     */
    public function index()
    {
        $stats = DatabaseBackup::getDatabaseStats();
        $backups = DatabaseBackup::listBackups();

        return view('backup', compact('stats', 'backups'));
    }

    /**
     * Retorna el listado de backups y estadísticas en formato JSON.
     */
    public function listar()
    {
        try {
            $stats = DatabaseBackup::getDatabaseStats();
            $backups = DatabaseBackup::listBackups();

            return response()->json([
                'success' => true,
                'stats' => $stats,
                'backups' => $backups
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener listado de backups: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Genera una nueva copia de seguridad en el servidor.
     */
    public function crear(Request $request)
    {
        try {
            $options = [
                'compress' => filter_var($request->input('compress', true), FILTER_VALIDATE_BOOLEAN),
                'include_data' => filter_var($request->input('include_data', true), FILTER_VALIDATE_BOOLEAN),
                'include_structure' => filter_var($request->input('include_structure', true), FILTER_VALIDATE_BOOLEAN),
                'name' => $request->input('name')
            ];

            $result = DatabaseBackup::createBackup($options);

            return response()->json([
                'success' => true,
                'message' => 'Copia de seguridad generada con éxito (' . $result['size'] . ').',
                'backup' => $result
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al generar la copia de seguridad: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Descarga un archivo de backup previamente generado.
     */
    public function descargar($filename)
    {
        $safeName = basename($filename);
        $filePath = DatabaseBackup::getBackupDirectory() . DIRECTORY_SEPARATOR . $safeName;

        if (!file_exists($filePath)) {
            abort(404, 'El archivo de backup no existe en el servidor.');
        }

        $headers = [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . $safeName . '"'
        ];

        return response()->download($filePath, $safeName, $headers);
    }

    /**
     * Genera y descarga directamente una copia de seguridad al navegador.
     */
    public function descargaDirecta(Request $request)
    {
        try {
            $compress = filter_var($request->input('compress', true), FILTER_VALIDATE_BOOLEAN);
            $options = [
                'compress' => $compress,
                'include_data' => true,
                'include_structure' => true,
                'name' => 'backup_express_' . config('database.connections.mysql.database', 'softsystem') . '_' . date('Ymd_His')
            ];

            $result = DatabaseBackup::createBackup($options);
            $filePath = $result['path'];
            $fileName = $result['filename'];

            return response()->download($filePath, $fileName)->deleteFileAfterSend(false);
        } catch (Exception $e) {
            return redirect()->route('backup.index')->with('error', 'Error al generar descarga directa: ' . $e->getMessage());
        }
    }

    /**
     * Restaura una copia de seguridad seleccionada de la lista local.
     */
    public function restaurarExistente(Request $request)
    {
        $filename = $request->input('filename');
        if (empty($filename)) {
            return response()->json([
                'success' => false,
                'message' => 'Debe indicar el nombre del archivo a restaurar.'
            ], 422);
        }

        $safeName = basename($filename);
        $filePath = DatabaseBackup::getBackupDirectory() . DIRECTORY_SEPARATOR . $safeName;

        if (!file_exists($filePath)) {
            return response()->json([
                'success' => false,
                'message' => 'El archivo de backup seleccionado no existe.'
            ], 404);
        }

        try {
            DatabaseBackup::restoreBackup($filePath);

            return response()->json([
                'success' => true,
                'message' => '¡Base de datos restaurada correctamente desde ' . $safeName . '!'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error durante la restauración: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Sube un archivo de backup (.sql o .sql.gz) desde la PC y lo restaura.
     */
    public function subirYRestaurar(Request $request)
    {
        if (!$request->hasFile('archivo')) {
            return response()->json([
                'success' => false,
                'message' => 'No se ha subido ningún archivo.'
            ], 422);
        }

        $file = $request->file('archivo');
        $origName = $file->getClientOriginalName();
        $ext = strtolower($file->getClientOriginalExtension());

        // Validar extensiones permitidas
        if ($ext !== 'sql' && $ext !== 'gz') {
            return response()->json([
                'success' => false,
                'message' => 'Formato no válido. Solo se admiten archivos .sql o .sql.gz'
            ], 422);
        }

        // Si es .gz, verificar que sea .sql.gz
        if ($ext === 'gz' && !preg_match('/\.sql\.gz$/i', $origName)) {
            return response()->json([
                'success' => false,
                'message' => 'El archivo comprimido debe tener extensión .sql.gz'
            ], 422);
        }

        $targetDir = DatabaseBackup::getBackupDirectory();
        $timestamp = date('Ymd_His');
        $savedName = 'subido_' . $timestamp . '_' . preg_replace('/[^a-zA-Z0-9_\.]/', '_', $origName);
        $targetPath = $targetDir . DIRECTORY_SEPARATOR . $savedName;

        $file->move($targetDir, $savedName);

        try {
            DatabaseBackup::restoreBackup($targetPath);

            return response()->json([
                'success' => true,
                'message' => '¡Archivo subido y base de datos restaurada con éxito!',
                'filename' => $savedName
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al restaurar el archivo subido: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Elimina un archivo de backup del servidor.
     */
    public function eliminar($filename)
    {
        try {
            $deleted = DatabaseBackup::deleteBackup($filename);

            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'Copia de seguridad eliminada correctamente.'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'El archivo no fue encontrado o no se pudo eliminar.'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el archivo: ' . $e->getMessage()
            ], 500);
        }
    }
}

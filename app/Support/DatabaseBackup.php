<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DatabaseBackup
{
    /**
     * Directorio de almacenamiento de backups.
     */
    public static function getBackupDirectory()
    {
        $dir = storage_path('app/backups');
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }

        // Crear .gitignore para evitar versionar dumps de base de datos
        $gitIgnore = $dir . DIRECTORY_SEPARATOR . '.gitignore';
        if (!file_exists($gitIgnore)) {
            @file_put_contents($gitIgnore, "*\n!.gitignore\n");
        }

        return $dir;
    }

    /**
     * Localiza el binario de mysqldump o mysql.
     */
    public static function findBinary($binary = 'mysqldump')
    {
        $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
        $binaryName = $binary . ($isWindows ? '.exe' : '');

        // 1. Intentar resolver mediante comando PATH
        $whichCmd = $isWindows ? 'where ' . escapeshellarg($binaryName) . ' 2>nul' : 'which ' . escapeshellarg($binaryName) . ' 2>/dev/null';
        $pathOutput = @shell_exec($whichCmd);
        if ($pathOutput) {
            $lines = explode("\n", trim($pathOutput));
            foreach ($lines as $line) {
                $line = trim($line);
                if (!empty($line) && file_exists($line)) {
                    return $line;
                }
            }
        }

        // 2. Rutas conocidas comunes en Laragon y XAMPP en Windows
        $candidates = [
            'C:\laragon\bin\mysql\mysql-5.7.24-winx64\bin\\' . $binaryName,
            'C:\xampp\mysql\bin\\' . $binaryName,
        ];

        // Escanear todas las subcarpetas de Laragon MySQL y MariaDB
        if (is_dir('C:\laragon\bin\mysql')) {
            $dirs = glob('C:\laragon\bin\mysql\*', GLOB_ONLYDIR) ?: [];
            foreach ($dirs as $dir) {
                $candidates[] = $dir . DIRECTORY_SEPARATOR . 'bin' . DIRECTORY_SEPARATOR . $binaryName;
            }
        }
        if (is_dir('C:\laragon\bin\mariadb')) {
            $dirs = glob('C:\laragon\bin\mariadb\*', GLOB_ONLYDIR) ?: [];
            foreach ($dirs as $dir) {
                $candidates[] = $dir . DIRECTORY_SEPARATOR . 'bin' . DIRECTORY_SEPARATOR . $binaryName;
            }
        }

        foreach ($candidates as $cand) {
            if (file_exists($cand)) {
                return $cand;
            }
        }

        return $binaryName;
    }

    /**
     * Crea un archivo temporal .cnf para pasar credenciales a MySQL de forma segura.
     */
    protected static function createTempConfigFile($host, $port, $user, $password)
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'mycnf_');
        $content = "[client]\n"
            . "host=" . $host . "\n"
            . "port=" . $port . "\n"
            . "user=" . $user . "\n";
        if ($password !== '' && $password !== null) {
            $content .= "password=\"" . addcslashes($password, "\\\"") . "\"\n";
        }
        file_put_contents($tempFile, $content);
        return $tempFile;
    }

    /**
     * Lista todos los backups existentes en el directorio.
     */
    public static function listBackups()
    {
        $dir = self::getBackupDirectory();
        $files = scandir($dir);
        $backups = [];

        foreach ($files as $file) {
            if ($file === '.' || $file === '..' || $file === '.gitignore') {
                continue;
            }

            $fullPath = $dir . DIRECTORY_SEPARATOR . $file;
            if (!is_file($fullPath)) {
                continue;
            }

            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            $isGz = ($ext === 'gz');
            if ($ext !== 'sql' && !$isGz) {
                continue;
            }

            $sizeBytes = filesize($fullPath);
            $mtime = filemtime($fullPath);
            $date = Carbon::createFromTimestamp($mtime);

            $type = 'completo';
            if (stripos($file, 'estructura') !== false) {
                $type = 'estructura';
            } elseif (stripos($file, 'datos') !== false) {
                $type = 'datos';
            }

            $backups[] = [
                'filename' => $file,
                'path' => $fullPath,
                'size_bytes' => $sizeBytes,
                'size_formatted' => self::formatBytes($sizeBytes),
                'created_at' => $date->format('d/m/Y H:i:s'),
                'created_at_human' => $date->diffForHumans(),
                'timestamp' => $mtime,
                'is_compressed' => $isGz,
                'type' => $type
            ];
        }

        // Ordenar del más reciente al más antiguo
        usort($backups, function ($a, $b) {
            return $b['timestamp'] - $a['timestamp'];
        });

        return $backups;
    }

    /**
     * Obtiene estadísticas de la base de datos actual.
     */
    public static function getDatabaseStats()
    {
        $dbName = config('database.connections.mysql.database', 'softsystem');
        $host = config('database.connections.mysql.host', 'localhost');
        $port = config('database.connections.mysql.port', '3306');

        $tablesCount = 0;
        $rowsCount = 0;
        $sizeMb = 0.0;
        $version = 'MySQL';

        try {
            $stats = DB::select("
                SELECT 
                    COUNT(*) AS tables_count,
                    COALESCE(SUM(table_rows), 0) AS rows_count,
                    COALESCE(ROUND(SUM(data_length + index_length) / (1024 * 1024), 2), 0) AS size_mb
                FROM information_schema.tables 
                WHERE table_schema = ?
            ", [$dbName]);

            if (!empty($stats)) {
                $tablesCount = (int)($stats[0]->tables_count ?? 0);
                $rowsCount = (int)($stats[0]->rows_count ?? 0);
                $sizeMb = (float)($stats[0]->size_mb ?? 0.0);
            }

            $verRes = DB::select("SELECT VERSION() AS ver");
            if (!empty($verRes)) {
                $version = $verRes[0]->ver ?? 'MySQL';
            }
        } catch (\Exception $e) {
            // Manejo silencioso en caso de permisos restringidos en information_schema
        }

        $backups = self::listBackups();
        $totalStorageBytes = array_sum(array_column($backups, 'size_bytes'));
        $lastBackup = !empty($backups) ? $backups[0] : null;

        return [
            'database' => $dbName,
            'host' => $host,
            'port' => $port,
            'version' => $version,
            'tables_count' => $tablesCount,
            'rows_count' => $rowsCount,
            'db_size_mb' => $sizeMb,
            'backups_count' => count($backups),
            'total_backup_bytes' => $totalStorageBytes,
            'total_backup_size' => self::formatBytes($totalStorageBytes),
            'last_backup' => $lastBackup
        ];
    }

    /**
     * Genera una nueva copia de seguridad.
     */
    public static function createBackup($options = [])
    {
        set_time_limit(600); // 10 minutos para bases grandes
        ini_set('memory_limit', '512M');

        $compress = $options['compress'] ?? true;
        $includeData = $options['include_data'] ?? true;
        $includeStructure = $options['include_structure'] ?? true;
        $customName = $options['name'] ?? null;

        $dbName = config('database.connections.mysql.database', 'softsystem');
        $host = config('database.connections.mysql.host', 'localhost');
        $port = config('database.connections.mysql.port', '3306');
        $user = config('database.connections.mysql.username', 'root');
        $pass = config('database.connections.mysql.password', '');

        $mysqldump = self::findBinary('mysqldump');
        $cnfFile = self::createTempConfigFile($host, $port, $user, $pass);

        $dir = self::getBackupDirectory();
        $timestamp = date('Y-m-d_H-i-s');

        $typeSuffix = 'completo';
        if ($includeStructure && !$includeData) {
            $typeSuffix = 'estructura';
        } elseif (!$includeStructure && $includeData) {
            $typeSuffix = 'datos';
        }

        $baseName = !empty($customName) 
            ? preg_replace('/[^a-zA-Z0-9_\-]/', '_', $customName)
            : "backup_{$dbName}_{$typeSuffix}_{$timestamp}";

        $fileName = $baseName . ($compress ? '.sql.gz' : '.sql');
        $targetFile = $dir . DIRECTORY_SEPARATOR . $fileName;

        $flags = [
            '--defaults-extra-file=' . escapeshellarg($cnfFile),
            '--default-character-set=utf8',
            '--routines',
            '--triggers',
            '--single-transaction',
        ];

        if (!$includeData) {
            $flags[] = '--no-data';
        }
        if (!$includeStructure) {
            $flags[] = '--no-create-info';
        }

        $flags[] = escapeshellarg($dbName);
        $cmd = escapeshellarg($mysqldump) . ' ' . implode(' ', $flags);
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $cmd = '"' . $cmd . '"';
        }

        $descriptors = [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w']
        ];

        $process = proc_open($cmd, $descriptors, $pipes);

        if (!is_resource($process)) {
            @unlink($cnfFile);
            throw new \Exception("No se pudo iniciar el proceso de mysqldump.");
        }

        fclose($pipes[0]);

        if ($compress) {
            $gz = gzopen($targetFile, 'wb9');
            if (!$gz) {
                @unlink($cnfFile);
                throw new \Exception("No se pudo crear el archivo comprimido en disco.");
            }
            while (!feof($pipes[1])) {
                $chunk = fread($pipes[1], 1024 * 64);
                gzwrite($gz, $chunk);
            }
            gzclose($gz);
        } else {
            $fp = fopen($targetFile, 'wb');
            if (!$fp) {
                @unlink($cnfFile);
                throw new \Exception("No se pudo crear el archivo en disco.");
            }
            while (!feof($pipes[1])) {
                $chunk = fread($pipes[1], 1024 * 64);
                fwrite($fp, $chunk);
            }
            fclose($fp);
        }

        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);

        $exitCode = proc_close($process);
        @unlink($cnfFile);

        // Si falló el volcado, eliminar el archivo creado y arrojar excepción
        if ($exitCode !== 0 && filesize($targetFile) < 100) {
            @unlink($targetFile);
            throw new \Exception("Error al ejecutar mysqldump: " . trim($stderr));
        }

        $fileSize = filesize($targetFile);

        return [
            'filename' => $fileName,
            'path' => $targetFile,
            'size' => self::formatBytes($fileSize),
            'size_bytes' => $fileSize,
            'created_at' => Carbon::now()->format('d/m/Y H:i:s'),
            'type' => $typeSuffix,
            'is_compressed' => $compress
        ];
    }

    /**
     * Restaura una copia de seguridad desde un archivo en disco.
     */
    public static function restoreBackup($filePath)
    {
        set_time_limit(900); // 15 minutos para restaurar
        ini_set('memory_limit', '512M');

        if (!file_exists($filePath)) {
            throw new \Exception("El archivo de backup especificado no existe.");
        }

        $dbName = config('database.connections.mysql.database', 'softsystem');
        $host = config('database.connections.mysql.host', 'localhost');
        $port = config('database.connections.mysql.port', '3306');
        $user = config('database.connections.mysql.username', 'root');
        $pass = config('database.connections.mysql.password', '');

        $mysql = self::findBinary('mysql');
        $cnfFile = self::createTempConfigFile($host, $port, $user, $pass);

        $cmd = escapeshellarg($mysql) 
            . ' --defaults-extra-file=' . escapeshellarg($cnfFile) 
            . ' --default-character-set=utf8 ' 
            . escapeshellarg($dbName);

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $cmd = '"' . $cmd . '"';
        }

        $descriptors = [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w']
        ];

        $process = proc_open($cmd, $descriptors, $pipes);

        if (!is_resource($process)) {
            @unlink($cnfFile);
            throw new \Exception("No se pudo iniciar el proceso de mysql para la restauración.");
        }

        $isGz = (substr(strtolower($filePath), -3) === '.gz');

        if ($isGz) {
            $gz = gzopen($filePath, 'rb');
            if (!$gz) {
                @unlink($cnfFile);
                throw new \Exception("No se pudo leer el archivo comprimido.");
            }
            while (!gzeof($gz)) {
                $chunk = gzread($gz, 1024 * 64);
                fwrite($pipes[0], $chunk);
            }
            gzclose($gz);
        } else {
            $fp = fopen($filePath, 'rb');
            if (!$fp) {
                @unlink($cnfFile);
                throw new \Exception("No se pudo leer el archivo SQL.");
            }
            while (!feof($fp)) {
                $chunk = fread($fp, 1024 * 64);
                fwrite($pipes[0], $chunk);
            }
            fclose($fp);
        }

        fclose($pipes[0]);

        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);

        $exitCode = proc_close($process);
        @unlink($cnfFile);

        if ($exitCode !== 0) {
            throw new \Exception("Error durante la restauración de la base de datos: " . trim($stderr));
        }

        return true;
    }

    /**
     * Elimina un archivo de backup del disco.
     */
    public static function deleteBackup($filename)
    {
        $safeName = basename($filename);
        $filePath = self::getBackupDirectory() . DIRECTORY_SEPARATOR . $safeName;

        if (file_exists($filePath)) {
            return @unlink($filePath);
        }

        return false;
    }

    /**
     * Formatea bytes a unidades legibles (KB, MB, GB).
     */
    public static function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}

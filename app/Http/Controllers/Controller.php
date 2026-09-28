<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Valida si el usuario actual tiene permiso para el formulario y acción especificados.
     * Si no tiene permiso, aborta con 403 (JSON para AJAX o vista/redirección para web).
     *
     * @param string $modulo Nombre del formulario en formularios.for_nombre
     * @param string $tipo   'open' | 'add' | 'edit' | 'del' | 'export'
     */
    protected function autorizarPermiso($modulo, $tipo = 'open')
    {
        $user = Auth::user();
        if (!$user) {
            if (request()->ajax() || request()->expectsJson() || request()->wantsJson()) {
                abort(response()->json(['success' => false, 'error' => 'No autenticado.'], 401));
            }
            redirect()->guest('login')->send();
            exit;
        }

        if ($user->esAdministrador()) {
            return;
        }

        if (!$user->tienePermiso($modulo, $tipo)) {
            $nombresTipo = [
                'open'   => 'visualizar',
                'add'    => 'crear o registrar',
                'edit'   => 'modificar',
                'del'    => 'eliminar o anular',
                'export' => 'exportar o imprimir'
            ];
            $accionTexto = $nombresTipo[$tipo] ?? $tipo;
            $msg = "Acceso denegado: no cuenta con privilegios para {$accionTexto} en {$modulo}.";

            if (request()->ajax() || request()->expectsJson() || request()->wantsJson()) {
                abort(response()->json([
                    'success' => false,
                    'error'   => $msg,
                    'modulo'  => $modulo,
                    'tipo'    => $tipo
                ], 403));
            }

            if ($tipo === 'open') {
                redirect()->route('home')->with('error', $msg)->send();
                exit;
            }

            abort(403, $msg);
        }
    }

    /**
     * Valida si el usuario actual tiene una acción operativa especial habilitada.
     *
     * @param string $claveAccion
     */
    protected function autorizarAccion($claveAccion)
    {
        $user = Auth::user();
        if (!$user) {
            if (request()->ajax() || request()->expectsJson() || request()->wantsJson()) {
                abort(response()->json(['success' => false, 'error' => 'No autenticado.'], 401));
            }
            redirect()->guest('login')->send();
            exit;
        }

        if ($user->esAdministrador()) {
            return;
        }

        if (!$user->tieneAccion($claveAccion)) {
            $msg = "Acción operativa no autorizada: requiere el privilegio '{$claveAccion}'.";

            if (request()->ajax() || request()->expectsJson() || request()->wantsJson()) {
                abort(response()->json([
                    'success' => false,
                    'error'   => $msg,
                    'accion'  => $claveAccion
                ], 403));
            }

            abort(403, $msg);
        }
    }
}

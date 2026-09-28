<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckAccion
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $claveAccion   Clave de la acción en la BD (ej. 'venta_modificar_precio', 'caja_reabrir_turno')
     * @return mixed
     */
    public function handle($request, Closure $next, $claveAccion)
    {
        if (!Auth::check()) {
            if ($request->ajax() || $request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'error'   => 'Sesión no iniciada. Debe iniciar sesión.'
                ], 401);
            }
            return redirect()->guest('login');
        }

        $user = Auth::user();

        // El administrador siempre tiene autorización total
        if ($user->esAdministrador()) {
            return $next($request);
        }

        if (!$user->tieneAccion($claveAccion)) {
            $mensaje = "Acceso denegado: Su rol no cuenta con autorización para la acción operativa especial '{$claveAccion}'.";

            if ($request->ajax() || $request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'error'   => $mensaje,
                    'accion'  => $claveAccion
                ], 403);
            }

            abort(403, $mensaje);
        }

        return $next($request);
    }
}

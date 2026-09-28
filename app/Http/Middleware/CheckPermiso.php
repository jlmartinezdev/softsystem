<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckPermiso
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $modulo   Nombre del formulario en la BD (ej. 'ventas', 'articulos')
     * @param  string  $tipo     Tipo de permiso ('open', 'add', 'edit', 'del', 'export')
     * @return mixed
     */
    public function handle($request, Closure $next, $modulo, $tipo = 'open')
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

        // El administrador siempre tiene acceso total
        if ($user->esAdministrador()) {
            return $next($request);
        }

        // Validar el permiso correspondiente
        if (!$user->tienePermiso($modulo, $tipo)) {
            $nombresTipo = [
                'open'   => 'visualizar o acceder a',
                'add'    => 'crear o registrar en',
                'edit'   => 'modificar en',
                'del'    => 'eliminar o anular en',
                'export' => 'exportar o imprimir datos de'
            ];
            $accionTexto = $nombresTipo[$tipo] ?? $tipo;
            $mensaje = "No cuenta con privilegios suficientes para {$accionTexto} el módulo de {$modulo}.";

            if ($request->ajax() || $request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'error'   => $mensaje,
                    'modulo'  => $modulo,
                    'tipo'    => $tipo
                ], 403);
            }

            if ($tipo === 'open') {
                return redirect()->route('home')->with('error', $mensaje);
            }

            abort(403, $mensaje);
        }

        return $next($request);
    }
}

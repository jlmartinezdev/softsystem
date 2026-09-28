<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
class Administrador
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->esAdministrador()) {
            return $next($request);
        }

        if ($request->ajax() || $request->expectsJson() || $request->wantsJson()) {
            return response()->json(['error' => 'Acceso exclusivo para el Administrador del sistema.', 'success' => false], 403);
        }

        return redirect('/');
    }
}

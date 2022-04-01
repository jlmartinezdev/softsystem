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
        if(Auth::user()->roles->nom_rol=="Administrador")
            return $next($request);
        else
            return redirect('/');
            
    }
}

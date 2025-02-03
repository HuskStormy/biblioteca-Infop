<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;

class middleware_dash
{
    public function handle(Request $request, Closure $next)
    {
        // Verifica si la sesión tiene la clave 'Session'
        if (!session()->has('Session')) {
            // Si no tiene la clave 'Session', redirige al usuario al login
            session(config('global.Mensaje_texto.no_autentificado'));
            return redirect('/login');
        }



        $DataEntidad = json_decode(Http::get(config('global.Api.usuario_id').Session::get('user_id')), true);
        Session::put('user_nombre', $DataEntidad['NOMBRE_USUARIO']);
        Session::put('user_Correo', $DataEntidad['CORREO_ELECTRONICO']);
        Session::put('user_Rol',    $DataEntidad['ROL_USUARIO']);


        // Si la sesión tiene la clave 'Session', pasa al siguiente middleware o controlador
        return $next($request);
    }

}

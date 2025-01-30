<?php

namespace App\Http\Controllers\seguridad;

use App\Http\Controllers\AutenticController;
use App\Mail\TestMail;
use Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ModuloSeguridad
{
    public function ruta_TablaUsuario(){

        $Usuarios = json_decode(Http::get(config('global.Api.usuario')), true);
        $Usuarios_estado = json_decode(Http::get(config('global.Api.usuarioEstado')), true);
        $Usuarios_rol = json_decode(Http::get(config('global.Api.usuarioRol')), true);

        return view('Modulos.Seguridad.Usuarios.tabla_usuarios')->with([
            'Usuarios' => $Usuarios,
            'Estado' => $Usuarios_estado,
            'Rol' => $Usuarios_rol
        ]);

    }
}

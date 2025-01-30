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

    public function accion_editarUsuario(Request $request)
    {
        $DatosPut = $request->validate([
            'id' => 'required',
            'estado' => 'required',
            'rol' => 'required',
            'nombre' => 'required|string|max:50',
            'DNI' => 'required',
            'correo' => 'required|string|max:50',
            'CambioContra' => ''
        ]);


        $token = Str::random(config('global.variables.token_lenght'));

        $parametro_Intentos = json_decode(Http::get(config('global.Api.parametro_id').'1'), true);
        $parametro_DiasVenc = json_decode(Http::get(config('global.Api.parametro_id').'3'), true);

        $DataEntidad = Http::put(config('global.Api.usuario_modify'), [
            "p_ID_USUARIO" => $DatosPut['id'],
            "p_ID_ESTADO_USUARIO"  => ( isset($DatosPut['CambioContra']) ) ? 5 : $DatosPut['estado'],
            "p_ID_ROL"  => $DatosPut['rol'],
            "p_ID_CENTRO_REGIONAL"  => 1,
            "p_NOMBRE_USUARIO"  => $DatosPut['nombre'],
            "p_CONTRASENA"  => ( isset($DatosPut['CambioContra']) ) ? "nueva contrasena" : null,
            "p_DNI"  => $DatosPut['DNI'],
            "p_CORREO_ELECTRONICO"  => $DatosPut['correo'],
            "p_FECHA_CONEXION_ULTIMA" => Carbon::today(),
            "p_COD_PRIMER_INGRESO" => $token,
            "p_FECHA_VENCIMIENTO" => Carbon::today()->addDays($parametro_DiasVenc['VALOR']),
            "p_INTENTOS" => intval($parametro_Intentos['VALOR'])
        ]);

        if (isset($DatosPut['CambioContra'])){
            $link_token = URL::temporarySignedRoute('verificacion.Link', now()->addMinutes(config('global.variables.token_time_min')), ['token' => $token]);
            Mail::to($DatosPut['correo'])->send(new TestMail($link_token, $DatosPut['nombre'], 0, $DatosPut['correo']));
        }
        return redirect()->back();
    }
}

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
use Barryvdh\DomPDF\Facade\Pdf;


class UsuarioController
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

    public function accion_AgregarUsuario(Request $request){
        $DatoForm = $request->validate([
            'rol' => 'required',
            'nombre' => 'required|string|max:50',
            'DNI' => 'required',
            'correo' => 'required|string|max:50',
            'CambioContra' => ''
        ]);

        //Extraer datos de api
        $DataEntidad = json_decode(Http::get(config('global.Api.usuario_correo').$DatoForm['correo']), true);
        if ($DataEntidad != null){
            session()->flash('toastr', [
                'type' => 'error',
                'message' => 'ya existe un usuario con el correo '.$DatoForm['correo'].', intente denuevo',
                'title' => '¡Error!'
            ]);
            return redirect()->back();
        }

        $token = Str::random(config('global.variables.token_lenght'));

        $parametro_Intentos = json_decode(Http::get(config('global.Api.parametro_id').'1'), true);
        $parametro_DiasVenc = json_decode(Http::get(config('global.Api.parametro_id').'3'), true);

        $DataEntidad = Http::post(config('global.Api.usuario_add'), [
            "p0" => 5,                  // estado usuario 3:usuario nuevo
            "p1" => $DatoForm['rol'],   // rol
            "p2" => 1,                  // centroReginal
            "p3" => strtoupper($DatoForm['nombre']),  // Nombre Usuario
            "p4" => $token,    // contrasena
            "p5" => $DatoForm['correo'], // correo electrónico
            "p6" => str_replace(' ', '', $DatoForm['DNI']),   // DNI
            "p7" => Carbon::today(),    // fecha_conexion_ultima
            "p8" => $token,             // cod primer ingreso
            "p9" => Carbon::today()->addDays($parametro_DiasVenc['VALOR']),          // fecha_vencimiento
            "p10"=> intval($parametro_Intentos['VALOR'])
        ]);


        $link_token = URL::temporarySignedRoute('verificacion.Link', now()->addMinutes(config('global.variables.token_time_min')), ['token' => $token]);
        Mail::to($DatoForm['correo'])->send(new TestMail($link_token, $DatoForm['nombre'], 0, $DatoForm['correo']));


        session()->flash('toastr', [
            'type' => 'success',
            'message' => 'el usuario '.$DatoForm['nombre'].' se a creado exitosamente',
            'title' => '¡Éxito!'
        ]);

        return redirect()->back();
    }

    public function accion_editarUsuario(Request $request){
        $DatosPut = $request->validate([
            'id' => 'required',
            'estado' => 'required',
            'rol' => 'required',
            'nombre' => 'required|string|max:50',
            'DNI' => 'required',
            'correo' => 'required|string|max:50',
            'CambioContra' => ''
        ]);

        $DatosPut['DNI'] = str_replace("-", "", $DatosPut['DNI']);



        //Extraer datos de api
        $DataEntidad = json_decode(Http::get(config('global.Api.usuario_id').$DatosPut['id']), true);
        $cambioCorreo = $DataEntidad['CORREO_ELECTRONICO'] != $DatosPut['correo'];

        if ($cambioCorreo){
            $DataEntidad = json_decode(Http::get(config('global.Api.usuario_correo').$DatosPut['correo']), true);
            if ($DataEntidad != null){
                session()->flash('toastr', [
                    'type' => 'error',
                    'message' => 'ya existe un usuario con el correo '.$DatosPut['correo'].', intente denuevo',
                    'title' => '¡Error!'
                ]);
                return redirect()->back();
            }
        }
        $token = Str::random(config('global.variables.token_lenght'));

        $parametro_Intentos = json_decode(Http::get(config('global.Api.parametro_id').'1'), true);
        $parametro_DiasVenc = json_decode(Http::get(config('global.Api.parametro_id').'3'), true);

        $DataEntidad = Http::put(config('global.Api.usuario_modify'), [
            "p_ID_USUARIO" => $DatosPut['id'],
            "p_ID_ESTADO_USUARIO"  => ( isset($DatosPut['CambioContra']) ) ? 5 : $DatosPut['estado'],
            "p_ID_ROL"  => $DatosPut['rol'],
            "p_ID_CENTRO_REGIONAL"  => 1,
            "p_NOMBRE_USUARIO"  => $DatosPut['nombre'],
            "p_CONTRASENA"  => ( isset($DatosPut['CambioContra']) ) ? $token : null,
            "p_DNI"  => $DatosPut['DNI'],
            "p_CORREO_ELECTRONICO"  => $DatosPut['correo'],
            "p_FECHA_CONEXION_ULTIMA" => Carbon::today(),
            "p_COD_PRIMER_INGRESO" => $token,
            "p_FECHA_VENCIMIENTO" => Carbon::today()->addDays($parametro_DiasVenc['VALOR']),
            "p_INTENTOS" => intval($parametro_Intentos['VALOR'])
        ]);

        if (isset($DatosPut['CambioContra']) || $cambioCorreo){
            $link_token = URL::temporarySignedRoute('verificacion.Link', now()->addMinutes(config('global.variables.token_time_min')), ['token' => $token]);
            Mail::to($DatosPut['correo'])->send(new TestMail($link_token, $DatosPut['nombre'], 0, $DatosPut['correo']));
        }

        session()->flash('toastr', [
            'type' => 'success',
            'message' => 'el usuario '.$DatosPut['nombre'].' se editó Exitosamente!!',
            'title' => '¡Éxito!'
        ]);

        return redirect()->back();
    }

    public function accion_EliminarUsuario(Request $request){
        $Datosdelete = $request->validate([
            'id' => 'required'
        ]);
        $http = json_decode(Http::delete(config('global.Api.usuario_eliminar').$Datosdelete['id']), true);

        session()->flash('toastr', [
            'type' => 'success',
            'message' => 'el usuario eliminó exitosamente',
            'title' => '¡Éxito!'
        ]);
        return redirect()->back();
    }

    public function ruta_ReporteUsuario()
    {
        $Usuarios = json_decode(Http::get(config('global.Api.usuario')), true);

        // Pasar datos a la vista
        $pdf = Pdf::loadView('PDFs.pdf_usuarios', compact('Usuarios'));

        return $pdf->stream('usuarios.pdf');
    }

}

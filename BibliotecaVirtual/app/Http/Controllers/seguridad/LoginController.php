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



class LoginController
{
    public function Accion_Login(Request $request){

        //Data del formulario
        $DatoForm = $request->validate([
            'email' => 'required|string|max:50',
            'password' => 'required|string|max:50',
        ]);

        //Extraer datos de api
        $DataEntidad = json_decode(Http::get(config('global.Api.usuario_correo').$DatoForm['email']), true);
        //existe el dato buscado?
        if ($DataEntidad == null){
            session()->flash(config('global.Mensaje_texto.Usuario_noAsseso'));
            return redirect('/login');
        }
        //dd($DataEntidad['ESTADO_USUARIO'], json_decode(Http::get(config('global.Api.usuarioEstado_id').'2'),true)['DESCRIPCION']);
        if ($DataEntidad['ESTADO_USUARIO'] == json_decode(Http::get(config('global.Api.usuarioEstado_id').'2'),true)['DESCRIPCION']){ //2:bloqueado
            session()->flash(config('global.Mensaje_texto.Usuario_bloqueado'));
            return redirect('/login');
        }
        $Verificar_Contra = $DataEntidad['CONTRASENA'] == $DatoForm['password'];//Hash::check($DatoForm['password'], '$2y$10$hQ1m.3mnBqdKBH845.Y.8ua.3JJeOzDWd0WRiKsbktF');

        /// si la contrase;a no es valida
        if (!$Verificar_Contra){
            if ($DataEntidad['INTENTOS'] <= 1){
                Http::put(config('global.Api.usuario_Estado_bloqueado'), ['p0'=>$DataEntidad['ID_USUARIO']]);
                session()->flash(config('global.Mensaje_texto.Usuario_bloqueado'));
                return redirect('/login');
            }else{
                //$parametro = json_decode(Http::get(config('global.Api.parametro_id').'1'), true);
                Http::put(config('global.Api.usuario_intento_Menos'), ['p0'=>$DataEntidad['ID_USUARIO']]);
                session()->flash(config('global.Mensaje_texto.Usuario_noAsseso'));
                return redirect('/login');
            }
        }



        // Si todo es correcto
        ///restablese los intentos
        Http::put(config('global.Api.usuario_intento_Restableser'), ['p0'=>$DataEntidad['ID_USUARIO']]);

        //dd($DataEntidad['ESTADO_USUARIO'], json_decode(Http::get(config('global.Api.usuarioEstado_id').'3'),true)['DESCRIPCION'] );
        //obtener datos para la session
        if ($DataEntidad['ESTADO_USUARIO'] == json_decode(Http::get(config('global.Api.usuarioEstado_id').'1'),true)['DESCRIPCION'] ){  //1: Activar
            Session::put('user_id',     $DataEntidad['ID_USUARIO']);
            Session::put('user_nombre', $DataEntidad['NOMBRE_USUARIO']);
            Session::put('user_Correo', $DataEntidad['CORREO_ELECTRONICO']);
            Session::put('user_Rol',    $DataEntidad['ROL_USUARIO']);
            Session::put('Session',     1);

            return redirect('/');
        }else if ($DataEntidad['ESTADO_USUARIO'] == json_decode(Http::get(config('global.Api.usuarioEstado_id').'3'),true)['DESCRIPCION'] ){ //3: primera vez
            Session::put('user_id',     $DataEntidad['ID_USUARIO']);
            Session::put('user_nombre', $DataEntidad['NOMBRE_USUARIO']);
            Session::put('user_Correo', $DataEntidad['CORREO_ELECTRONICO']);
            Session::put('user_Rol',    $DataEntidad['ROL_USUARIO']);
            Session::put('Session',     1);
            return redirect('/');
        }


    }

    public function Accion_Registrarses(Request $request){

        //Data del formulario
        $DatoForm = $request->validate([
            'name' => 'required|string|max:50',
            'DNI' => 'required|string|max:15',
            'email' => 'required|string|max:50',
            'password' => 'required|string|max:50',
            'password_r' => 'required|string|max:50',
        ]);

        //Extraer datos de api
        $DataEntidad = json_decode(Http::get(config('global.Api.usuario_correo').$DatoForm['email']), true);
        //existe el dato buscado?
        //dd($DataEntidad);
        if ($DataEntidad != null){
            session()->flash(config('global.Mensaje_texto.Usuario_bloqueado'));
            return redirect('/login');
        }

        /// si los input de las contrase;as no son iguales entonces devolverse al login
        if ($DatoForm['password'] != $DatoForm['password_r']) return redirect()->back();

        $hashedPassword = $DatoForm['password'];//Hash::make($DatoForm['password']);
        $parametro_Intentos = json_decode(Http::get(config('global.Api.parametro_id').'1'), true);
        $parametro_DiasVenc = json_decode(Http::get(config('global.Api.parametro_id').'3'), true);
        //$Codigo_Ingreso = Str::random(20);


        $token = Str::random(config('global.variables.token_lenght'));

        $DataEntidad = Http::post(config('global.Api.usuario_add'), [
            "p0" => 4,                  // estado usuario 3:usuario nuevo
            "p1" => 1,                  // rol
            "p2" => 1,                  // centroReginal
            "p3" => strtoupper($DatoForm['name']),  // Nombre Usuario
            "p4" => $hashedPassword,    // contrasena
            "p5" => $DatoForm['email'], // correo electrónico
            "p6" => str_replace(' ', '', $DatoForm['DNI']),   // DNI
            "p7" => Carbon::today(),    // fecha_conexion_ultima
            "p8" => $token,             // cod primer ingreso
            "p9" => Carbon::today()->addDays($parametro_DiasVenc['VALOR']),          // fecha_vencimiento
            "p10"=> intval($parametro_Intentos['VALOR'])
        ]);

        $link_token = URL::temporarySignedRoute('verificacion.Link', now()->addMinutes(config('global.variables.token_time_min')), ['token' => $token]);
        Mail::to($DatoForm['email'])->send(new TestMail($link_token, $DatoForm['name'], 0, $DatoForm['email']));

        session()->flash(config('global.Mensaje_texto.Usuario_resgistrado'));
        return redirect('/login');
        //return $DatoForm['name'].' - '.$DatoForm['DNI'].' - '.$DatoForm['email'].' - '.$DatoForm['password'].' - '.$DatoForm['password_r'];
    }

    public function Accion_Logout(){
        Session::flush();   ///borra todo los datos de una seccion
        return redirect('/login');
    }

    public function ruta_index(){
        return view('index');
    }

    public function ruta_login(){
        //session()->flush();
        //dd($this->generarEnlaceUnico());
        return view('login.login');
    }

    public function accion_Verificacion(Request $request){


        if ($request->hasValidSignature()) {


            $DataBusqueda = json_decode(Http::get(config('global.Api.usuario_Tcd').$request->token), true);

            $new_codigo = Str::random(60);
            $DataEntidad = Http::put(config('global.Api.usuVerific'), [
                "P_ID"          => $DataBusqueda['ID_USUARIO'],
                "P_CODIGO"      => $new_codigo
            ]);

            if ($DataBusqueda['ESTADO_USUARIO'] == json_decode(Http::get(config('global.Api.usuarioEstado_id').'5'),true)['DESCRIPCION']){



                return redirect('/login/CambioContrasena')->with('id_usuario', $DataBusqueda['ID_USUARIO']);
            }






            //dd($request->token, $new_codigo);



            if ($DataBusqueda == null){
                session()->flash(config('global.Mensaje_texto.link_caducado'));
                return redirect('/login');
            }

            $DataEntidad = Http::put(config('global.Api.usuVerific'), [
                "P_ID"          => $DataBusqueda['ID_USUARIO'],
                "P_CODIGO"      => $new_codigo
            ]);

            session()->flash(config('global.Mensaje_texto.link_verificar'));
            return redirect('/login');
        } else {
            session()->flash(config('global.Mensaje_texto.link_caducado'));
            return redirect('/login');
        }
    }

    public function ruta_cambioContra(){

        $id = session('id_usuario');

        return view('login.validacion', compact('id'));
    }

    public function accion_cambioContra(Request $request){

        //Data del formulario
        $DatoForm = $request->validate([
            'id' => 'required',
            'password' => 'required|string|max:50',
            'password_r' => 'required|string|max:50'
        ]);

        if ($DatoForm['password_r'] == $DatoForm['password']){
            $U = json_decode(Http::get(config('global.Api.usuario_id'). $DatoForm['id']), true);

            $token = Str::random(config('global.variables.token_lenght'));
            $parametro_Intentos = json_decode(Http::get(config('global.Api.parametro_id').'1'), true);
            $parametro_DiasVenc = json_decode(Http::get(config('global.Api.parametro_id').'3'), true);

            $DataEntidad = Http::put(config('global.Api.usuario_modify'), [
                "p_ID_USUARIO" => $U['ID_USUARIO'],
                "p_ID_ESTADO_USUARIO"  => 1,
                "p_ID_ROL"  => $U['ID_ROLL'],
                "p_ID_CENTRO_REGIONAL"  => 1,
                "p_NOMBRE_USUARIO"  => $U['NOMBRE_USUARIO'],
                "p_CONTRASENA"  => $DatoForm['password'],
                "p_DNI"  => $U['DNI'],
                "p_CORREO_ELECTRONICO"  => $U['CORREO_ELECTRONICO'],
                "p_FECHA_CONEXION_ULTIMA" => Carbon::today(),
                "p_COD_PRIMER_INGRESO" => "hola",
                "p_FECHA_VENCIMIENTO" => Carbon::today()->addDays($parametro_DiasVenc['VALOR']),
                "p_INTENTOS" => intval($parametro_Intentos['VALOR'])
            ]);


            session()->flash(config('global.Mensaje_texto.Cambio_Contraseña'));
            return redirect('/login');

        }else{
            $U = json_decode(Http::get(config('global.Api.usuario_id'). $DatoForm['id']), true);
            return redirect('/login/CambioContrasena')->with('id_usuario', $U['ID_USUARIO']);
        }


    }

    public function ruta_buscarCorreo(){
        //session()->flush();
        //dd($this->generarEnlaceUnico());
        return view('login.buscarCorreo');
    }
    public function Accion_buscarCorreo(Request $request){

        dd("hola mundo");
        return redirect()->back();
    }
}

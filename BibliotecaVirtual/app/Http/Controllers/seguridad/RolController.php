<?php

namespace App\Http\Controllers\seguridad;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;


class RolController
{
    public function ruta_rol(){
        $Roles = json_decode(Http::get(config('global.Api.usuarioRol')), true);


        return view('Modulos.Seguridad.Seguridad.tabla_rol')->with([
            'Roles' => $Roles
        ]);
    }
    public function Accion_EditarRol(Request $request){
        //Data del formulario
        $DatoForm = $request->validate([
            'id' => '',
            'rol' => 'required|string|max:30',
            'descripcion' => 'required|string|max:100'
        ]);

        $DataEntidad = Http::put(config('global.Api.usuarioRol'), [
            "p_Rol_id"          => $DatoForm['id'],
            "p_Rol_Nombre"      => $DatoForm['rol'],
            "p_Rol_Descripcion" => $DatoForm['descripcion']
        ]);


        session()->flash('toastr', [
            'type' => 'success',
            'message' => 'el Rol '.$DatoForm['rol'].' se editó Exitosamente!!',
            'title' => '¡Éxito!'
        ]);

        return redirect()->back();
    }
}

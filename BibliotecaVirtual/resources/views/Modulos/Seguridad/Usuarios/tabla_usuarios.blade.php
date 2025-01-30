@extends('layout.Layout_Main')

@section('TITULO_PAGINA', 'Usuarios')
@section('PAG_GRUPO', 'Seguridad')
@section('content')



<!--TABLA-->
<div class="card-group">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Usuarios</h5>
                <div class="d-flex gap-2 ms-auto">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                        <i class="bi bi-plus-circle"></i> Agregar
                    </button>
                    <a type="button" class="btn btn-secondary btn_imprimir" href="{{url('pdf-usuarios')}}">
                        <i class="bi bi-printer"></i> Imprimir
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table id="example2" class="table table-bordered table-hover">
                    <thead>
                        <tr class="align-middle">
                            <th>n°</th>
                            <th>Nombre Usuario</th>
                            <th>DNI</th>
                            <th>Estado</th>
                            <th>Rol</th>
                            <th>Correo electrónico</th>
                            <th>Fecha Ultima conexión</th>
                            <th>Intentos</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($Usuarios as $u)
                        <tr>
                            <td>{{ $u['ID_USUARIO'] }}</td>
                            <td>{{ $u['NOMBRE_USUARIO'] }}</td>
                            <td>{{ $u['DNI'] }}</td>
                            <td>{{ $u['ESTADO_USUARIO'] }}</td>
                            <td>{{ $u['ROL_USUARIO'] }}</td>
                            <td>{{ $u['CORREO_ELECTRONICO'] }}</td>
                            <td>{{ $u['FECHA_CONEXION_ULTIMA'] }}</td>
                            <td>{{ $u['INTENTOS'] }}</td>
                            <td>
                                <div class="button-group">
                                    <button type="button" class="btn btn-warning btn-sm btn_editar" data-bs-toggle="modal" data-bs-target="#editModal_{{$u['ID_ESTADO_USUARIO']}}"
                                        data-id="{{ $u['ID_USUARIO'] }}"
                                        data-nombre="{{ $u['NOMBRE_USUARIO'] }}"
                                        data-dni="{{ $u['DNI'] }}"
                                        data-estado="{{ $u['ESTADO_USUARIO'] }}"
                                        data-rol="{{ $u['ROL_USUARIO'] }}"
                                        data-correo="{{ $u['CORREO_ELECTRONICO'] }}">
                                        <i class="bi bi-pen-fill"></i> Editar
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm btn_eliminar" data-bs-toggle="modal" data-bs-target="#deleteModal"
                                        data-id="{{ $u['ID_USUARIO'] }}">
                                        <i class="bi bi-trash3-fill"></i> Eliminar
                                    </button>
                                    <button class="btn btn-info" data-toggle="modal" data-target="#myModal" data-id="{{ $u['ID_USUARIO'] }}">Ver Detalles</button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

    <!-- Modal para Agregar -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Crear Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                        <div class="row">
                            <div class="col-2">
                                <img src="{{asset('dist/img/default-150x150.png')}}" class="img-thumbnail" alt="Cinque Terre" width="150px" height="150px">
                            </div>
                            <div class="col-10">

                                <div class="row">
                                    <label for="titulo" class="form-label">Datos Personales</label>
                                    <div class="col-12">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend"> <span class="input-group-text">Nombre</span></div>
                                            <input type="text" class="form-control" placeholder="Texto">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend"> <span class="input-group-text">Apellido</span></div>
                                            <input type="text" class="form-control" placeholder="Texto">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend"> <span class="input-group-text">RTN</span></div>
                                            <input type="text" class="form-control" placeholder="Texto">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <label for="titulo" class="form-label">Datos de Usuario</label>
                            <div class="col-12">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend"> <span class="input-group-text">Usuario</span></div>
                                    <input type="text" class="form-control" placeholder="Texto">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend"> <span class="input-group-text">Correo</span></div>
                                    <input type="text" class="form-control" placeholder="Texto">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend"> <span class="input-group-text">Contraseña</span></div>
                                    <input type="text" class="form-control" placeholder="Texto">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <label for="titulo" class="form-label">Registro</label>
                            <div class="col-4">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Estado</span>
                                    <select class="form-control">
                                    <option>option 1</option>
                                    <option>option 2</option>
                                    <option>option 3</option>
                                    <option>option 4</option>
                                    <option>option 5</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rol</span>
                                    <select class="form-control">
                                    <option>option 1</option>
                                    <option>option 2</option>
                                    <option>option 3</option>
                                    <option>option 4</option>
                                    <option>option 5</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend"> <span class="input-group-text">Codigo Primer Ingreso</span></div>
                                    <input type="text" class="form-control" placeholder="Texto">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Centro Regional</span>
                                    <select class="form-control">
                                    <option>option 1</option>
                                    <option>option 2</option>
                                    <option>option 3</option>
                                    <option>option 4</option>
                                    <option>option 5</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary">Agregar</button>
                </div>
            </div>
        </div>
</div>
@foreach($Usuarios as $u)
    <!-- Modal de Edición -->
    <div class="modal fade" id="editModal_{{$u['ID_ESTADO_USUARIO']}}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        <input type="hidden" id="usuario_id">
                        <div class="mb-3">
                            <label for="nombre_usuario" class="form-label">Nombre Usuario</label>
                            <input type="text" class="form-control" id="nombre_usuario" value="{{ $u['NOMBRE_USUARIO'] }}">
                        </div>
                        <div class="mb-3">
                            <label for="dni" class="form-label">DNI</label>
                            <input type="text" class="form-control" value={{ $u['DNI'] }}>
                        </div>
                        <div class="mb-3">
                            <label for="estado_usuario" class="form-label">Estado</label>
                            <select class="form-control" id="estado_usuario">

                                @foreach($Estado as $e)
                                    @php
                                        $sld = ( $e['ID_ESTADO_USUARIO'] == $u['ID_ESTADO_USUARIO'] ) ? 'selected' : '';
                                    @endphp
                                    <option value="{{ $e['ID_ESTADO_USUARIO'] }}" {{ $sld }}> {{ $e['DESCRIPCION'] }} </option>
                                @endforeach

                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="rol_usuario" class="form-label">Rol</label>
                            <select class="form-control" id="rol_usuario">
                                @foreach($Rol as $r)
                                    @php
                                        $sld = ( $r['ID_ROL'] == $u['ID_ROLL'] ) ? 'selected' : '';
                                    @endphp
                                    <option value="{{ $r['ID_ROL'] }}" {{ $sld }}> {{ $r['DESCRIPCION'] }} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="correo_electronico" class="form-label">Correo electrónico</label>
                            <input type="email" class="form-control" id="correo_electronico" value="{{ $u['CORREO_ELECTRONICO'] }}">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="saveChanges">Guardar Cambios</button>
                </div>
            </div>
        </div>
    </div>
@endforeach


<!-- Modal de Eliminación -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="delete_usuario_id">
                ¿Está seguro de que desea eliminar este usuario?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Eliminar</button>
            </div>
        </div>
    </div>
</div>


@endsection


@extends('layout.Layout_Main')

@section('TITULO_PAGINA', 'Usuarios')
@section('PAG_GRUPO', 'Seguridad')
@section('content')


@php
$contador = 1;
@endphp
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
                            <th class="col-1">n°</th>
                            <th class="col-1">Nombre Usuario</th>
                            <th class="col-1">DNI</th>
                            <th class="col-1">Estado</th>
                            <th class="col-1">Rol</th>
                            <th class="col-2">Correo electrónico</th>
                            <th class="col-1">Fecha Ultima conexión</th>
                            <th class="col-1">Intentos</th>
                            <th class="col-1">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($Usuarios as $u)
                        <tr>
                            <td>{{ $contador++ }}</td>
                            <td>{{ $u['NOMBRE_USUARIO'] }}</td>
                            <td>{{ $u['DNI'] }}</td>
                            <td>{{ $u['ESTADO_USUARIO'] }}</td>
                            <td>{{ $u['ROL_USUARIO'] }}</td>
                            <td>{{ $u['CORREO_ELECTRONICO'] }}</td>
                            <td>{{ $u['FECHA_CONEXION_ULTIMA'] }}</td>
                            <td>{{ $u['INTENTOS'] }}</td>
                            <td>
                                @if($u['ID_USUARIO'] != Session::get('user_id'))
                                <div class="button-group">
                                    <button type="button" class="btn btn-warning btn-sm btn_editar" data-bs-toggle="modal" data-bs-target="#editModal"
                                        data-id="{{ $u['ID_USUARIO'] }}"
                                        data-nombre="{{ $u['NOMBRE_USUARIO'] }}"
                                        data-dni="{{ $u['DNI'] }}"
                                        data-estado="{{ $u['ID_ESTADO_USUARIO'] }}"
                                        data-rol="{{ $u['ID_ROLL'] }}"
                                        data-correo="{{ $u['CORREO_ELECTRONICO'] }}">
                                        <i class="bi bi-pen-fill"></i> Editar
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm btn_eliminar" data-bs-toggle="modal" data-bs-target="#deleteModal"
                                        data-id="{{ $u['ID_USUARIO'] }}"
                                        data-usuario="{{ $u['NOMBRE_USUARIO'] }}">
                                        <i class="bi bi-trash3-fill"></i> Eliminar
                                    </button>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="col-1">n°</th>
                            <th class="col-1">Nombre Usuario</th>
                            <th class="col-1">DNI</th>
                            <th class="col-1">Estado</th>
                            <th class="col-1">Rol</th>
                            <th class="col-2">Correo electrónico</th>
                            <th class="col-1">Fecha Ultima conexión</th>
                            <th class="col-1">Intentos</th>
                            <th class="col-1">Acciones</th>
                        </tr>
                        </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>



<!-- MODAL DE EDICIÓN -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Editar Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addForm" method="POST" action="/table_usuarios/agregar">
                @csrf

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" name="nombre" maxlength="50" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_dni" class="form-label">DNI</label>
                        <input type="text" class="form-control" name="DNI" maxlength="15" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_estado" class="form-label">Estado</label>
                        <!-- Select para Estado -->
                        <select class="form-control" name="estado">
                            @foreach($Estado as $e)
                            @if($e['ID_ESTADO_USUARIO'] == '5')
                                <option value="{{ $e['ID_ESTADO_USUARIO'] }}">
                                    {{ $e['DESCRIPCION'] }}
                                </option>
                            @endif
                            @endforeach
                        </select>

                    </div>
                    <div class="mb-3">
                        <label for="  " class="form-label">Rol</label>

                        <!-- Select para Rol -->
                        <select class="form-control" name="rol">
                            @foreach($Rol as $r)
                                    <option value="{{ $r['ID_ROL'] }}">
                                        {{ $r['DESCRIPCION'] }}
                                    </option>
                            @endforeach
                        </select>

                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" name="correo" maxlength="50" required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- MODAL DE EDICIÓN -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Editar Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm" method="POST" action="/table_usuarios/actualizar">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="edit_id">

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="edit_nombre" name="nombre" maxlength="50" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_dni" class="form-label">DNI</label>
                        <input type="text" class="form-control" id="edit_dni" name="DNI" maxlength="15" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_estado" class="form-label">Estado</label>
                        <!-- Select para Estado -->
                        <select class="form-control" id="estado_usuario" name="estado">
                            @foreach($Estado as $e)
                                <option value="{{ $e['ID_ESTADO_USUARIO'] }}">
                                    {{ $e['DESCRIPCION'] }}
                                </option>
                            @endforeach
                        </select>

                    </div>
                    <div class="mb-3">
                        <label for="  " class="form-label">Rol</label>

                        <!-- Select para Rol -->
                        <select class="form-control" id="edit_rol" name="rol">
                            @foreach($Rol as $r)
                                <option value="{{ $r['ID_ROL'] }}">
                                    {{ $r['DESCRIPCION'] }}
                                </option>
                            @endforeach
                        </select>

                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="edit_correo" name="correo" maxlength="50" required>
                    </div>
                    <div class="mb-3form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" name="CambioContra" value=true>
                        <label class="form-check-label" for="flexSwitchCheckDefault">Cambiar Contraseña</label>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="openConfirmModal">Guardar Cambios</button>

                    <!--<button type="submit" class="btn btn-primary">Guardar Cambios</button> -->
                </div>
            </form>
        </div>
    </div>
</div>



<!-- Modal de Eliminación -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="/table_usuarios/eliminar" method="POST">
                <div class="modal-body">

                        @csrf
                        @method('DELETE')
                        <input type="hidden" id="eliminar_id" name="id">
                        <input type="text" class="form-control" id="usuario_name" readonly>
                        <p>
                            ¿Está seguro de que desea eliminar este usuario?
                        </p>



                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="sudmit" class="btn btn-danger" id="confirmDelete">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>




<!-- MODAL DE CONFIRMACIÓN -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h5 class="modal-title w-100" id="confirmModalLabel">Confirmación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div class="warning-icon">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <p class="mt-3">¿Estás seguro de que deseas guardar los cambios?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmSave">Sí, Guardar</button>
            </div>
        </div>
    </div>
</div>




<script>
    // pasar datos a los modales
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".btn_editar").forEach(button => {
            button.addEventListener("click", function () {
                let id = this.getAttribute("data-id");
                let nombre = this.getAttribute("data-nombre");
                let dni = this.getAttribute("data-dni");
                let estado = this.getAttribute("data-estado");
                let rol = this.getAttribute("data-rol");
                let correo = this.getAttribute("data-correo");

                // Rellenar el formulario del modal
                document.getElementById("edit_id").value = id;
                document.getElementById("edit_nombre").value = nombre;
                document.getElementById("edit_dni").value = dni;
                document.getElementById("edit_rol").value = rol;
                document.getElementById("edit_correo").value = correo;

                // Seleccionar la opción correcta en el select de estado
                let estadoSelect = document.getElementById("estado_usuario");
                estadoSelect.value = estado;

                // Opcional: Forzar el cambio visualmente por si Bootstrap no lo actualiza automáticamente
                estadoSelect.querySelectorAll('option').forEach(option => {
                    option.selected = (option.value === estado);
                });
            });
        });
        document.querySelectorAll(".btn_eliminar").forEach(button => {
            button.addEventListener("click", function () {
                let id = this.getAttribute("data-id");
                let name = this.getAttribute("data-usuario");

                // Rellenar el formulario del modal
                document.getElementById("eliminar_id").value = id;
                document.getElementById("usuario_name").value = name;

            });
        });
    });

    document.addEventListener("DOMContentLoaded", function () {
        let editModal = new bootstrap.Modal(document.getElementById('editModal'));
        let confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));

        // Al hacer clic en "Guardar Cambios", abrir el modal de confirmación
        document.getElementById("openConfirmModal").addEventListener("click", function () {
            confirmModal.show();
        });

        // Al confirmar la acción, enviar el formulario
        document.getElementById("confirmSave").addEventListener("click", function () {
            document.getElementById("editForm").submit();
        });
    });

</script>


@endsection


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
                <div class="row">
                    <div class="table-responsive">
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
                                @php
                                    $color = null;
                                    if ($u['ID_USUARIO'] == Session::get('user_id')) $color = 'table-secondary';
                                    else if ($u['ID_ESTADO_USUARIO'] == 5 || $u['ID_ESTADO_USUARIO'] == 4) $color = 'table-warning';
                                    else if ($u['ID_ESTADO_USUARIO'] == 6) $color = 'table-danger';
                                @endphp
                                <tr class="{{$color}}">
                                    <td>{{ $contador++ }}</td>
                                    <td>{{ $u['NOMBRE_USUARIO'] }}</td>
                                    <td>{{ substr($u['DNI'], 0, 4) . '-' . substr($u['DNI'], 4, 4) . '-' . substr($u['DNI'], 8) }}</td>
                                    <td>{{ $u['ESTADO_USUARIO'] }}</td>
                                    <td>{{ $u['ROL_USUARIO'] }}</td>
                                    <td>{{ $u['CORREO_ELECTRONICO'] }}</td>
                                    <td>{{ $u['FECHA_CONEXION_ULTIMA'] }}</td>
                                    <td>{{ $u['INTENTOS'] }}</td>
                                    <td>
                                        @if($u['ID_USUARIO'] != Session::get('user_id'))
                                        <div class="button-group">
                                            <button type="button" class="btn_editar btn-sm" data-bs-toggle="modal" data-bs-target="#editModal"
                                                data-id="{{ $u['ID_USUARIO'] }}"
                                                data-nombre="{{ $u['NOMBRE_USUARIO'] }}"
                                                data-dni="{{ $u['DNI'] }}"
                                                data-estado="{{ $u['ID_ESTADO_USUARIO'] }}"
                                                data-rol="{{ $u['ID_ROLL'] }}"
                                                data-email="{{ $u['CORREO_ELECTRONICO'] }}">
                                                <i class="bi bi-pen-fill"></i> Editar
                                            </button>
                                            <button type="button" class="btn_eliminar btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal"
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
    </div>
</div>



<!-- MODAL DE EDICIÓN -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Editar Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addForm" method="POST" action="/usuarios/agregar">
                @csrf

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control input_letras" name="nombre" maxlength="50" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_dni" class="form-label">DNI</label>
                        <input type="text" class="form-control formato_texto_DNI" id="texto_dni_add" name="DNI" minlength="15" maxlength="15" placeholder="0000-0000-00000" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_estado" class="form-label">Estado</label>
                        <!-- Select para Estado -->
                        <select class="form-control" name="estado"  disabled>
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
                                        {{ $r['ROL'] }}
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
                    <button type="submit" class="btn btn-primary" >Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- MODAL DE ELIMINACIÓN -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="/usuarios/eliminar" method="POST" id="deleteForm">
                <div class="modal-body">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" id="eliminar_id" name="id">
                    <input type="text" class="form-control input_letras" id="usuario_name" readonly>
                    <p>¿Está seguro de que desea eliminar este usuario?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="openConfirmDeleteModal">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL DE CONFIRMACIÓN DE ELIMINACIÓN -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h5 class="modal-title w-100">Confirmación Final</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <div class="warning-icon">
                    <i class="fas fa-exclamation-triangle fa-3x text-danger"></i>
                </div>
                <p class="mt-3">Esta acción es irreversible. ¿Deseas continuar?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Sí, Eliminar</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Seleccionar todos los botones con la clase 'btn_eliminar'
        document.querySelectorAll(".btn_eliminar").forEach(button => {
            button.addEventListener("click", function () {
                // Extraer los atributos de datos del botón clickeado
                let userId = this.getAttribute("data-id");
                let userName = this.getAttribute("data-usuario");

                // Insertar los valores en los campos del modal
                document.getElementById("eliminar_id").value = userId;
                document.getElementById("usuario_name").value = userName;
            });
        });

        // Manejo del modal de confirmación
        document.getElementById("openConfirmDeleteModal").addEventListener("click", function () {
            let confirmDeleteModal = new bootstrap.Modal(document.getElementById("confirmDeleteModal"));
            confirmDeleteModal.show();
        });

        // Confirmar eliminación y enviar formulario
        document.getElementById("confirmDelete").addEventListener("click", function () {
            document.getElementById("deleteForm").submit();
        });
    });
</script>







<!-- MODAL DE EDICIÓN -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Editar Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm" method="POST" action="/usuarios/actualizar">
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
                        <input type="text" class="form-control" id="texto_dni_edit" name="DNI" maxlength="15" minlength="15" placeholder="0000-0000-00000" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_estado" class="form-label">Estado</label>
                        <select class="form-control" id="estado_usuario" name="estado">
                            @foreach($Estado as $e)
                                <option value="{{ $e['ID_ESTADO_USUARIO'] }}">{{ $e['DESCRIPCION'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_rol" class="form-label">Rol</label>
                        <select class="form-control" id="edit_rol" name="rol">
                            @foreach($Rol as $r)
                                <option value="{{ $r['ID_ROL'] }}">{{ $r['ROL'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_correo" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="edit_correo" name="correo" maxlength="50" required>
                    </div>
                    <div class="mb'3">
                        <label class="form-label">cambio de Contraseña</label>
                        <div class="form-check form-switch">
                            <input class="mb-3 form-check-input" type="checkbox" id="flexSwitchCheckDefault" name="CambioContra" value="true">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="openConfirmModal" disabled>Guardar Cambios</button>
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
                <i class="fas fa-exclamation-circle" style="font-size: 2rem; color: red;"></i>
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
    document.addEventListener("DOMContentLoaded", function () {
        let editModal = new bootstrap.Modal(document.getElementById('editModal'));
        let confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));

        let editForm = document.getElementById("editForm");
        let editNombre = document.getElementById("edit_nombre");
        let editDNI = document.getElementById("texto_dni_edit");
        let editCorreo = document.getElementById("edit_correo");
        let editEstado = document.getElementById("estado_usuario");
        let editRol = document.getElementById("edit_rol");
        let openConfirmModalBtn = document.getElementById("openConfirmModal");
        let confirmSaveBtn = document.getElementById("confirmSave");

        document.querySelectorAll(".btn_editar").forEach(button => {
            button.addEventListener("click", function () {
                document.getElementById("edit_id").value = this.getAttribute("data-id");
                editNombre.value = this.getAttribute("data-nombre");
                editDNI.value = this.getAttribute("data-dni");
                editCorreo.value = this.getAttribute("data-email") || "";
                editEstado.value = this.getAttribute("data-estado");
                editRol.value = this.getAttribute("data-rol");

                validateForm();
            });
        });

        function validateForm() {
            let nombreValido = editNombre.value.trim() !== "";
            let dniValido = editDNI.value.trim().length === 15;
            let correoValido = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(editCorreo.value.trim());
            let estadoValido = editEstado.value !== "";
            let rolValido = editRol.value !== "";

            let isValid = nombreValido && dniValido && correoValido && estadoValido && rolValido;
            openConfirmModalBtn.disabled = !isValid;
        }

        editNombre.addEventListener("input", validateForm);
        editDNI.addEventListener("input", validateForm);
        editCorreo.addEventListener("input", validateForm);
        editEstado.addEventListener("change", validateForm);
        editRol.addEventListener("change", validateForm);

        openConfirmModalBtn.addEventListener("click", function () {
            if (!openConfirmModalBtn.disabled) {
                confirmModal.show();
            }
        });

        confirmSaveBtn.addEventListener("click", function () {
            console.log("Formulario enviado");
            editForm.submit();
        });

        validateForm();
    });
</script>




<script>
    document.addEventListener("DOMContentLoaded", function () {
        let editDNI = document.getElementById("texto_dni_edit");

        // Función para formatear el DNI en 0000-0000-00000
        function formatDNI(value) {
            // Remover cualquier carácter que no sea número
            value = value.replace(/\D/g, '');

            // Aplicar el formato con guiones
            if (value.length > 4) {
                value = value.substring(0, 4) + '-' + value.substring(4);
            }
            if (value.length > 9) {
                value = value.substring(0, 9) + '-' + value.substring(9);
            }
            return value;
        }

        // Aplicar el formato cuando el usuario escribe
        editDNI.addEventListener("input", function () {
            this.value = formatDNI(this.value);
        });

        // Aplicar el formato cuando se abre el modal
        document.getElementById('editModal').addEventListener('show.bs.modal', function () {
            editDNI.value = formatDNI(editDNI.value); // Formatea el valor actual antes de mostrar el modal
        });

        // Aplicar el formato cuando se pasan datos al modal
        document.querySelectorAll(".btn_editar").forEach(button => {
            button.addEventListener("click", function () {
                let dni = this.getAttribute("data-dni");
                editDNI.value = formatDNI(dni); // Formatea el valor antes de mostrarlo
            });
        });
    });
    document.addEventListener("DOMContentLoaded", function () {
        let editDNI = document.getElementById("texto_dni_add");

        // Función para formatear el DNI en 0000-0000-00000
        function formatDNI(value) {
            // Remover cualquier carácter que no sea número
            value = value.replace(/\D/g, '');

            // Aplicar el formato con guiones
            if (value.length > 4) {
                value = value.substring(0, 4) + '-' + value.substring(4);
            }
            if (value.length > 9) {
                value = value.substring(0, 9) + '-' + value.substring(9);
            }
            return value;
        }

        // Aplicar el formato cuando el usuario escribe
        editDNI.addEventListener("input", function () {
            this.value = formatDNI(this.value);
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
            const input_MAYUS = document.querySelectorAll(".input_letras");
            input_MAYUS.forEach(input => {
                input.addEventListener("input", function() {
                    this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '').toUpperCase();
                });
            });

            const input_NUMERO = document.querySelectorAll(".input_numeros");
            input_NUMERO.forEach(input => {
                input.addEventListener("input", function() {
                    this.value = this.value.replace(/[^0-9]/g, ''); // Solo permite números (0-9)
                });
            });
    });
</script>





@endsection


@extends('layout.Layout_Main')

@section('TITULO_PAGINA', 'Rol')
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
                <h5 class="card-title mb-0">Roles</h5>
                <button type="button" class="btn btn-primary ms-auto" data-bs-toggle="modal" data-bs-target="#addModal">
                    <i class="bi bi-plus-circle">Agregar</i>
                </button>
            </div>
            <div class="card-body">
                <table id="example2" class="table table-bordered table-hover">
                    <thead>
                        <tr class="align-middle">
                            <th>n°</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($Roles as $r)
                        @php
                            $color = null;
                            if ($r['ROL'] == Session::get('user_Rol')) $color = 'table-secondary';
                        @endphp
                        <tr class="{{$color}}">
                            <td>{{ $contador++ }}</td>
                            <td>{{ $r['ROL'] }}</td>
                            <td>{{ $r['DESCRIPCION'] }}</td>
                            <td>
                                <div class="button-group">
                                    <button type="button" class="btn_editar btn-sm" data-bs-toggle="modal" data-bs-target="#editModal"
                                        data-id="{{ $r['ID_ROL'] }}"
                                        data-rol="{{ $r['ROL'] }}"
                                        data-descripcion="{{ $r['DESCRIPCION'] }}"
                                        <i class="bi bi-pen-fill"></i> Editar
                                    </button>
                                    <button type="button" class="btn_eliminar btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal"
                                        data-id="{{ $r['ID_ROL'] }}"
                                        data-rol="{{ $r['ROL'] }}">
                                        <i class="bi bi-trash3-fill"></i> Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="align-middle">
                            <th>n°</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- MODAL DE AGREGAR -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Agregar Rol</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addForm" method="POST" action="/roles/agregar">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rol_nombre" class="form-label">Nombre del Rol</label>
                        <input type="text" class="form-control input_letras" name="rol" maxlength="30" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_descripcion" class="form-label">Descripción</label>
                        <textarea type="text" class="form-control input_letras" name="descripcion" maxlength="100" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL DE EDICIÓN -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Editar Rol</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm" method="POST" action="/roles/actualizar">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="edit_id">

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_rol" class="form-label">Nombre del Rol</label>
                        <input type="text" class="form-control input_letras" id="edit_rol" name="rol" maxlength="30" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_descripcion" class="form-label">Descripción</label>
                        <textarea type="text" class="form-control input_letras" id="edit_descripcion" name="descripcion" maxlength="100" required></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="openConfirmModal">Guardar Cambios</button>
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
    document.addEventListener("DOMContentLoaded", function () {
        let editModal = new bootstrap.Modal(document.getElementById('editModal'));
        let confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));

        let editForm = document.getElementById("editForm");
        let editRol = document.getElementById("edit_rol");
        let editDescripcion = document.getElementById("edit_descripcion");
        let openConfirmModalBtn = document.getElementById("openConfirmModal");
        let confirmSaveBtn = document.getElementById("confirmSave");

        // Función para validar los inputs
        function validateForm() {
            let isValid = editRol.value.trim() !== "" && editDescripcion.value.trim() !== "";
            openConfirmModalBtn.disabled = !isValid; // Habilita o deshabilita el botón
        }

        // Escuchar eventos en los inputs
        editRol.addEventListener("input", validateForm);
        editDescripcion.addEventListener("input", validateForm);

        // Deshabilitar el botón al abrir el modal de edición
        document.getElementById('editModal').addEventListener('show.bs.modal', function () {
            validateForm();
        });

        // Al hacer clic en "Guardar Cambios", abrir el modal de confirmación solo si está habilitado
        openConfirmModalBtn.addEventListener("click", function () {
            if (!openConfirmModalBtn.disabled) {
                confirmModal.show();
            }
        });

        // Al confirmar la acción, enviar el formulario
        confirmSaveBtn.addEventListener("click", function () {
            editForm.submit();
        });

        // Asegurar que el botón esté deshabilitado al cargar la página
        validateForm();
    });
</script>

<!-- MODAL DE ELIMINACIÓN -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="/roles/eliminar" method="POST" id="deleteform">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <input type="hidden" id="eliminar_id" name="id">
                    <input type="text" class="form-control" id="rol_name" readonly>
                    <p>¿Está seguro de que desea eliminar este rol?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".btn_editar").forEach(button => {
            button.addEventListener("click", function () {
                let id = this.getAttribute("data-id");
                let rol = this.getAttribute("data-rol");
                let estado = this.getAttribute("data-descripcion");

                document.getElementById("edit_id").value = id;
                document.getElementById("edit_rol").value = rol;
                document.getElementById("edit_descripcion").textContent = estado;
            });
        });

        document.querySelectorAll(".btn_eliminar").forEach(button => {
            button.addEventListener("click", function () {
                let id = this.getAttribute("data-id");
                let name = this.getAttribute("data-rol");

                document.getElementById("eliminar_id").value = id;
                document.getElementById("rol_name").value = name;
            });
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

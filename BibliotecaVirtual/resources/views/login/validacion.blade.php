<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Validación de Registro</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
  <style>
    body {
      position: relative;
      margin: 0;
      height: 100vh;
      background: none;
      overflow: hidden;
    }

    /* Fondo fijo con desenfoque */
    body::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: url('{{ asset('asset/imagenes/biblioteca.jpg') }}') no-repeat center center fixed;
      background-size: cover;
      filter: blur(10px); /* Desenfoque permanente */
      z-index: -1;
    }

    /* Superposición oscura */
    body::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.3);
      z-index: -1;
    }

    .content {
      position: relative;
      color: white;
      z-index: 1; /* Asegura que el contenido esté sobre el fondo */
      text-align: center;
      padding: 20px;
    }

    .login-card-body {
      background: rgba(255, 255, 255, 0.9); /* Fondo blanco semitransparente */
      border-radius: 16px;
    }
  </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="#"><b style="color: black !important">Biblioteca</b><p style="color: black !important">Infop</p></a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
                <!-- Register Form -->
                <form action="Accion_CambioContra" method="POST">
                    @csrf
                    @method('PUT')
                    <p class="login-box-msg">Cambio de Contraseña</p>

                    <input type="hidden" name="id" id="id" value="{{$id}}">
                    <!-- Password Input -->
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control password" placeholder="Contraseña" maxlength="50" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Password Input -->
                    <div class="input-group mb-3">
                        <input type="password" name="password_r" class="form-control password_r" placeholder="Confirmar Contraseña" maxlength="50" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <!-- Submit Button -->
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block submit-btn" disabled>Registrarse</button>
                        </div>
                    </div>
                </form>

                <script>

                    document.querySelectorAll('.formato-dni').forEach(function(input) {
                        input.addEventListener('input', function() {
                        let value = input.value.replace(/\D/g, ''); // Elimina todo lo que no sea número
                        // Aplicamos el formato
                        if (value.length <= 4) {
                            input.value = value; // Hasta 4 dígitos, no se agregan espacios
                        } else if (value.length <= 8) {
                            input.value = value.replace(/(\d{4})(\d{0,4})/, '$1 $2'); // Después de 4, se agrega un espacio
                        } else {
                            input.value = value.replace(/(\d{4})(\d{4})(\d{0,5})/, '$1 $2 $3'); // Después de 8, se agrega otro espacio
                        }
                        });
                    });

                    // Aplicamos un evento para todos los inputs con la clase 'solo-numeros'
                    document.querySelectorAll('.solo-numeros').forEach(function(input) {
                        input.addEventListener('input', function() {
                        input.value = input.value.replace(/[^0-9]/g, ''); // Solo permite números
                        });
                    });


                    // Función para verificar si las contraseñas coinciden y aplicar clases
                    function checkPasswordsMatch() {
                        const password = document.querySelector('.password');
                        const confirmPassword = document.querySelector('.password_r');
                        const submitButton = document.querySelector('.submit-btn');

                        // Verificar si las contraseñas coinciden
                        if (password.value !== confirmPassword.value) {
                        confirmPassword.classList.add('is-invalid'); // Añadir clase para borde rojo
                        confirmPassword.classList.remove('is-valid'); // Eliminar clase de borde verde
                        submitButton.disabled = true; // Deshabilitar el botón de submit
                        } else {
                        confirmPassword.classList.remove('is-invalid'); // Eliminar clase de borde rojo
                        confirmPassword.classList.add('is-valid'); // Añadir clase de borde verde
                        submitButton.disabled = false; // Habilitar el botón de submit
                        }
                    }

                    // Añadir los eventos a los campos de contraseña
                    document.querySelector('.password').addEventListener('input', checkPasswordsMatch);
                    document.querySelector('.password_r').addEventListener('input', checkPasswordsMatch);


                    // Función para verificar que el campo tenga exactamente 15 caracteres
                    function checkExactLength() {
                        const input = document.querySelector('.input-exact');
                        const submitButton = document.querySelector('.submit-btn');

                        // Verificar si el campo tiene exactamente 15 caracteres
                        if (input.value.length !== 15) {
                        input.classList.add('is-invalid'); // Añadir clase para borde rojo
                        input.classList.remove('is-valid'); // Eliminar clase de borde verde
                        submitButton.disabled = true; // Deshabilitar el botón de submit
                        } else {
                        input.classList.remove('is-invalid'); // Eliminar clase de borde rojo
                        input.classList.add('is-valid'); // Añadir clase de borde verde
                        submitButton.disabled = false; // Habilitar el botón de submit
                        }
                    }

                    // Añadir los eventos al campo de entrada
                    document.querySelector('.input-exact').addEventListener('input', checkExactLength);

                    </script>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>
</body>
</html>

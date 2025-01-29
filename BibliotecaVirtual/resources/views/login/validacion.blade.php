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
      <p class="login-box-msg"><strong>Validacion Gmail</strong></p>
      <p>
        Estimado usuario, para completar el proceso de registro con el nombre <strong>[Nombre del Usuario]</strong>
        y el correo <strong>[correo@ejemplo.com]</strong>, deberá presentarse personalmente en la biblioteca del INFOP.
      </p>

      <form action="/login/Validacion" method="POST">
        @csrf
        <!-- Name Input -->
        <div class="input-group mb-3">
          <input type="text" name="name" class="form-control mayusculas" placeholder="Codigo" maxlength="20" required>
          <div class="input-group-append">
            <div class="input-group-text">
            
            </div>
          </div>
        </div>
        <!-- Submit Button -->
        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block submit-btn" disabled>Registrarse</button>
          </div>
        </div>

        <!-- Switch to Login -->
        <div class="mt-3 text-center">
          <a href="#" onclick="switchForm('loginForm')">¿Ya tienes cuenta? Inicia sesión aquí</a>
        </div>
      </form>

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

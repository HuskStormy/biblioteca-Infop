<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte</title>
    <link rel="stylesheet" href="asset/pdf.css">
</head>
<body>
    <div class="header">
        <img src="{{public_path().'/asset/imagenes/infop_logo2.png'}}" alt="">
        <h1>Usuarios</h1>
    </div>

    <div class="details">
        <p><strong>Creado por:</strong> {{ Session::get('user_nombre'); }}</p>
        <p><strong>Fecha de creación:</strong> {{ date('d/m/Y') }}</p>
        <p><strong>Descripción:</strong> agregar una descripcion</p>
    </div>
    @php
    $contador = 1;
    @endphp

    <table>
        <thead>
            <tr>
                <th class="col-1">n°</th>
                <th class="col-1">Nombre Usuario</th>
                <th class="col-1">DNI</th>
                <th class="col-1">Rol</th>
                <th class="col-2">Correo electrónico</th>
            </tr>
        </thead>
        <tbody>
            @foreach($Usuarios as $u)
            <tr>
                <td>{{ $contador++ }}</td>
                <td>{{ $u['NOMBRE_USUARIO'] }}</td>
                <td>{{ $u['DNI'] }}</td>
                <td>{{ $u['ROL_USUARIO'] }}</td>
                <td>{{ $u['CORREO_ELECTRONICO'] }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th class="col-1">n°</th>
                <th class="col-1">Nombre Usuario</th>
                <th class="col-1">DNI</th>
                <th class="col-1">Rol</th>
                <th class="col-2">Correo electrónico</th>
            </tr>
        </tfoot>
    </table>

    <script type="text/php">
        if ( isset($pdf) ) {
            $pdf->page_script('
                $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
                $pdf->text(270, 780, "Pagina $PAGE_NUM de $PAGE_COUNT", $font, 10);
            ');
        }
    </script>
</body>
</html>

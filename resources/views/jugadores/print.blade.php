<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">

    <title>Listado de Jugadores</title>

    <style>

        body{
            font-family: Arial, Helvetica, sans-serif;
            margin:40px;
            color:#222;
        }

        h1{
            text-align:center;
            margin-bottom:5px;
        }

        h3{
            text-align:center;
            font-weight:normal;
            color:#666;
            margin-top:0;
        }

        table{
            width:100%;
            border-collapse:collapse;
            margin-top:30px;
        }

        th{
            background:#1e3a8a;
            color:white;
            padding:10px;
            border:1px solid #ddd;
            font-size:13px;
        }

        td{
            padding:8px;
            border:1px solid #ddd;
            font-size:12px;
        }

        tr:nth-child(even){
            background:#f7f7f7;
        }

        .footer{
            margin-top:30px;
            text-align:right;
            color:#666;
            font-size:12px;
        }

    </style>

</head>

<body>

<h1>LISTADO DE JUGADORES</h1>

<h3>{{ date('d/m/Y H:i') }}</h3>

<table>

    <thead>

        <tr>

            <th>#</th>
            <th>Nombre</th>
            <th>Documento</th>
            <th>Categoría</th>
            <th>Equipo</th>
            <th>Posición</th>
            <th>Estado</th>

        </tr>

    </thead>

    <tbody>

    @foreach($jugadores as $jugador)

        <tr>

            <td>{{ $loop->iteration }}</td>

            <td>

                {{ $jugador->nombres }}
                {{ $jugador->apellidos }}

            </td>

            <td>

                {{ $jugador->numero_documento }}

            </td>

            <td>

                {{ $jugador->categoria->nombre ?? '' }}

            </td>

            <td>

                {{ $jugador->equipo->nombre ?? '' }}

            </td>

            <td>

                {{ $jugador->posicion }}

            </td>

            <td>

                {{ $jugador->activo ? 'ACTIVO' : 'INACTIVO' }}

            </td>

        </tr>

    @endforeach

    </tbody>

</table>

<div class="footer">

    Documento generado por Gestión Clubes

</div>

<script>

window.onload=function(){

    @if(!request()->is('jugadores/pdf'))

        window.print();

    @endif

}

</script>

</body>
</html>
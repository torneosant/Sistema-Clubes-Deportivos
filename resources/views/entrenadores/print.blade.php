<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Entrenadores</title>

    <style>

        body{
            font-family: Arial;
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        th,td{
            border:1px solid #000;
            padding:6px;
        }

        th{
            background:#eee;
        }

    </style>

</head>

<body>

<h2>Listado de Entrenadores</h2>

<table>

<thead>

<tr>

    <th>#</th>
    <th>Nombre</th>
    <th>Documento</th>
    <th>Cargo</th>
    <th>Teléfono</th>

</tr>

</thead>

<tbody>

@foreach($entrenadores as $entrenador)

<tr>

    <td>{{ $loop->iteration }}</td>

    <td>
        {{ $entrenador->nombres }}
        {{ $entrenador->apellidos }}
    </td>

    <td>{{ $entrenador->numero_documento }}</td>

    <td>{{ $entrenador->cargo }}</td>

    <td>{{ $entrenador->telefono }}</td>

</tr>

@endforeach

</tbody>

</table>

<script>

window.onload=function(){

    window.print();

}

</script>

</body>

</html>
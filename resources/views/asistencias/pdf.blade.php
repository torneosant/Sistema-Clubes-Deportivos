<!DOCTYPE html>
<html lang="es">
<head>

<meta charset="UTF-8">

<style>

body{
    font-family: DejaVu Sans, sans-serif;
    font-size:12px;
}

table{
    width:100%;
    border-collapse:collapse;
}

th,td{
    border:1px solid #999;
    padding:6px;
}

th{
    background:#1e40af;
    color:white;
    font-weight:bold;
}

tbody tr:nth-child(even){
    background:#f5f5f5;
}

h2,h4{
    text-align:center;
    margin:3px;
}

</style>

</head>

<body>

<table width="100%" style="border:0; margin-bottom:15px;">

<tr>

<td width="18%" style="border:0; text-align:center;">

{{-- Aquí luego pondremos el logo --}}

<strong>LOGO</strong>

</td>

<td width="82%" style="border:0; text-align:center;">

<h2 style="margin:0;">
{{ $entrenamiento->equipo->club->nombre ?? 'CLUB DEPORTIVO' }}
</h2>

<h3 style="margin:5px 0;">

REPORTE DE ASISTENCIA
</h3>

</td>

</tr>

</table>

<h4>REPORTE DE ASISTENCIA</h4>

<hr>

<p>

<b>Equipo:</b>
{{ $entrenamiento->equipo->nombre }}

<br>

<b>Entrenador:</b>
{{ $entrenamiento->entrenador->nombres }}
{{ $entrenamiento->entrenador->apellidos }}

<br>

<b>Fecha:</b>
{{ $entrenamiento->fecha }}

<br>

<b>Hora:</b>
{{ $entrenamiento->hora_inicio }}

</p>

<table>

<thead>

<tr>

<th>#</th>

<th>Jugador</th>

<th>Categoría</th>

<th>Asistencia</th>

<th>Observación</th>

</tr>

</thead>

<tbody>

@foreach($jugadores as $i=>$jugador)

<tr>

<td>{{ $i+1 }}</td>

<td>

{{ $jugador->apellidos }}

{{ $jugador->nombres }}

</td>

<td>

{{ $jugador->categoria->nombre }}

</td>

<td>

{{ $asistencias[$jugador->id]->estado ?? 'Presente' }}

</td>

<td>

{{ $asistencias[$jugador->id]->observacion ?? '' }}

</td>

</tr>

@endforeach

</tbody>

</table>

<br><br>

<table style="width:45%; border-collapse:collapse;">

<tr>
    <td><b>Total jugadores</b></td>
    <td>{{ $totalJugadores }}</td>
</tr>

<tr>
    <td><b>Presentes</b></td>
    <td>{{ $presentes }}</td>
</tr>

<tr>
    <td><b>Ausentes</b></td>
    <td>{{ $ausentes }}</td>
</tr>

<tr>
    <td><b>Permisos</b></td>
    <td>{{ $permisos }}</td>
</tr>

<tr>
    <td><b>Incapacidades</b></td>
    <td>{{ $incapacidades }}</td>
</tr>

<tr>
    <td><b>% Asistencia</b></td>
    <td>{{ $porcentaje }}%</td>
</tr>

</table>

<br><br><br>

<div style="width:300px;text-align:center">

____________________________________

<br>

Firma del entrenador

</div>

<br><br>

<div style="font-size:10px;color:#666;text-align:center">

Generado el {{ now()->format('d/m/Y H:i') }}

</div>

</body>
</html>
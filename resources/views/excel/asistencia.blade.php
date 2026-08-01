<table>

    <tr>
        <td colspan="4"><strong>REPORTE DE ASISTENCIA</strong></td>
    </tr>

    <tr>
        <td><strong>Fecha</strong></td>
        <td>{{ $entrenamiento->fecha }}</td>

        <td><strong>Hora</strong></td>
        <td>{{ $entrenamiento->hora_inicio }}</td>
    </tr>

    <tr>
        <td><strong>Equipo</strong></td>
        <td>{{ $entrenamiento->equipo->nombre }}</td>

        <td><strong>Entrenador</strong></td>
        <td>{{ $entrenamiento->entrenador->nombres ?? '' }} </td>
        <td>{{ $entrenamiento->entrenador->apellidos ?? '' }} </td>
    </tr>

</table>    

<br>

<table>

<thead>

<tr>

<th>Jugador</th>

<th>Categoría</th>

<th>Asistencia</th>

<th>Observación</th>

</tr>

</thead>

<tbody>

@foreach($jugadores as $jugador)

@php
$asistencia = \App\Models\Asistencia::where('entrenamiento_id',$entrenamiento->id)
    ->where('jugador_id',$jugador->id)
    ->first();
@endphp

<tr>

<td>{{ $jugador->apellidos }} {{ $jugador->nombres }}</td>

<td>{{ $jugador->categoria->nombre }}</td>

<td>{{ $asistencia->estado ?? 'Presente' }}</td>

<td>{{ $asistencia->observacion ?? '' }}</td>

</tr>

@endforeach

<tr></tr>

<tr>
    <td><strong>Presentes</strong></td>
    <td>{{ \App\Models\Asistencia::where('entrenamiento_id',$entrenamiento->id)->where('estado','Presente')->count() }}</td>
</tr>

<tr>
    <td><strong>Ausentes</strong></td>
    <td>{{ \App\Models\Asistencia::where('entrenamiento_id',$entrenamiento->id)->where('estado','Ausente')->count() }}</td>
</tr>

<tr>
    <td><strong>Permisos</strong></td>
    <td>{{ \App\Models\Asistencia::where('entrenamiento_id',$entrenamiento->id)->where('estado','Permiso')->count() }}</td>
</tr>

<tr>
    <td><strong>Incapacidades</strong></td>
    <td>{{ \App\Models\Asistencia::where('entrenamiento_id',$entrenamiento->id)->where('estado','Incapacidad')->count() }}</td>
</tr>

</tbody>

</table>
@extends('layouts.app')

@section('titulo','Tomar Asistencia')

@section('contenido')

<h2 class="text-2xl font-bold">
    ✅ Tomar Asistencia
</h2>

<p class="text-gray-500 mt-2">
    Entrenamiento del {{ $entrenamiento->fecha }}
</p>

<div class="bg-white rounded-lg shadow p-5 mt-4">

    <p><strong>Equipo:</strong> {{ $entrenamiento->equipo->nombre }}</p>

    <p class="mt-2">
        <strong>Categorías:</strong>

        @foreach($entrenamiento->categorias as $categoria)

            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm">
                {{ $categoria->nombre }}
            </span>

        @endforeach

    </p>

</div>

<div class="bg-white rounded-lg shadow mt-6">
    <form method="POST"
      action="{{ route('asistencias.store',$entrenamiento) }}">

@csrf

<div class="grid grid-cols-2 md:grid-cols-6 gap-4 mb-6">

    <div class="bg-white rounded-lg shadow p-4 text-center">
        <div class="text-2xl">👥</div>
        <div class="font-bold text-xl">{{ $totalJugadores }}</div>
        <div class="text-gray-500 text-sm">Jugadores</div>
    </div>

    <div class="bg-green-100 rounded-lg shadow p-4 text-center">
        <div class="text-2xl">🟢</div>
        <div class="font-bold text-xl">{{ $presentes }}</div>
        <div class="text-gray-600 text-sm">Presentes</div>
    </div>

    <div class="bg-red-100 rounded-lg shadow p-4 text-center">
        <div class="text-2xl">🔴</div>
        <div class="font-bold text-xl">{{ $ausentes }}</div>
        <div class="text-gray-600 text-sm">Ausentes</div>
    </div>

    <div class="bg-yellow-100 rounded-lg shadow p-4 text-center">
        <div class="text-2xl">🟡</div>
        <div class="font-bold text-xl">{{ $permisos }}</div>
        <div class="text-gray-600 text-sm">Permisos</div>
    </div>

    <div class="bg-purple-100 rounded-lg shadow p-4 text-center">
        <div class="text-2xl">🟣</div>
        <div class="font-bold text-xl">{{ $incapacidades }}</div>
        <div class="text-gray-600 text-sm">Incapacidades</div>
    </div>

    <div class="bg-blue-100 rounded-lg shadow p-4 text-center">
        <div class="text-2xl">📊</div>
        <div class="font-bold text-xl">{{ $porcentaje }}%</div>
        <div class="text-gray-600 text-sm">Asistencia</div>
    </div>

</div>

<table class="w-full border-collapse">

<thead class="bg-slate-100 border-b-2 border-slate-300">

<tr>

    <th class="px-5 py-4 text-left text-sm font-bold uppercase tracking-wide text-slate-700">
        Jugador
    </th>

    <th class="px-5 py-4 text-left text-sm font-bold uppercase tracking-wide text-slate-700">
        Categoría
    </th>

    <th class="px-5 py-4 text-center text-sm font-bold uppercase tracking-wide text-slate-700">
        Asistencia
    </th>

    <th class="px-5 py-4 text-center text-sm font-bold uppercase tracking-wide text-slate-700">
        Observación

    </th>

</tr>

</thead>

<tbody>

@foreach($jugadores as $jugador)

<tr class="border-t hover:bg-slate-50 transition">

<td class="px-5 py-4">

{{ $jugador->apellidos }}, {{ $jugador->nombres }}

</td>

<td class="px-5 py-4">

{{ $jugador->categoria->nombre }}

<td class="px-5 py-4">
    <div class="flex justify-center">

<select
    name="estado[{{ $jugador->id }}]"
    class="w-56 rounded-lg border border-slate-300 px-3 py-2 shadow-sm">

    <option value="Presente"
    {{ (isset($asistencias[$jugador->id]) && $asistencias[$jugador->id]->estado=='Presente') ? 'selected' : '' }}>
    🟢 Presente
</option>

<option value="Ausente"
    {{ (isset($asistencias[$jugador->id]) && $asistencias[$jugador->id]->estado=='Ausente') ? 'selected' : '' }}>
    🔴 Ausente
</option>

<option value="Permiso"
    {{ (isset($asistencias[$jugador->id]) && $asistencias[$jugador->id]->estado=='Permiso') ? 'selected' : '' }}>
    🟡 Permiso
</option>

<option value="Incapacidad"
    {{ (isset($asistencias[$jugador->id]) && $asistencias[$jugador->id]->estado=='Incapacidad') ? 'selected' : '' }}>
    🟣 Incapacidad
</option>

</select>

<td class="px-4 py-2">

    <input
        type="text"
        name="observacion[{{ $jugador->id }}]"
        value="{{ $asistencias[$jugador->id]->observacion ?? '' }}"
        class="w-full rounded-lg border border-slate-300 px-3 py-2"
        placeholder="Observación">

</td>

</td>

</tr>

@endforeach

</tbody>

</div>

</form>

</table>
<div class="mt-6 text-end">

<button
class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg">

💾 Guardar Asistencia

</button>

 <a href="{{ route('entrenamientos.index') }}"
       class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg">
        Cancelar
    </a>

    <div class="flex gap-2">

<a href="{{ route('asistencias.pdf', $entrenamiento) }}"
   target="_blank"
   class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
    📄 PDF
</a>

<a href="{{ route('asistencias.excel', $entrenamiento) }}"
   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
    📗 Excel
</a>

<a href="{{ route('asistencias.imprimir',$entrenamiento) }}"
target="_blank"
class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">

🖨 Imprimir

</a>

</div>



</div>
@endsection
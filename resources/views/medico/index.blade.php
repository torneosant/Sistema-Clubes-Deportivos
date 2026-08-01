@extends('layouts.app')

@section('titulo','Historial Médico')

@section('contenido')

<div>


    <div class="flex justify-between items-center mb-6">

    <div>
        <h1 class="text-3xl font-bold text-red-600">
            ❤️ Historial Médico
        </h1>
    </div>

<div class="grid grid-cols-4 gap-5 mb-6">

    <div class="bg-white rounded-xl shadow p-5">
        <p class="text-gray-500 text-sm">Registros</p>
        <h2 class="text-3xl font-bold text-red-600">
            {{ $historial->count() }}
        </h2>
    </div>

    <div class="bg-white rounded-xl shadow p-5">
        <p class="text-gray-500 text-sm">Lesiones activas</p>
        <h2 class="text-3xl font-bold text-red-600">
            {{ $historial->where('estado','Activo')->count() }}
        </h2>
    </div>

    <div class="bg-white rounded-xl shadow p-5">
        <p class="text-gray-500 text-sm">En recuperación</p>
        <h2 class="text-3xl font-bold text-yellow-600">
            {{ $historial->where('estado','En recuperación')->count() }}
        </h2>
    </div>

    <div class="bg-white rounded-xl shadow p-5">
        <p class="text-gray-500 text-sm">Altas médicas</p>
        <h2 class="text-3xl font-bold text-green-600">
            {{ $historial->where('estado','Alta médica')->count() }}
        </h2>
    </div>

</div>

<form method="GET" class="mb-6">

<div class="flex gap-3">

<input

type="text"
name="buscar"
value="{{ request('buscar') }}"
placeholder="Buscar jugador..."
class="border rounded-lg px-4 py-2 w-72">

<select
name="estado"
class="border rounded-lg px-4 py-2">

<option value="">Todos los estados</option>

<option value="Activo"
{{ request('estado')=='Activo'?'selected':'' }}>

Activo

</option>

<option value="En recuperación"
{{ request('estado')=='En recuperación'?'selected':'' }}>

En recuperación

</option>

<option value="Alta médica"
{{ request('estado')=='Alta médica'?'selected':'' }}>

Alta médica

</option>

</select>

<button class="bg-slate-700 text-white px-5 rounded-lg">

Buscar



</button>

<a href="{{ route('historial-medico.index') }}"
class="bg-gray-400 text-white px-5 py-2 rounded-lg">

Limpiar

</a>

</div>

</form>

    <a href="{{ route('historial-medico.create') }}"
       class="bg-red-600 hover:bg-red-700 text-white px-5 py-3 rounded-lg shadow">

        + Nuevo Registro

    </a>

</div>

    @if($jugador)

        @if($jugador)

<p class="text-slate-500">
    {{ $jugador->nombres }} {{ $jugador->apellidos }}
</p>

@else

<p class="text-slate-500">
    Todos los registros médicos del club
</p>

@endif

<form method="GET" action="{{ route('historial-medico.index') }}" class="mt-5">

    <input
        type="text"
        name="buscar"
        value="{{ request('buscar') }}"
        placeholder="Buscar jugador..."
        class="border rounded-lg px-4 py-2 w-80">

    <button class="bg-blue-600 text-white px-4 py-2 rounded-lg">
        Buscar
    </button>

</form>

        <div class="mt-3">

            <a href="{{ route('historial-medico.index') }}"
               class="bg-slate-700 hover:bg-slate-800 text-white px-4 py-2 rounded-lg">

                📋 Todos los registros

            </a>

        </div>

    @endif

</div>  

    @if($jugador)

<a href="{{ route('historial-medico.create') }}"
   class="bg-red-600 hover:bg-red-700 text-white px-5 py-3 rounded-lg shadow">

    ➕ Nuevo Registro

</a>

@endif

</div>

<div class="bg-white rounded-xl shadow overflow-hidden">

<table class="w-full">

<thead class="bg-slate-800 text-white">

<tr>

<th class="px-4 py-3">Fecha</th>

<th>Jugador</th>

<th>Tipo</th>

<th>Zona</th>

<th>Días</th>

<th>Estado</th>

<th>Acciones</th>

</tr>

</thead>

<tbody>

@forelse($historial as $item)

<tr class="border-b hover:bg-slate-50">

<td class="px-4 py-3">
{{ \Carbon\Carbon::parse($item->fecha)->format('d/m/Y') }}
</td>

<td>

    {{ $item->jugador->nombres }}

    {{ $item->jugador->apellidos }}

</td>

<td>{{ $item->tipo }}</td>

<td>{{ $item->zona }}</td>

<td>{{ $item->dias_incapacidad }}</td>

<td>

@if($item->estado=='Activo')

<span class="bg-red-100 text-red-700 px-3 py-1 rounded-full">

Activo

</span>

@elseif($item->estado=='En recuperación')

<span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full">

En recuperación

</span>

@else

<span class="bg-green-100 text-green-700 px-3 py-1 rounded-full">

Alta médica

</span>

@endif

</td>

<td class="flex gap-2 py-3">

<a href="{{ route('historial-medico.edit',$item) }}"
class="bg-blue-600 text-white px-3 py-1 rounded">

Editar

</a>

<a href="{{ route('jugadores.show',$item->jugador_id) }}"
class="bg-slate-600 text-white px-3 py-1 rounded">

Jugador

</a>

</td>   

</tr>

@empty

<tr>

<td colspan="6" class="text-center p-8 text-gray-500">

No existen registros médicos.

</td>

</tr>

@endforelse

</tbody>

</table>

</div>

@endsection
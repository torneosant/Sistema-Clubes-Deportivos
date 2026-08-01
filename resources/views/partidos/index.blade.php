@extends('layouts.app')

@section('titulo')
⚽ Partidos
@endsection

@section('contenido')

<div class="flex justify-between items-center mb-6">

    <div>
        <h1 class="text-3xl font-bold text-slate-800">⚽ Partidos</h1>
        <p class="text-slate-500">Administración de partidos</p>
    </div>

    <a href="{{ route('partidos.create') }}"
       class="bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded-lg shadow">
        ➕ Nuevo Partido
    </a>

</div>

<div class="bg-white rounded-xl shadow">

<table class="w-full">

<thead class="bg-slate-800 text-white">
<tr>
<th class="px-4 py-3">Fecha</th>
<th class="px-4 py-3">Hora</th>
<th class="px-4 py-3">Equipo</th>
<th class="px-4 py-3">Rival</th>
<th class="px-4 py-3">Categoría</th>
<th class="px-4 py-3">Competencia</th>
<th class="px-4 py-3">Condición</th>
<th class="px-4 py-3">Estado</th>
<th class="px-4 py-3 text-center">Acciones</th>
</tr>
</thead>

<tbody>

@forelse($partidos as $partido)

<tr class="border-b hover:bg-slate-50">

<td>{{ \Carbon\Carbon::parse($partido->fecha)->format('d/m/Y') }}</td>

<td>{{ \Carbon\Carbon::parse($partido->hora)->format('H:i') }}</td>

<td>{{ $partido->equipo->nombre }}</td>

<td>{{ $partido->rival }}</td>

<td>{{ $partido->categoria->nombre }}</td>

<td>{{ $partido->competencia }}</td>

<td>{{ $partido->condicion }}</td>

<td>{{ $partido->estado }}</td>

<td class="text-center space-x-2">

    <a href="{{ route('partidos.edit',$partido) }}"
       class="bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded">
        ✏️
    </a>

    @if($partido->estado != 'Jugado')

    <a href="{{ route('partidos.resultado',$partido) }}"
       class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded">
        ⚽ Registrar Resultado
    </a>

@else

    <a href="{{ route('partidos.resultado',$partido) }}"
       class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded">
        ✏️ Editar Resultado
    </a>

    <a href="{{ route('partidos.estadisticas',$partido) }}"
       class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded">
        📊 Estadísticas
    </a>

@endif

</td>
</tr>

@empty

<tr>

<td colspan="9" class="text-center p-6 text-gray-500">

No hay partidos registrados.

</td>

</tr>

@endforelse

</tbody>

</table>

</div>

@endsection
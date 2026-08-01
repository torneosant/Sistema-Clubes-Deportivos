@extends('layouts.app')

@section('titulo')
📂 Conceptos Contables
@endsection

@section('contenido')

<div class="flex justify-between items-center mb-6">

    <div>

        <h1 class="text-3xl font-bold text-slate-800">
            📂 Conceptos Contables
        </h1>

        <p class="text-slate-500">
            Administración de conceptos de ingresos y gastos
        </p>

    </div>

    <a href="{{ route('conceptos-contables.create') }}"
       class="bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded-lg shadow">

        ➕ Nuevo Concepto

    </a>

</div>

<div class="bg-white rounded-xl shadow">

<table class="w-full">

<thead class="bg-slate-800 text-white">

<tr>

<th class="p-3">Nombre</th>

<th>Tipo</th>

<th>Descripción</th>

<th>Estado</th>

<th class="text-center">Acciones</th>

</tr>

</thead>

<tbody>

@forelse($conceptos as $concepto)

<tr class="border-b hover:bg-slate-50">

<td class="p-3">

{{ $concepto->nombre }}

</td>

<td>

@if($concepto->tipo=='Ingreso')

<span class="bg-green-100 text-green-700 px-3 py-1 rounded-full">

💰 Ingreso

</span>

@else

<span class="bg-red-100 text-red-700 px-3 py-1 rounded-full">

💸 Gasto

</span>

@endif

</td>

<td>

{{ $concepto->descripcion }}

</td>

<td>

@if($concepto->activo)

<span class="bg-green-500 text-white px-3 py-1 rounded">

Activo

</span>

@else

<span class="bg-gray-500 text-white px-3 py-1 rounded">

Inactivo

</span>

@endif

</td>

<td class="text-center">

<a href="{{ route('conceptos-contables.edit',$concepto) }}"
class="bg-blue-600 text-white px-2 py-1 rounded">

✏️

</a>

</td>

</tr>

@empty

<tr>

<td colspan="5" class="text-center p-8 text-gray-500">

No existen conceptos registrados.

</td>

</tr>

@endforelse

</tbody>

</table>

</div>

@endsection
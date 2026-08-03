@extends('layouts.app')

@section('titulo','Inventario')

@section('contenido')

<x-page-header
title="📦 Inventario"
subtitle="Administra el inventario del club."/>




<div class="flex justify-end mb-5">

    <a href="{{ route('inventario.create') }}"
       class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl">

        ➕ Nuevo Artículo

    </a>

</div>

<x-card>

<table class="w-full">

    <thead>

        <tr class="border-b">

            <th class="text-left py-3">Artículo</th>
            <th>Tipo</th>
            <th>Stock</th>
            <th>Estado</th>
            <th>Ubicación</th>
            <th width="220">Acciones</th>

        </tr>

    </thead>

    <tbody>

    @forelse($articulos as $articulo)

        <tr class="border-b hover:bg-gray-50">

            <td>{{ $articulo->nombre }}</td>

            <td>{{ $articulo->tipoArticulo->nombre }}</td>

            <td>

<div>
    <strong>Total:</strong>
    {{ $articulo->cantidad }}
</div>

<div class="text-orange-600">
    <strong>Asignado:</strong>
    {{ $articulo->asignado }}
</div>

<div class="text-green-600">
    <strong>Disponible:</strong>
    {{ $articulo->disponible }}
</div>

</td>

            <td>{{ $articulo->estado }}</td>

            <td>{{ $articulo->ubicacion }}</td>
<td>

<div class="flex items-center gap-2 whitespace-nowrap">

    <a href="{{ route('inventario.edit',$articulo) }}"
       class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white rounded-lg">

        ✏

    </a>

    <x-button
    color="gray"
    onclick="window.location='{{ route('inventario.trazabilidad',$articulo) }}'">

    👁

</x-button>


    <form action="{{ route('inventario.destroy',$articulo) }}"
          method="POST">

        @csrf
        @method('DELETE')

        <button
            type="button"
            onclick="confirmarEliminar(this)"
            class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white rounded-lg">

            🗑

        </button>

    </form>

</div>

</td>

        </tr>

    @empty

        <tr>

            <td colspan="6" class="text-center py-6">

                No existen artículos registrados.

            </td>

        </tr>

    @endforelse

    </tbody>

</table>

</x-card>

@endsection
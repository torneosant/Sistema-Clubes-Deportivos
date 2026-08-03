@extends('layouts.app')

@section('titulo', 'Tipos de Documento')

@section('contenido')

<x-page-header
    title="📂 Tipos de Documento"
    subtitle="Administre las categorías para el Centro de Documentación.">

    <a href="{{ route('tipos-documento.create') }}"
       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl">
        + Nuevo Tipo
    </a>

</x-page-header>

<x-card>

<table class="w-full">

    <thead class="border-b">

        <tr class="text-left">

            <th class="py-3">Nombre</th>
            <th>Descripción</th>
            <th width="180">Acciones</th>

        </tr>

    </thead>

    <tbody>

    @forelse($tipos as $tipo)

        <tr class="border-b hover:bg-gray-50">

            <td class="py-3 font-medium">
                {{ $tipo->nombre }}
            </td>

            <td>
                {{ $tipo->descripcion }}
            </td>

            <td>

                <a href="{{ route('tipos-documento.edit',$tipo) }}"
                   class="text-blue-600 mr-3">
                    Editar
                </a>

                <form action="{{ route('tipos-documento.destroy',$tipo) }}"
                      method="POST"
                      class="inline">

                    @csrf
                    @method('DELETE')

                  <button
    type="button"
    onclick="confirmarEliminar(this)"
    class="text-red-600 hover:text-red-800 font-medium">
    🗑 Eliminar
</button>

                </form>

            </td>

        </tr>

    @empty

        <tr>

            <td colspan="3" class="text-center py-6 text-gray-500">
                No existen registros.
            </td>

        </tr>

    @endforelse

    </tbody>

</table>

</x-card>

@endsection
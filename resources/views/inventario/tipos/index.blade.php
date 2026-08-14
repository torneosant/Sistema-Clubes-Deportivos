@extends('layouts.app')

@section('titulo','Tipos de Artículos')

@section('contenido')

<x-page-header
    title="📦 Tipos de Artículos"
    subtitle="Administra los tipos de implementos del club."/>

<div class="flex justify-end mb-5">

    <a href="{{ route('tipos-articulo.create') }}"
       class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl">

        + Nuevo Tipo

    </a>

</div>

<x-card>

<table class="w-full">

    <thead>

        <tr class="border-b">

            <th class="text-left py-3">Nombre</th>

            <th width="180">Origen</th>

            <th width="150">Estado</th>

            <th width="220">Acciones</th>

        </tr>

    </thead>

    <tbody>

    @forelse($tipos as $tipo)

        <tr class="border-b hover:bg-gray-50">

            <td>

                {{ $tipo->nombre }}

            </td>

            <td>

                @if($tipo->club_id === null)

                    <span class="bg-slate-100 text-slate-700 px-3 py-1 rounded-full text-sm">

                        ⚙️ Sistema

                    </span>

                @else

                    <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm">

                        🏢 Mi club

                    </span>

                @endif

            </td>

            <td>

                @if($tipo->activo)

                    <span class="text-green-600 font-semibold">

                        Activo

                    </span>

                @else

                    <span class="text-red-600">

                        Inactivo

                    </span>

                @endif

            </td>

            <td>

                @if($tipo->club_id !== null)

                    <div class="flex items-center gap-2">

                        <a href="{{ route('tipos-articulo.edit',$tipo) }}"
                           class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-lg text-sm">

                            ✏️ Editar

                        </a>

                        <form method="POST"
                              action="{{ route('tipos-articulo.destroy',$tipo) }}"
                              onsubmit="return confirm('¿Está seguro de eliminar este tipo de artículo?');">

                            @csrf

                            @method('DELETE')

                            <button type="submit"
                                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-lg text-sm">

                                🗑️ Eliminar

                            </button>

                        </form>

                    </div>

                @else

                    <span class="text-gray-400 text-sm">

                        🔒 Tipo del sistema

                    </span>

                @endif

            </td>

        </tr>

    @empty

        <tr>

            <td colspan="4" class="text-center py-5">

                No existen registros.

            </td>

        </tr>

    @endforelse

    </tbody>

</table>

</x-card>

@endsection
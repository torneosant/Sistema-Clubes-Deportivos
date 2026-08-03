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

            <th width="150">Estado</th>

            <th width="180">Acciones</th>

        </tr>

    </thead>

    <tbody>

    @forelse($tipos as $tipo)

        <tr class="border-b hover:bg-gray-50">

            <td>{{ $tipo->nombre }}</td>

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

                <a href="{{ route('tipos-articulo.edit',$tipo) }}"
                   class="text-blue-600">

                    Editar

                </a>

            </td>

        </tr>

    @empty

        <tr>

            <td colspan="3" class="text-center py-5">

                No existen registros.

            </td>

        </tr>

    @endforelse

    </tbody>

</table>

</x-card>

@endsection
@extends('layouts.app')

@section('titulo','Módulos')

@section('contenido')

<x-page-header
    title="⚙️ Módulos del Sistema"
    subtitle="Administración de módulos" />

<x-card>

<div class="flex justify-end mb-4">

    <x-button
        color="blue"
        onclick="window.location='{{ route('modulos.create') }}'">

        ➕ Nuevo módulo

    </x-button>

</div>

<table class="w-full">

    <thead>

        <tr class="border-b">

            <th class="text-left">Nombre</th>

            <th class="text-left">Slug</th>

            <th>Activo</th>

            <th width="140">Acciones</th>

        </tr>

    </thead>

    <tbody>

    @forelse($modulos as $modulo)

        <tr class="border-b hover:bg-gray-50">

            <td>{{ $modulo->nombre }}</td>

            <td>{{ $modulo->slug }}</td>

            <td>

                {{ $modulo->activo ? '✅' : '❌' }}

            </td>

            <td>

                <div class="flex gap-2 justify-center">

                    <x-button
                        color="yellow"
                        icon
                        onclick="window.location='{{ route('modulos.edit',$modulo) }}'">

                        ✏️

                    </x-button>

                    <form action="{{ route('modulos.destroy',$modulo) }}"
                          method="POST">

                        @csrf
                        @method('DELETE')

                        <x-button
                            color="red"
                            icon
                            onclick="confirmarEliminar(this)">

                            🗑️

                        </x-button>

                    </form>

                </div>

            </td>

        </tr>

    @empty

        <tr>

            <td colspan="4" class="text-center py-5">

                No existen módulos.

            </td>

        </tr>

    @endforelse

    </tbody>

</table>

</x-card>

@endsection
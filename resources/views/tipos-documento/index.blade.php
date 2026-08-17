@extends('layouts.app')

@section('titulo', 'Tipos de Documento')

@section('contenido')


{{-- ENCABEZADO --}}

<x-page-header
    title="📂 Tipos de Documento"
    subtitle="Administra las categorías para el Centro de Documentación."
>


    <div class="flex items-center gap-2">

        <x-stat
            label="Total"
            :value="$tipos->count()"
            icon="📂"
            color="blue"
        />

    </div>

</x-page-header>


@if(session('success'))

    <div class="mb-6 rounded-lg bg-green-100 border border-green-300 text-green-700 px-4 py-3">

        {{ session('success') }}

    </div>

@endif


{{-- ACCIONES --}}

<x-actions>

    <a href="{{ route('tipos-documento.create') }}">

        <x-button color="blue">

            ➕ Nuevo Tipo

        </x-button>

    </a>

</x-actions>


{{-- TABLA --}}

<x-table>

    <x-table-header>

        <x-table-header-cell>
            Nombre
        </x-table-header-cell>

        <x-table-header-cell>
            Descripción
        </x-table-header-cell>

        <x-table-header-cell align="center">
            Acciones
        </x-table-header-cell>

    </x-table-header>


    <tbody>

    @forelse($tipos as $tipo)

        <x-table-row>


            {{-- NOMBRE --}}

            <x-table-cell>

                <span class="font-semibold">

                    {{ $tipo->nombre }}

                </span>

            </x-table-cell>


            {{-- DESCRIPCIÓN --}}

            <x-table-cell>

                {{ $tipo->descripcion ?? '-' }}

            </x-table-cell>


            {{-- ACCIONES --}}

            <x-table-cell align="center">

                <div class="flex justify-center items-center gap-2">


                    {{-- EDITAR --}}

                    <a href="{{ route('tipos-documento.edit', $tipo) }}">

                        <x-button
                            color="yellow"
                            icon
                            title="Editar tipo de documento"
                        >

                            ✏️

                        </x-button>

                    </a>


                    {{-- ELIMINAR --}}

                    <form
                        action="{{ route('tipos-documento.destroy', $tipo) }}"
                        method="POST"
                        class="inline"
                    >

                        @csrf
                        @method('DELETE')

                        <x-button
                            type="button"
                            color="red"
                            icon
                            title="Eliminar tipo de documento"
                            onclick="confirmarEliminar(this)"
                        >

                            🗑️

                        </x-button>

                    </form>


                </div>

            </x-table-cell>


        </x-table-row>

    @empty

        <tr>

            <td
                colspan="3"
                class="px-4 py-10 text-center text-gray-500"
            >

                No existen registros.

            </td>

        </tr>

    @endforelse

    </tbody>

</x-table>


@endsection
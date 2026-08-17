@extends('layouts.app')

@section('titulo', 'Inventario')

@section('contenido')


{{-- ENCABEZADO --}}

<x-page-header
    title="📦 Inventario"
    subtitle="Administra el inventario del club."
/>


{{-- ACCIONES --}}

<x-actions>

    <a href="{{ route('inventario.excel') }}">

        <x-button color="green">

            📤 Excel

        </x-button>

    </a>


    <a href="#">

        <x-button color="red">

            📄 PDF

        </x-button>

    </a>


    <a href="{{ route('inventario.create') }}">

        <x-button color="blue">

            ➕ Nuevo Artículo

        </x-button>

    </a>

</x-actions>


{{-- TABLA --}}

<x-table>

    <x-table-header>

        <x-table-header-cell>
            Artículo
        </x-table-header-cell>

        <x-table-header-cell>
            Tipo
        </x-table-header-cell>

        <x-table-header-cell align="center">
            Stock
        </x-table-header-cell>

        <x-table-header-cell>
            Estado
        </x-table-header-cell>

        <x-table-header-cell>
            Ubicación
        </x-table-header-cell>

        <x-table-header-cell align="center">
            Acciones
        </x-table-header-cell>

    </x-table-header>


    <tbody>

    @forelse($articulos as $articulo)

        <x-table-row>


            {{-- ARTÍCULO --}}

            <x-table-cell>

                <span class="font-semibold">

                    {{ $articulo->nombre }}

                </span>

            </x-table-cell>


            {{-- TIPO --}}

            <x-table-cell>

                {{ $articulo->tipoArticulo->nombre ?? '-' }}

            </x-table-cell>


            {{-- STOCK --}}

            <x-table-cell align="center">

                <div class="flex justify-center items-center gap-2">

                    <span class="px-3 py-1 bg-gray-100 rounded-full text-sm font-semibold">

                        📦 {{ $articulo->cantidad }}

                    </span>

                    <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm font-semibold">

                        🔴 {{ $articulo->asignado }}

                    </span>

                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-semibold">

                        🟢 {{ $articulo->disponible }}

                    </span>

                </div>

                <div class="flex justify-center gap-6 text-xs text-gray-500 mt-1">

                    <span>Total</span>

                    <span>Asignados</span>

                    <span>Disponibles</span>

                </div>

            </x-table-cell>


            {{-- ESTADO --}}

            <x-table-cell>

                {{ $articulo->estado ?? '-' }}

            </x-table-cell>


            {{-- UBICACIÓN --}}

            <x-table-cell>

                {{ $articulo->ubicacion ?? '-' }}

            </x-table-cell>


            {{-- ACCIONES --}}

            <x-table-cell align="center">

                <div class="flex justify-center items-center gap-2">


                    {{-- EDITAR --}}

                    <a href="{{ route('inventario.edit', $articulo) }}">

                        <x-button
                            color="yellow"
                            icon
                            title="Editar artículo"
                        >

                            ✏️

                        </x-button>

                    </a>


                    {{-- TRAZABILIDAD --}}

                    <x-button
                        color="gray"
                        icon
                        title="Ver trazabilidad"
                        onclick="window.location='{{ route('inventario.trazabilidad', $articulo) }}'"
                    >

                        👁️

                    </x-button>


                    {{-- ELIMINAR --}}

                    <form
                        action="{{ route('inventario.destroy', $articulo) }}"
                        method="POST"
                        class="inline"
                    >

                        @csrf
                        @method('DELETE')

                        <x-button
                            type="button"
                            color="red"
                            icon
                            title="Eliminar artículo"
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
                colspan="6"
                class="px-4 py-10 text-center text-gray-500"
            >

                No existen artículos registrados.

            </td>

        </tr>

    @endforelse

    </tbody>

</x-table>


@endsection
@extends('layouts.app')

@section('titulo', 'Centro de Documentación')

@section('contenido')

{{-- ENCABEZADO --}}

<x-page-header
title="📚 Centro de Documentación"
subtitle="Documentos oficiales del club"

>


<div class="flex items-center gap-2">

    <x-stat
        label="Documentos"
        :value="$documentos->count()"
        icon="📚"
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


<a href="{{ route('documentos.create') }}">

    <x-button color="blue">

        ➕ Nuevo Documento

    </x-button>

</a>

</x-actions>

{{-- BÚSQUEDA --}}

<x-filter
:action="route('documentos.index')"

>


<x-input
    name="buscar"
    id="buscarDocumento"
    placeholder="🔍 Buscar documentos..."
/>

</x-filter>

{{-- TABLA --}}

<x-table>

<x-table-header>

    <x-table-header-cell>
        Título
    </x-table-header-cell>

    <x-table-header-cell>
        Tipo
    </x-table-header-cell>

    <x-table-header-cell>
        Archivo
    </x-table-header-cell>

    <x-table-header-cell>
        Fecha
    </x-table-header-cell>

    <x-table-header-cell align="center">
        Acciones
    </x-table-header-cell>

</x-table-header>


<tbody>

@forelse($documentos as $doc)

    <x-table-row>


        {{-- TÍTULO --}}

        <x-table-cell>

            <span class="font-semibold">

                {{ $doc->titulo }}

            </span>

        </x-table-cell>


        {{-- TIPO --}}

        <x-table-cell>

            {{ $doc->tipoDocumento->nombre ?? '-' }}

        </x-table-cell>


        {{-- ARCHIVO --}}

        <x-table-cell>

            <span class="inline-flex items-center gap-2">

                📄

                {{ basename($doc->archivo) }}

            </span>

        </x-table-cell>


        {{-- FECHA --}}

        <x-table-cell>

            {{ $doc->fecha }}

        </x-table-cell>


        {{-- ACCIONES --}}

        <x-table-cell align="center">

            <div class="flex justify-center items-center gap-2">


                {{-- VER --}}

                <a
                    href="{{ asset('storage/'.$doc->archivo) }}"
                    target="_blank"
                >

                    <x-button
                        color="green"
                        icon
                        title="Ver documento"
                    >

                        👁️

                    </x-button>

                </a>


                {{-- DESCARGAR --}}

                <a
                    href="{{ asset('storage/'.$doc->archivo) }}"
                    download
                >

                    <x-button
                        color="blue"
                        icon
                        title="Descargar documento"
                    >

                        ⬇️

                    </x-button>

                </a>


                {{-- ELIMINAR --}}

                <form
                    action="{{ route('documentos.destroy', $doc) }}"
                    method="POST"
                    class="inline"
                >

                    @csrf
                    @method('DELETE')

                    <x-button
                        type="button"
                        color="red"
                        icon
                        title="Eliminar documento"
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
            colspan="5"
            class="px-4 py-10 text-center text-gray-500"
        >

            No existen documentos.

        </td>

    </tr>

@endforelse

</tbody>


</x-table>

@endsection

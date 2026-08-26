@extends('layouts.app')

@section('titulo', 'Nuevo Documento')

@section('contenido')

<x-page-header
    title="📄 Nuevo Documento"
    subtitle="Registrar un nuevo documento del club."
/>

@if ($errors->any())

    <x-card class="mb-6">

        <div class="rounded-lg bg-red-50 border border-red-200 p-4">

            <div class="flex items-center gap-2 mb-2">

                <span class="text-red-600 text-xl">
                    ⚠️
                </span>

                <h3 class="font-semibold text-red-800">
                    No se pudo guardar el documento
                </h3>

            </div>

            <ul class="list-disc list-inside text-sm text-red-700 space-y-1">

                @foreach ($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    </x-card>

@endif


<x-card>

    <form
        action="{{ route('documentos.store') }}"
        method="POST"
        enctype="multipart/form-data"
        class="space-y-6"
    >

        @csrf


        {{-- ============================== --}}
        {{-- DATOS DEL DOCUMENTO --}}
        {{-- ============================== --}}

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


            {{-- TIPO --}}

            <div>

                <label
                    for="tipo_documento_club_id"
                    class="block text-sm font-medium text-gray-700 mb-1"
                >
                    Tipo de documento *
                </label>

                <select
                    id="tipo_documento_club_id"
                    name="tipo_documento_club_id"
                    required
                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                >

                    <option value="">
                        Seleccione...
                    </option>

                    @foreach ($tipos as $tipo)

                        <option
                            value="{{ $tipo->id }}"
                            {{ old('tipo_documento_club_id') == $tipo->id ? 'selected' : '' }}
                        >
                            {{ $tipo->nombre }}
                        </option>

                    @endforeach

                </select>

            </div>


            {{-- FECHA --}}

            <div>

                <label
                    for="fecha"
                    class="block text-sm font-medium text-gray-700 mb-1"
                >
                    Fecha
                </label>

                <input
                    type="date"
                    id="fecha"
                    name="fecha"
                    value="{{ old('fecha', $anio . '-01-01') }}"
                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                >

            </div>

        </div>


        {{-- ============================== --}}
        {{-- TÍTULO --}}
        {{-- ============================== --}}

        <div>

            <label
                for="titulo"
                class="block text-sm font-medium text-gray-700 mb-1"
            >
                Título *
            </label>

            <input
                type="text"
                id="titulo"
                name="titulo"
                value="{{ old('titulo') }}"
                required
                maxlength="255"
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                placeholder="Ej. Reglamento del club"
            >

        </div>


        {{-- ============================== --}}
        {{-- DESCRIPCIÓN --}}
        {{-- ============================== --}}

        <div>

            <label
                for="descripcion"
                class="block text-sm font-medium text-gray-700 mb-1"
            >
                Descripción
            </label>

            <textarea
                id="descripcion"
                name="descripcion"
                rows="4"
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                placeholder="Descripción o información adicional del documento..."
            >{{ old('descripcion') }}</textarea>

        </div>


        {{-- ============================== --}}
        {{-- ARCHIVO --}}
        {{-- ============================== --}}

        <div>

            <label
                for="archivo"
                class="block text-sm font-medium text-gray-700 mb-1"
            >
                Archivo PDF *
            </label>

            <input
                type="file"
                id="archivo"
                name="archivo"
                accept=".pdf,application/pdf"
                required
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
            >

            <p class="mt-1 text-xs text-gray-500">
                Solo archivos PDF. Tamaño máximo: 5 MB.
            </p>

        </div>


        {{-- ============================== --}}
        {{-- BOTONES --}}
        {{-- ============================== --}}

        <div class="flex items-center justify-end gap-3 pt-4 border-t">

            <a
                href="{{ route('documentos.index') }}"
                class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium px-5 py-2.5 rounded-lg"
            >
                Cancelar
            </a>

            <button
                type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2.5 rounded-lg shadow"
            >
                💾 Guardar documento
            </button>

        </div>

    </form>

</x-card>

@endsection
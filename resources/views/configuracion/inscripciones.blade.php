@extends('layouts.app')

@section('titulo', 'Configuración de Inscripciones')

@section('contenido')

<x-page-header
    title="📝 Configuración de Inscripciones"
    subtitle="Configura el comportamiento de las inscripciones aprobadas."
/>


@if(session('success'))

    <div class="mb-6 rounded-lg bg-green-100 border border-green-300 text-green-700 px-4 py-3">
        {{ session('success') }}
    </div>

@endif


@if($errors->any())

    <x-card class="mb-6">

        <div class="rounded-lg bg-red-50 border border-red-200 p-4">

            <h3 class="font-semibold text-red-800 mb-2">
                ⚠️ No se pudo guardar
            </h3>

            <ul class="list-disc list-inside text-sm text-red-700">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    </x-card>

@endif


<form
    action="{{ route('configuracion.updateInscripciones') }}"
    method="POST"
>

    @csrf
    @method('PUT')


    {{-- CORREO --}}

    <x-card class="mb-6">

        <h2 class="text-lg font-bold">
            📧 Correo al aprobar
        </h2>

        <p class="text-sm text-gray-500 mb-5">
            Configura el envío automático al aprobar una inscripción.
        </p>


        <label class="flex items-center gap-3">

            <input
                type="checkbox"
                name="enviar_correo"
                value="1"
                class="rounded border-gray-300"
                {{ $configuracion->enviar_correo ? 'checked' : '' }}
            >

            <span class="font-medium">
                Enviar correo automáticamente al aprobar una inscripción
            </span>

        </label>

    </x-card>


    {{-- MENSAJE --}}

    <x-card class="mb-6">

        <h2 class="text-lg font-bold mb-5">
            ✉️ Mensaje de bienvenida
        </h2>


        <div class="mb-5">

            <label class="block text-sm font-medium mb-1">
                Asunto del correo
            </label>

            <input
                type="text"
                name="asunto_correo"
                value="{{ old('asunto_correo', $configuracion->asunto_correo) }}"
                class="w-full rounded-lg border-gray-300"
                required
            >

        </div>


        <div>

            <label class="block text-sm font-medium mb-1">
                Mensaje
            </label>

            <textarea
                name="mensaje_correo"
                rows="5"
                class="w-full rounded-lg border-gray-300"
            >{{ old('mensaje_correo', $configuracion->mensaje_correo) }}</textarea>

        </div>

    </x-card>


    {{-- DOCUMENTOS --}}

    <x-card class="mb-6">

        <h2 class="text-lg font-bold">
            📎 Documentos para nuevos jugadores
        </h2>

        <p class="text-sm text-gray-500 mb-5">
            Selecciona los documentos del Centro de Documentación
            que se enviarán al aprobar una inscripción.
        </p>


        <label class="flex items-center gap-3 mb-6">

            <input
                type="checkbox"
                name="adjuntar_documentos"
                value="1"
                class="rounded border-gray-300"
                {{ $configuracion->adjuntar_documentos ? 'checked' : '' }}
            >

            <span class="font-medium">
                Adjuntar documentos al correo
            </span>

        </label>


        @if($documentos->count())

            <div class="space-y-3">

                @foreach($documentos as $documento)

                    <label class="flex items-start gap-3 p-4 border rounded-lg hover:bg-gray-50 cursor-pointer">

                        <input
                            type="checkbox"
                            name="documentos[]"
                            value="{{ $documento->id }}"
                            class="mt-1 rounded border-gray-300"
                            {{ in_array($documento->id, $documentosSeleccionados) ? 'checked' : '' }}
                        >

                        <div>

                            <div class="font-semibold">
                                📄 {{ $documento->titulo }}
                            </div>

                            @if($documento->descripcion)

                                <div class="text-sm text-gray-500 mt-1">
                                    {{ $documento->descripcion }}
                                </div>

                            @endif

                        </div>

                    </label>

                @endforeach

            </div>

        @else

            <div class="rounded-lg bg-gray-50 border p-6 text-center">

                <div class="text-3xl mb-2">
                    📄
                </div>

                <p class="font-medium">
                    No hay documentos disponibles.
                </p>

                <p class="text-sm text-gray-500 mt-1">
                    Primero carga documentos en el Centro de Documentación.
                </p>

                <a
                    href="{{ route('documentos.index') }}"
                    class="inline-block mt-4 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg"
                >
                    📚 Centro de Documentación
                </a>

            </div>

        @endif

    </x-card>


    {{-- GUARDAR --}}

    <div class="flex justify-end">

        <button
            type="submit"
            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg shadow"
        >
            💾 Guardar configuración
        </button>

    </div>

</form>

@endsection
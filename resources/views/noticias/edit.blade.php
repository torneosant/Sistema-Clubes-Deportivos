@extends('layouts.app')

@section('titulo', 'Editar noticia')

@section('contenido')

<x-page-header
    title="✏️ Editar noticia"
    subtitle="Modifica la información de la noticia."
/>

<x-card>

    <form
        action="{{ route('noticias.update', $noticia) }}"
        method="POST"
        enctype="multipart/form-data"
    >

        @csrf
        @method('PUT')

        {{-- Título --}}
        <div class="mb-5">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Título
            </label>

            <input
                type="text"
                name="titulo"
                value="{{ old('titulo', $noticia->titulo) }}"
                class="w-full border-gray-300 rounded-lg"
                required
            >
        </div>

        {{-- Contenido --}}
        <div class="mb-5">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Contenido
            </label>

            <textarea
                name="contenido"
                rows="7"
                class="w-full border-gray-300 rounded-lg"
                required
            >{{ old('contenido', $noticia->contenido) }}</textarea>
        </div>

        {{-- Fecha --}}
        <div class="mb-5">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Fecha de publicación
            </label>

            <input
                type="date"
                name="fecha_publicacion"
                value="{{ old('fecha_publicacion', $noticia->fecha_publicacion ? \Carbon\Carbon::parse($noticia->fecha_publicacion)->format('Y-m-d') : '') }}"
                class="border-gray-300 rounded-lg"
            >
        </div>

        {{-- Estado --}}
        <div class="mb-5">

            <label class="flex items-center gap-2">

                <input
                    type="checkbox"
                    name="publicada"
                    value="1"
                    {{ old('publicada', $noticia->publicada) ? 'checked' : '' }}
                >

                <span class="text-sm text-gray-700">
                    Noticia publicada
                </span>

            </label>

        </div>

        {{-- Imagen actual --}}
        @if($noticia->imagen)

            <div class="mb-5">

                <p class="text-sm font-medium text-gray-700 mb-2">
                    Imagen actual
                </p>

                <img
                    src="{{ asset('storage/' . $noticia->imagen) }}"
                    class="w-64 h-40 object-cover rounded-lg"
                >

            </div>

        @endif

        {{-- Nueva imagen --}}
        <div class="mb-6">

            <label class="block text-sm font-medium text-gray-700 mb-1">
                Cambiar imagen
            </label>

            <input
                type="file"
                name="imagen"
                accept=".jpg,.jpeg,.png,.webp"
                class="w-full border border-gray-300 rounded-lg p-2"
            >

            <p class="text-xs text-gray-500 mt-1">
                Opcional. JPG, PNG o WEBP. Máximo 2 MB.
            </p>

        </div>

        {{-- Botones --}}
        <div class="flex gap-3">

            <button
                type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg"
            >
                💾 Guardar cambios
            </button>

            <a
                href="{{ route('noticias.index') }}"
                class="bg-gray-600 hover:bg-gray-700 text-white px-5 py-2 rounded-lg"
            >
                Cancelar
            </a>

        </div>

    </form>

</x-card>

@endsection
@extends('layouts.app')

@section('titulo', 'Nueva Noticia')

@section('contenido')

<x-page-header
    title="📢 Nueva noticia"
    subtitle="Publica una noticia para los usuarios del club."
/>

<x-card>

    <form method="POST"
          action="{{ route('noticias.store') }}"
          enctype="multipart/form-data">

        @csrf

        <div class="space-y-5">

            <div>
                <label class="block text-sm font-medium mb-1">
                    Título
                </label>

                <input
                    type="text"
                    name="titulo"
                    value="{{ old('titulo') }}"
                    required
                    class="w-full border rounded-lg p-3">

                @error('titulo')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">
                    Contenido
                </label>

                <textarea
                    name="contenido"
                    rows="8"
                    required
                    class="w-full border rounded-lg p-3">{{ old('contenido') }}</textarea>

                @error('contenido')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">
                    Imagen
                </label>

                <input
                    type="file"
                    name="imagen"
                    accept=".jpg,.jpeg,.png,.webp"
                    class="w-full border rounded-lg p-3">

                <p class="text-xs text-gray-500 mt-1">
                    Opcional. Máximo 2 MB.
                </p>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">
                    Fecha de publicación
                </label>

                <input
                    type="date"
                    name="fecha_publicacion"
                    value="{{ old('fecha_publicacion', now()->format('Y-m-d')) }}"
                    class="border rounded-lg p-3">
            </div>

        </div>

        <div class="mt-8 flex justify-end gap-3">

            <a href="{{ route('noticias.index') }}"
               class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg">

                Cancelar

            </a>

            <button
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">

                📢 Publicar noticia

            </button>

        </div>

    </form>

</x-card>

@endsection
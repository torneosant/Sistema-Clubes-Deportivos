@extends('layouts.app')

@section('titulo', 'Ver noticia')

@section('contenido')

<x-page-header
    title="📢 {{ $noticia->titulo }}"
    subtitle="Detalle de la noticia"
/>

<x-card>

    {{-- Imagen --}}
    @if($noticia->imagen)
        <div class="mb-6">
            <img
                src="{{ asset('storage/' . $noticia->imagen) }}"
                class="w-full max-h-96 object-cover rounded-lg"
            >
        </div>
    @endif

    {{-- Fecha y estado --}}
    <div class="mb-6 text-sm text-gray-500">

        {{ $noticia->fecha_publicacion
            ? \Carbon\Carbon::parse($noticia->fecha_publicacion)->format('d/m/Y')
            : ''
        }}

        @if($noticia->publicada)
            · <span class="text-green-600 font-semibold">
                Publicada
            </span>
        @else
            · <span class="text-gray-500 font-semibold">
                Borrador
            </span>
        @endif

    </div>

    {{-- Contenido --}}
    <div class="text-gray-700 leading-relaxed whitespace-pre-line">
        {{ $noticia->contenido }}
    </div>

    {{-- Botones --}}
    <div class="mt-8 flex gap-3">

        <a
            href="{{ route('noticias.index') }}"
            class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg"
        >
            ← Volver
        </a>

        <a
            href="{{ route('noticias.edit', $noticia) }}"
            class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg"
        >
            ✏️ Editar
        </a>

    </div>

</x-card>

@endsection
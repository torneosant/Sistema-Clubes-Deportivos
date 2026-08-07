@extends('layouts.app')

@section('titulo', 'Noticias')

@section('contenido')

<x-page-header
    title="📢 Noticias"
    subtitle="Noticias y comunicados del club."
/>

<div class="mb-6 flex justify-end">

    <a href="{{ route('noticias.create') }}"
       class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg shadow">

        ➕ Nueva noticia

    </a>

</div>

@if(session('success'))

    <div class="mb-6 bg-green-100 text-green-800 px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>

@endif

<div class="space-y-5">

    @forelse($noticias as $noticia)

        <x-card>

            <div class="flex flex-col md:flex-row gap-5">

                @if($noticia->imagen)

                    <img
                        src="{{ asset('storage/' . $noticia->imagen) }}"
                        class="w-full md:w-48 h-32 object-cover rounded-lg"
                    >

                @endif

                <div class="flex-1">

                    <div class="flex items-start justify-between gap-4">

                        <div>

                            <h2 class="text-xl font-bold text-slate-800">
                                {{ $noticia->titulo }}
                            </h2>

                            <p class="text-sm text-gray-500 mt-1">

                                {{ optional($noticia->fecha_publicacion)->format('d/m/Y') }}

                                @if($noticia->publicada)
                                    · <span class="text-green-600">Publicada</span>
                                @else
                                    · <span class="text-gray-500">Borrador</span>
                                @endif

                            </p>

                        </div>

                    </div>

                    <p class="text-gray-600 mt-4">
                        {{ Str::limit($noticia->contenido, 180) }}
                    </p>

                    <div class="mt-5 flex gap-2">

                        <a href="{{ route('noticias.show', $noticia) }}"
                           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">

                            Ver

                        </a>

                        <a href="{{ route('noticias.edit', $noticia) }}"
                           class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg">

                            Editar

                        </a>

                     <form
    action="{{ route('noticias.destroy', $noticia) }}"
    method="POST"
    class="inline formulario-eliminar"
    data-titulo="{{ $noticia->titulo }}"
>
    @csrf
    @method('DELETE')

    <button
        type="button"
        onclick="abrirModalEliminar(this)"
        class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg shadow-sm transition"
    >
        🗑️ Eliminar
    </button>
</form>

                    </div>

                </div>

            </div>

        </x-card>

    @empty

        <x-card>

            <div class="text-center py-10 text-gray-500">

                <div class="text-5xl mb-4">📢</div>

                <p>No hay noticias publicadas.</p>

            </div>

        </x-card>

    @endforelse

</div>

{{-- Modal confirmar eliminación --}}
<div
    id="modalEliminar"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 px-4"
>
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">

        <div class="flex items-center gap-4 mb-5">

            <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center text-2xl">
                🗑️
            </div>

            <div>
                <h3 class="text-lg font-bold text-slate-800">
                    Eliminar noticia
                </h3>

                <p class="text-sm text-gray-500">
                    Esta acción no se puede deshacer.
                </p>
            </div>

        </div>

        <p class="text-gray-600 mb-6">
            ¿Seguro que deseas eliminar la noticia
            <strong id="tituloNoticiaEliminar"></strong>?
        </p>

        <div class="flex justify-end gap-3">

            <button
                type="button"
                onclick="cerrarModalEliminar()"
                class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-gray-700"
            >
                Cancelar
            </button>

            <button
                type="button"
                onclick="ejecutarEliminar()"
                class="px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white"
            >
                🗑️ Sí, eliminar
            </button>

        </div>

    </div>
</div>

<script>

let formularioEliminar = null;

function abrirModalEliminar(boton)
{
    formularioEliminar = boton.closest('form');

    const titulo = formularioEliminar.dataset.titulo;

    document.getElementById('tituloNoticiaEliminar').textContent = titulo;

    const modal = document.getElementById('modalEliminar');

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function cerrarModalEliminar()
{
    const modal = document.getElementById('modalEliminar');

    modal.classList.add('hidden');
    modal.classList.remove('flex');

    formularioEliminar = null;
}

function ejecutarEliminar()
{
    if (!formularioEliminar) {
        return;
    }

    // Guardamos el formulario antes de cerrar
    const formulario = formularioEliminar;

    formularioEliminar = null;

    formulario.submit();
}

</script>


@endsection
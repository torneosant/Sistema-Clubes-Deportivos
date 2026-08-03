@extends('layouts.app')

@section('titulo', $modo == 'crear' ? 'Nuevo Tipo de Artículo' : 'Editar Tipo de Artículo')

@section('contenido')

<x-page-header
    :title="$modo == 'crear' ? '📦 Nuevo Tipo de Artículo' : '✏ Editar Tipo de Artículo'"
    subtitle="Administra los tipos de implementos del club."/>

<x-card>

<form
    method="POST"
    action="{{ $modo == 'crear'
        ? route('tipos-articulo.store')
        : route('tipos-articulo.update', $tipo) }}">

    @csrf

    @if($modo=='editar')
        @method('PUT')
    @endif

    <div class="mb-5">

        <label class="font-semibold">
            Nombre
        </label>

        <input
            type="text"
            name="nombre"
            value="{{ old('nombre', $tipo->nombre) }}"
            class="w-full border rounded-xl p-3 mt-2"
            required>

    </div>

    <div class="mb-6">

        <label class="inline-flex items-center gap-2">

            <input
                type="checkbox"
                name="activo"
                {{ old('activo', $tipo->activo) ? 'checked' : '' }}>

            Activo

        </label>

    </div>

    <div class="flex gap-3">

        <button
            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl">

            {{ $modo=='crear' ? 'Guardar' : 'Actualizar' }}

        </button>

        <a
            href="{{ route('tipos-articulo.index') }}"
            class="bg-gray-200 hover:bg-gray-300 px-6 py-3 rounded-xl">

            Cancelar

        </a>

    </div>

</form>

</x-card>

@endsection
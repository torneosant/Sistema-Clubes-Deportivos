@extends('layouts.app')

@section('titulo','Editar Tipo')

@section('contenido')

<x-page-header
title="✏️ Editar Tipo de Documento"
subtitle="Actualizar información." />

<x-card>

<form action="{{ route('tipos-documento.update',$tipo) }}" method="POST">

    @csrf
    @method('PUT')

    <div class="mb-5">

        <label class="block mb-2 font-medium">
            Nombre
        </label>

        <input
            type="text"
            name="nombre"
            value="{{ old('nombre',$tipo->nombre) }}"
            class="w-full border rounded-lg px-4 py-2"
            required>

    </div>

    <div class="mb-5">

        <label class="block mb-2 font-medium">
            Descripción
        </label>

        <textarea
            name="descripcion"
            rows="3"
            class="w-full border rounded-lg px-4 py-2">{{ old('descripcion',$tipo->descripcion) }}</textarea>

    </div>

    <div class="flex gap-3">

        <button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg">
            Actualizar
        </button>

        <a href="{{ route('tipos-documento.index') }}"
           class="bg-gray-200 px-5 py-2 rounded-lg">
            Cancelar
        </a>

    </div>

</form>

</x-card>

@endsection
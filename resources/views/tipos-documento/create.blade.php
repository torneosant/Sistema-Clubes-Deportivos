@extends('layouts.app')

@section('titulo','Nuevo Tipo de Documento')

@section('contenido')

<x-page-header
title="📂 Nuevo Tipo de Documento"
subtitle="Crear una nueva categoría de documentos." />

<x-card>

<form action="{{ route('tipos-documento.store') }}" method="POST">

    @csrf

    <div class="mb-5">

        <label class="block mb-2 font-medium">
            Nombre
        </label>

        <input
            type="text"
            name="nombre"
            value="{{ old('nombre') }}"
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
            class="w-full border rounded-lg px-4 py-2">{{ old('descripcion') }}</textarea>

    </div>

    <div class="flex gap-3">

        <button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg">
            Guardar
        </button>

        <a href="{{ route('tipos-documento.index') }}"
           class="bg-gray-200 px-5 py-2 rounded-lg">
            Cancelar
        </a>

    </div>

</form>

</x-card>

@endsection
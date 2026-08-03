@extends('layouts.app')

@section('titulo','Nuevo Documento')

@section('contenido')

<x-page-header
title="📄 Nuevo Documento"
subtitle="Registrar un nuevo documento del club." />

<x-card>

<form action="{{ route('documentos.store') }}"
      method="POST"
      enctype="multipart/form-data">

@csrf

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <div>

        <label class="font-medium">Tipo de documento</label>

        <select name="tipo_documento_id"
                class="w-full mt-2 border rounded-lg px-4 py-2"
                required>

            <option value="">Seleccione...</option>

            @foreach($tipos as $tipo)

                <option value="{{ $tipo->id }}">
                    {{ $tipo->nombre }}
                </option>

            @endforeach

        </select>

    </div>

    <div>

        <label class="font-medium">Fecha</label>

        <input type="date"
               name="fecha"
               class="w-full mt-2 border rounded-lg px-4 py-2">

    </div>

</div>

<div class="mt-6">

    <label class="font-medium">Título</label>

    <input type="text"
           name="titulo"
           class="w-full mt-2 border rounded-lg px-4 py-2"
           required>

</div>

<div class="mt-6">

    <label class="font-medium">Descripción</label>

    <textarea
        name="descripcion"
        rows="4"
        class="w-full mt-2 border rounded-lg px-4 py-2"></textarea>

</div>

<div class="mt-6">

    <label class="font-medium">Archivo PDF</label>

    <input
        type="file"
        name="archivo"
        accept=".pdf"
        class="w-full mt-2 border rounded-lg px-4 py-2"
        required>

</div>

<div class="mt-8 flex gap-3">

    <button
        class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg">

        Guardar

    </button>

    <a href="{{ route('documentos.index') }}"
       class="bg-gray-200 px-5 py-2 rounded-lg">

        Cancelar

    </a>

</div>

</form>

</x-card>

@endsection
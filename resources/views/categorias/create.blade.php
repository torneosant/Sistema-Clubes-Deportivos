@extends('layouts.app')

@section('titulo','Nueva Categoría')

@section('contenido')

<div class="max-w-lg mx-auto bg-white shadow rounded-lg p-6">

    <h2 class="text-2xl font-bold mb-6">
        Nueva Categoría
    </h2>

    <form method="POST" action="{{ route('categorias.store') }}">

        @csrf

        <label class="block mb-2 font-semibold">
            Nombre
        </label>

        <input
            type="text"
            name="nombre"
            required
            class="w-full border rounded-lg px-4 py-2 mb-6">

            <div class="mt-8 flex justify-end gap-3">
    <a href="{{ route('categorias.index') }}"
       class="bg-gray-500 hover:bg-gray-600 text-white px-8 py-3 rounded-lg shadow">
        Cancelar
    </a>

        <button
            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">

            Guardar Categoría

        </button>

    </form>

</div>

@endsection
@extends('layouts.app')

@section('titulo', 'Mi Club')

@section('contenido')

@if(session('success'))
<div class="mb-5 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
    {{ session('success') }}
</div>
@endif

<div class="bg-white rounded-xl shadow">

    <div class="bg-blue-700 text-white px-6 py-4 rounded-t-xl">
        <h2 class="text-2xl font-bold">
            🏟️ Información del Club
        </h2>
    </div>

    <form action="{{ route('club.store') }}" method="POST">

        @csrf

        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <label class="font-semibold">Nombre del Club</label>

                <input
                    type="text"
                    name="nombre"
                    value="{{ old('nombre', $club->nombre ?? '') }}"
                    class="w-full mt-2 border rounded-lg p-3">
            </div>

            <div>
                <label class="font-semibold">Correo</label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email', $club->email ?? '') }}"
                    class="w-full mt-2 border rounded-lg p-3">
            </div>

            <div>
                <label class="font-semibold">Teléfono</label>

                <input
                    type="text"
                    name="telefono"
                    value="{{ old('telefono', $club->telefono ?? '') }}"
                    class="w-full mt-2 border rounded-lg p-3">
            </div>

            <div>
                <label class="font-semibold">Ciudad</label>

                <input
                    type="text"
                    name="ciudad"
                    value="{{ old('ciudad', $club->ciudad ?? '') }}"
                    class="w-full mt-2 border rounded-lg p-3">
            </div>

            <div>
                <label class="font-semibold">Departamento</label>

                <input
                    type="text"
                    name="departamento"
                    value="{{ old('departamento', $club->departamento ?? '') }}"
                    class="w-full mt-2 border rounded-lg p-3">
            </div>

            <div>
                <label class="font-semibold">Dirección</label>

                <input
                    type="text"
                    name="direccion"
                    value="{{ old('direccion', $club->direccion ?? '') }}"
                    class="w-full mt-2 border rounded-lg p-3">
            </div>

            <div class="md:col-span-2">

                <label class="font-semibold">Descripción</label>

                <textarea
                    name="descripcion"
                    rows="4"
                    class="w-full mt-2 border rounded-lg p-3">{{ old('descripcion', $club->descripcion ?? '') }}</textarea>

            </div>

        </div>

        <div class="border-t px-6 py-4 flex justify-end">

            <button
                type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg">

                💾 Guardar Cambios

            </button>

        </div>

    </form>

</div>

@endsection
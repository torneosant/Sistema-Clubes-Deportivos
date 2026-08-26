@extends('layouts.app')

@section('titulo','Nueva Competencia')

@section('contenido')

<x-page-header
    title="🏆 Nueva Competencia"
    subtitle="Crear un campeonato, festival o evento deportivo."
/>

<x-card>

<form method="POST"
      action="{{ route('competencias.store') }}">

@csrf

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <div>

        <label class="font-medium">
            Nombre
        </label>

        <input
            type="text"
            name="nombre"
            value="{{ old('nombre') }}"
            required
            class="w-full mt-2 border rounded-lg px-4 py-2">

    </div>

    <div>

        <label class="font-medium">
            Tipo
        </label>

        <select
            name="tipo"
            required
            class="w-full mt-2 border rounded-lg px-4 py-2">

            <option value="campeonato">
                🏆 Campeonato
            </option>

            <option value="festival">
                🎪 Festival
            </option>

            <option value="evento">
                🎯 Evento
            </option>

        </select>

    </div>

    <div>

        <label class="font-medium">
            Categoría principal
        </label>

        <select
            name="categoria_id"
            class="w-full mt-2 border rounded-lg px-4 py-2">

            <option value="">
                Todas / No especificada
            </option>

            @foreach($categorias as $categoria)

                <option
                    value="{{ $categoria->id }}"
                    @selected(old('categoria_id') == $categoria->id)>

                    {{ $categoria->nombre }}

                </option>

            @endforeach

        </select>

    </div>

    <div>

        <label class="font-medium">
            Estado
        </label>

        <select
            name="estado"
            required
            class="w-full mt-2 border rounded-lg px-4 py-2">

            <option value="proximo">
                Próximo
            </option>

            <option value="en_curso">
                En curso
            </option>

            <option value="finalizado">
                Finalizado
            </option>

            <option value="cancelado">
                Cancelado
            </option>

        </select>

    </div>

    <div>

        <label class="font-medium">
            Fecha inicio
        </label>

        <input
            type="date"
            name="fecha_inicio"
            value="{{ old('fecha_inicio') }}"
            class="w-full mt-2 border rounded-lg px-4 py-2">

    </div>

    <div>

        <label class="font-medium">
            Fecha final
        </label>

        <input
            type="date"
            name="fecha_fin"
            value="{{ old('fecha_fin') }}"
            class="w-full mt-2 border rounded-lg px-4 py-2">

    </div>

</div>

<div class="mt-6">

    <label class="font-medium">
        Lugar
    </label>

    <input
        type="text"
        name="lugar"
        value="{{ old('lugar') }}"
        class="w-full mt-2 border rounded-lg px-4 py-2">

</div>

<div class="mt-6">

    <label class="font-medium">
        Descripción
    </label>

    <textarea
        name="descripcion"
        rows="4"
        class="w-full mt-2 border rounded-lg px-4 py-2">{{ old('descripcion') }}</textarea>

</div>

<div class="mt-8 flex gap-3">

    <button
        class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg">

        Guardar competencia

    </button>

    <a href="{{ route('competencias.index') }}"
       class="bg-gray-200 px-5 py-2 rounded-lg">

        Cancelar

    </a>

</div>

</form>

</x-card>

@endsection
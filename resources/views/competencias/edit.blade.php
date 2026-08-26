@extends('layouts.app')

@section('titulo', 'Editar Competencia')

@section('contenido')

<x-page-header
    title="✏️ Editar Competencia"
    subtitle="Actualizar información de la competencia."
/>

<x-card>

<form method="POST"
      action="{{ route('competencias.update', $competencia) }}">

@csrf
@method('PUT')

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <div>

        <label class="font-medium">
            Nombre
        </label>

        <input
            type="text"
            name="nombre"
            value="{{ old('nombre', $competencia->nombre) }}"
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

            <option value="campeonato"
                @selected(old('tipo', $competencia->tipo) === 'campeonato')>
                🏆 Campeonato
            </option>

            <option value="festival"
                @selected(old('tipo', $competencia->tipo) === 'festival')>
                🎪 Festival
            </option>

            <option value="evento"
                @selected(old('tipo', $competencia->tipo) === 'evento')>
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
                    @selected(old('categoria_id', $competencia->categoria_id) == $categoria->id)>

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

            <option value="proximo"
                @selected(old('estado', $competencia->estado) === 'proximo')>
                Próximo
            </option>

            <option value="en_curso"
                @selected(old('estado', $competencia->estado) === 'en_curso')>
                En curso
            </option>

            <option value="finalizado"
                @selected(old('estado', $competencia->estado) === 'finalizado')>
                Finalizado
            </option>

            <option value="cancelado"
                @selected(old('estado', $competencia->estado) === 'cancelado')>
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
            value="{{ old('fecha_inicio', $competencia->fecha_inicio?->format('Y-m-d')) }}"
            class="w-full mt-2 border rounded-lg px-4 py-2">

    </div>


    <div>

        <label class="font-medium">
            Fecha final
        </label>

        <input
            type="date"
            name="fecha_fin"
            value="{{ old('fecha_fin', $competencia->fecha_fin?->format('Y-m-d')) }}"
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
        value="{{ old('lugar', $competencia->lugar) }}"
        class="w-full mt-2 border rounded-lg px-4 py-2">

</div>


<div class="mt-6">

    <label class="font-medium">
        Descripción
    </label>

    <textarea
        name="descripcion"
        rows="4"
        class="w-full mt-2 border rounded-lg px-4 py-2">{{ old('descripcion', $competencia->descripcion) }}</textarea>

</div>


<div class="mt-8 flex gap-3">

    <button
        class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg">

        Guardar cambios

    </button>

    <a href="{{ route('competencias.index') }}"
       class="bg-gray-200 px-5 py-2 rounded-lg">

        Cancelar

    </a>

</div>

</form>

</x-card>

@endsection
@extends('layouts.app')
@section('contenido')

    <div class="max-w-5xl mx-auto">

        <div class="bg-white rounded-xl shadow-lg p-8">

            <h1 class="text-3xl font-bold text-slate-700 mb-8">
        ✏️ Editar Partido
            </h1>

            <form action="{{ route('partidos.update',$partido) }}" method="POST">

        @csrf
        @method('PUT')

                <div class="grid grid-cols-2 gap-6">

                    <div>
                        <label class="font-semibold">Equipo</label>

                        <select name="equipo_id" class="w-full border rounded-lg p-2">
                            @foreach($equipos as $equipo)
                            <option value="{{ $equipo->id }}"
                            {{ $partido->equipo_id==$equipo->id ? 'selected' : '' }}>
                                    
                                    {{ $equipo->nombre }}
                                </option>
                            @endforeach
                        </select>

                    </div>

                    <div>

                        <label class="font-semibold">Categoría</label>

                        <select name="categoria_id" class="w-full border rounded-lg p-2">
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id }}"
                                {{ $partido->equipo_id==$equipo->id ? 'selected' : '' }}>
                                    {{ $categoria->nombre }}
                                </option>
                            @endforeach
                        </select>

                    </div>

                    <div>

                        <label class="font-semibold">Competencia</label>

                    <input
                    type="text"
                    name="competencia"
                    value="{{ old('competencia',$partido->competencia) }}"
                    class="w-full border rounded-lg p-2">

                    </div>

                    <div>

                        <label class="font-semibold">Rival</label>

                        <input
    type="text"
    name="rival"
    value="{{ old('rival',$partido->rival) }}"
    class="w-full border rounded-lg p-2">

                    </div>

                    <div>

                        <label class="font-semibold">Fecha</label>

                        <input
    type="text"
    name="fecha"
    value="{{ old('fecha',$partido->fecha) }}"
    class="w-full border rounded-lg p-2">

                    </div>

                    <div>

                        <label class="font-semibold">Hora</label>

                        <input
    type="text"
    name="hora"
    value="{{ old('hora',$partido->hora) }}"
    class="w-full border rounded-lg p-2">

                    </div>

                    <div>

                        <label class="font-semibold">Lugar</label>

                        <input
    type="text"
    name="lugar"
    value="{{ old('lugar',$partido->lugar) }}"
    class="w-full border rounded-lg p-2">

                    </div>

                    <div>

                        <label class="font-semibold">Condición</label>
    <select name="condicion" class="w-full border rounded-lg p-2">

    <option value="Local"
    {{ $partido->condicion=='Local' ? 'selected' : '' }}>
    Local
    </option>

    <option value="Visitante"
    {{ $partido->condicion=='Visitante' ? 'selected' : '' }}>
    Visitante
    </option>

    </select>
    <div>

    <label class="font-semibold">
    Estado
    </label>

    <select name="estado" class="w-full border rounded-lg p-2">

    <option value="Programado"
    {{ $partido->estado=='Programado' ? 'selected' : '' }}>
    Programado
    </option>

    <option value="Jugado"
    {{ $partido->estado=='Jugado' ? 'selected' : '' }}>
    Jugado
    </option>

    <option value="Aplazado"
    {{ $partido->estado=='Aplazado' ? 'selected' : '' }}>
    Aplazado
    </option>

    <option value="Suspendido"
    {{ $partido->estado=='Suspendido' ? 'selected' : '' }}>
    Suspendido
    </option>

    <option value="Cancelado"
    {{ $partido->estado=='Cancelado' ? 'selected' : '' }}>
    Cancelado
    </option>

    </select>

    </div>

                    </div>

                </div>

                <div class="mt-8 flex gap-3">

                    <button
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">

                        💾 Guardar Partido

                    </button>

                    <a href="{{ route('partidos.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">

                        Cancelar

                    </a>

                </div>

            </form>

        </div>

    </div>

    @endsection
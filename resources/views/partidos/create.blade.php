@extends('layouts.app')

@section('contenido')

<div class="max-w-5xl mx-auto">

    <div class="bg-white rounded-xl shadow-lg p-8">

        <h1 class="text-3xl font-bold text-slate-700 mb-8">
            ⚽ Programar Partido
        </h1>

        <form action="{{ route('partidos.store') }}" method="POST">

            @csrf

            <div class="grid grid-cols-2 gap-6">

                {{-- EQUIPO --}}

                <div>

                    <label class="font-semibold">
                        Equipo
                    </label>

                    <select
                        name="equipo_id"
                        class="w-full border rounded-lg p-2"
                        required
                    >

                        @foreach($equipos as $equipo)

                            <option
                                value="{{ $equipo->id }}"
                                {{ old('equipo_id') == $equipo->id ? 'selected' : '' }}
                            >
                                {{ $equipo->nombre }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- CATEGORÍA --}}

                <div>

                    <label class="font-semibold">
                        Categoría
                    </label>

                    <select
                        name="categoria_id"
                        class="w-full border rounded-lg p-2"
                        required
                    >

                        @foreach($categorias as $categoria)

                            <option
                                value="{{ $categoria->id }}"
                                {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}
                            >
                                {{ $categoria->nombre }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- COMPETENCIA --}}

                <div>

                    <label class="font-semibold">
                        Competencia
                    </label>

                    <select
                        name="competencia_id"
                        class="w-full border rounded-lg p-2"
                    >

                        <option value="">
                            — Amistoso / sin competencia —
                        </option>

                        @foreach($competencias as $competencia)

                            <option
                                value="{{ $competencia->id }}"
                                {{ old('competencia_id') == $competencia->id ? 'selected' : '' }}
                            >
                                {{ $competencia->nombre }}
                            </option>

                        @endforeach

                    </select>

                    <p class="text-xs text-gray-500 mt-1">
                        Selecciona una competencia si el partido pertenece a un torneo.
                    </p>

                </div>


                {{-- RIVAL --}}

                <div>

                    <label class="font-semibold">
                        Rival
                    </label>

                    <input
                        type="text"
                        name="rival"
                        value="{{ old('rival') }}"
                        class="w-full border rounded-lg p-2"
                        required
                    >

                </div>


                {{-- FECHA --}}

                <div>

                    <label class="font-semibold">
                        Fecha
                    </label>

                    <input
                        type="date"
                        name="fecha"
                        value="{{ old('fecha') }}"
                        min="{{ $anioTrabajo }}-01-01"
                        max="{{ $anioTrabajo }}-12-31"
                        class="w-full border rounded-lg p-2"
                        required
                    >

                </div>


                {{-- HORA --}}

                <div>

                    <label class="font-semibold">
                        Hora
                    </label>

                    <input
                        type="time"
                        name="hora"
                        value="{{ old('hora') }}"
                        class="w-full border rounded-lg p-2"
                        required
                    >

                </div>


                {{-- LUGAR --}}

                <div>

                    <label class="font-semibold">
                        Lugar
                    </label>

                    <input
                        type="text"
                        name="lugar"
                        value="{{ old('lugar') }}"
                        class="w-full border rounded-lg p-2"
                    >

                </div>


                {{-- CONDICIÓN --}}

                <div>

                    <label class="font-semibold">
                        Condición
                    </label>

                    <select
                        name="condicion"
                        class="w-full border rounded-lg p-2"
                        required
                    >

                        <option value="Local">
                            Local
                        </option>

                        <option value="Visitante">
                            Visitante
                        </option>

                    </select>

                </div>

            </div>


            {{-- BOTONES --}}

            <div class="mt-8 flex gap-3">

                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg"
                >
                    💾 Guardar Partido
                </button>

                <a
                    href="{{ route('partidos.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg"
                >
                    Cancelar
                </a>

            </div>

        </form>

    </div>

</div>

@endsection
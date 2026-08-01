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

                <div>
                    <label class="font-semibold">Equipo</label>

                    <select name="equipo_id" class="w-full border rounded-lg p-2">
                        @foreach($equipos as $equipo)
                            <option value="{{ $equipo->id }}">
                                {{ $equipo->nombre }}
                            </option>
                        @endforeach
                    </select>

                </div>

                <div>

                    <label class="font-semibold">Categoría</label>

                    <select name="categoria_id" class="w-full border rounded-lg p-2">
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}">
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
                        class="w-full border rounded-lg p-2">

                </div>

                <div>

                    <label class="font-semibold">Rival</label>

                    <input
                        type="text"
                        name="rival"
                        class="w-full border rounded-lg p-2">

                </div>

                <div>

                    <label class="font-semibold">Fecha</label>

                    <input
                        type="date"
                        name="fecha"
                        class="w-full border rounded-lg p-2">

                </div>

                <div>

                    <label class="font-semibold">Hora</label>

                    <input
                        type="time"
                        name="hora"
                        class="w-full border rounded-lg p-2">

                </div>

                <div>

                    <label class="font-semibold">Lugar</label>

                    <input
                        type="text"
                        name="lugar"
                        class="w-full border rounded-lg p-2">

                </div>

                <div>

                    <label class="font-semibold">Condición</label>

                    <select name="condicion" class="w-full border rounded-lg p-2">

                        <option>Local</option>

                        <option>Visitante</option>

                    </select>

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
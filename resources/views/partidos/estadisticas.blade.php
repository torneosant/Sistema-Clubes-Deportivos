@extends('layouts.app')

@section('titulo')
📊 Estadísticas del Partido
@endsection

@section('contenido')

<div class="bg-white rounded-xl shadow-lg mb-6">

    <div class="bg-slate-800 text-white p-6 rounded-t-xl">

        <h2 class="text-2xl font-bold">
            {{ $partido->competencia }}
        </h2>

    </div>

    <div class="p-6">

        <div class="flex justify-between items-center">

            <div class="text-xl font-bold">
                {{ $partido->equipo->nombre }}
            </div>

            <div class="text-center">

                <div class="text-5xl font-extrabold text-slate-700">

                    {{ $partido->goles_favor ?? '-' }}

                    <span class="mx-3 text-gray-400">:</span>

                    {{ $partido->goles_contra ?? '-' }}

                </div>

                <div class="text-sm text-gray-500">
                    Resultado
                </div>

            </div>

            <div class="text-xl font-bold">
                {{ $partido->rival }}
            </div>

        </div>

        <hr class="my-6">

        <div class="grid grid-cols-3 gap-4 text-sm">

            <div>
                <strong>Fecha:</strong><br>
                {{ \Carbon\Carbon::parse($partido->fecha)->format('d/m/Y') }}
            </div>

            <div>
                <strong>Hora:</strong><br>
                {{ \Carbon\Carbon::parse($partido->hora)->format('H:i') }}
            </div>

            <div>
                <strong>Lugar:</strong><br>
                {{ $partido->lugar }}
            </div>

            <div>
                <strong>Categoría:</strong><br>
                {{ $partido->categoria->nombre }}
            </div>

            <div>
                <strong>Condición:</strong><br>
                {{ $partido->condicion }}
            </div>

            <div>
                <strong>Estado:</strong><br>
                {{ $partido->estado }}
            </div>

        </div>

    </div>

</div>

        <form method="POST"
              action="{{ route('partidos.estadisticas.store',$partido) }}">

            @csrf

            <div class="overflow-x-auto">

                <table class="min-w-full text-sm">

                    <thead class="bg-gray-100">

                    <tr>

                        <th class="p-3 text-left">Jugadora</th>

                        <th class="text-center">Participación</th>

                        <th class="text-center">Min</th>

                        <th class="text-center">⚽</th>

                        <th class="text-center">🎯</th>

                        <th class="text-center">🟨</th>

                        <th class="text-center">🟥</th>

                        <th class="text-center">⭐</th>

                    </tr>

                    </thead>

                    <tbody>

                    @foreach($jugadores as $jugador)

                        @php
                            $e = $estadisticas[$jugador->id] ?? null;
                        @endphp

                        <tr class="border-b hover:bg-gray-50">

                            <td class="p-3">

                                <div class="font-semibold">
                                    {{ $jugador->apellidos }}
                                </div>

                                <div class="text-gray-500 text-xs">
                                    {{ $jugador->nombres }}
                                </div>

                            </td>

                            <td class="text-center">

                                <select
    name="participacion[{{ $jugador->id }}]"
    class="border rounded px-2 py-1">

    <option value="No jugó"
        {{ ($e->participacion ?? '')=='No jugó' ? 'selected' : '' }}>
        No jugó
    </option>

    <option value="Suplente"
        {{ ($e->participacion ?? '')=='Suplente' ? 'selected' : '' }}>
        Suplente
    </option>

    <option value="Titular"
        {{ ($e->participacion ?? '')=='Titular' ? 'selected' : '' }}>
        Titular
    </option>

</select>
                            </td>

                            <td class="text-center">

                                <input
                                    type="number"
                                    min="0"
                                    max="120"
                                    class="w-16 border rounded text-center"
                                    name="minutos[{{ $jugador->id }}]"
                                    value="{{ $e->minutos ?? 0 }}">

                            </td>

                            <td class="text-center">

                                <input
                                    type="number"
                                    min="0"
                                    class="w-14 border rounded text-center"
                                    name="goles[{{ $jugador->id }}]"
                                    value="{{ $e->goles ?? 0 }}">

                            </td>

                            <td class="text-center">

                                <input
                                    type="number"
                                    min="0"
                                    class="w-14 border rounded text-center"
                                    name="asistencias[{{ $jugador->id }}]"
                                    value="{{ $e->asistencias ?? 0 }}">

                            </td>

                            <td class="text-center">

                                <input
                                    type="number"
                                    min="0"
                                    class="w-14 border rounded text-center"
                                    name="amarillas[{{ $jugador->id }}]"
                                    value="{{ $e->amarillas ?? 0 }}">

                            </td>

                            <td class="text-center">

                                <input
                                    type="number"
                                    min="0"
                                    class="w-14 border rounded text-center"
                                    name="rojas[{{ $jugador->id }}]"
                                    value="{{ $e->rojas ?? 0 }}">

                            </td>

                            <td class="text-center">

                                <input
                                    type="checkbox"
                                    name="figura[{{ $jugador->id }}]"
                                    value="1"
                                    {{ $e && $e->figura ? 'checked' : '' }}>

                            </td>

                        </tr>

                    @endforeach

                    </tbody>

                </table>

            </div>

            <div class="flex justify-end gap-3 p-6 border-t">

                <a href="{{ route('partidos.index') }}"
                   class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg">

                    Cancelar

                </a>

                <button
                    type="submit"
                    class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg">

                    💾 Guardar Estadísticas

                </button>

            </div>

        </form>

    </div>

</div>

@endsection
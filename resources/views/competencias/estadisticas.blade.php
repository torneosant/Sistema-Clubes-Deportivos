@extends('layouts.app')

@section('titulo', 'Estadísticas - ' . $competencia->nombre)

@section('contenido')

<x-page-header
    title="📊 Estadísticas"
    subtitle="Rendimiento del equipo en {{ $competencia->nombre }}."
/>


{{-- ==========================================================
     CABECERA
=========================================================== --}}

<div class="mb-5 flex justify-between items-center">

    <a
        href="{{ route('competencias.show', $competencia) }}"
        class="bg-gray-200 hover:bg-gray-300 px-5 py-2 rounded-lg"
    >
        ← Volver a la competencia
    </a>

</div>



{{-- ==========================================================
     INFORMACIÓN DE LA COMPETENCIA
=========================================================== --}}

<x-card>

    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">

        <div>

            <h2 class="text-2xl font-bold text-slate-800">
                🏆 {{ $competencia->nombre }}
            </h2>

            <p class="text-sm text-gray-500 mt-1">

                @switch($competencia->tipo)

                    @case('campeonato')
                        Campeonato
                        @break

                    @case('festival')
                        Festival
                        @break

                    @case('evento')
                        Evento
                        @break

                @endswitch

                @if($competencia->categoria)
                    · {{ $competencia->categoria->nombre }}
                @endif

            </p>

        </div>


        <div class="text-right">

            <div class="text-sm text-gray-500">
                Puntos adicionales
            </div>

            <div class="text-3xl font-bold text-blue-600">
                {{ $puntosAdicionales }}
            </div>

        </div>

    </div>

</x-card>



{{-- ==========================================================
     ESTADÍSTICAS PRINCIPALES
=========================================================== --}}

<div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4 mt-6">


    {{-- PJ --}}

    <x-card>

        <div class="text-center">

            <div class="text-sm text-gray-500">
                PJ
            </div>

            <div class="text-3xl font-bold text-slate-800">
                {{ $pj }}
            </div>

            <div class="text-xs text-gray-400">
                Jugados
            </div>

        </div>

    </x-card>



    {{-- PG --}}

    <x-card>

        <div class="text-center">

            <div class="text-sm text-gray-500">
                PG
            </div>

            <div class="text-3xl font-bold text-green-600">
                {{ $pg }}
            </div>

            <div class="text-xs text-gray-400">
                Ganados
            </div>

        </div>

    </x-card>



    {{-- PE --}}

    <x-card>

        <div class="text-center">

            <div class="text-sm text-gray-500">
                PE
            </div>

            <div class="text-3xl font-bold text-yellow-600">
                {{ $pe }}
            </div>

            <div class="text-xs text-gray-400">
                Empates
            </div>

        </div>

    </x-card>



    {{-- PP --}}

    <x-card>

        <div class="text-center">

            <div class="text-sm text-gray-500">
                PP
            </div>

            <div class="text-3xl font-bold text-red-600">
                {{ $pp }}
            </div>

            <div class="text-xs text-gray-400">
                Perdidos
            </div>

        </div>

    </x-card>



    {{-- GF --}}

    <x-card>

        <div class="text-center">

            <div class="text-sm text-gray-500">
                GF
            </div>

            <div class="text-3xl font-bold text-blue-600">
                {{ $gf }}
            </div>

            <div class="text-xs text-gray-400">
                A favor
            </div>

        </div>

    </x-card>



    {{-- GC --}}

    <x-card>

        <div class="text-center">

            <div class="text-sm text-gray-500">
                GC
            </div>

            <div class="text-3xl font-bold text-orange-600">
                {{ $gc }}
            </div>

            <div class="text-xs text-gray-400">
                En contra
            </div>

        </div>

    </x-card>



    {{-- DG --}}

    <x-card>

        <div class="text-center">

            <div class="text-sm text-gray-500">
                DG
            </div>

            <div
                class="text-3xl font-bold
                {{ $dg > 0 ? 'text-green-600' : ($dg < 0 ? 'text-red-600' : 'text-gray-600') }}"
            >
                {{ $dg > 0 ? '+' : '' }}{{ $dg }}
            </div>

            <div class="text-xs text-gray-400">
                Diferencia
            </div>

        </div>

    </x-card>



    {{-- PUNTOS --}}

    <x-card>

        <div class="text-center">

            <div class="text-sm text-gray-500">
                Puntos
            </div>

            <div class="text-3xl font-bold text-purple-600">
                {{ $puntosTotales }}
            </div>

            <div class="text-xs text-gray-400">
                Total
            </div>

        </div>

    </x-card>

</div>



{{-- ==========================================================
     RENDIMIENTO
=========================================================== --}}

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mt-6">


    {{-- RENDIMIENTO --}}

    <x-card>

        <div class="text-center">

            <div class="text-3xl mb-2">
                📈
            </div>

            <p class="text-sm text-gray-500">
                Rendimiento
            </p>

            <p class="text-3xl font-bold text-blue-600">
                {{ number_format($rendimiento, 2) }}%
            </p>

            <p class="text-xs text-gray-400 mt-1">
                Sobre puntos posibles
            </p>

        </div>

    </x-card>



    {{-- EFECTIVIDAD GOLEADORA --}}

    <x-card>

        <div class="text-center">

            <div class="text-3xl mb-2">
                ⚽
            </div>

            <p class="text-sm text-gray-500">
                Efectividad goleadora
            </p>

            <p class="text-3xl font-bold text-green-600">
                {{ number_format($efectividadGoleadora, 2) }}%
            </p>

            <p class="text-xs text-gray-400 mt-1">
                GF sobre GF + GC
            </p>

        </div>

    </x-card>



    {{-- PROMEDIO GF --}}

    <x-card>

        <div class="text-center">

            <div class="text-3xl mb-2">
                🔵
            </div>

            <p class="text-sm text-gray-500">
                Promedio goles a favor
            </p>

            <p class="text-3xl font-bold text-slate-700">
                {{ number_format($promedioGf, 2) }}
            </p>

            <p class="text-xs text-gray-400 mt-1">
                Por partido
            </p>

        </div>

    </x-card>



    {{-- PROMEDIO GC --}}

    <x-card>

        <div class="text-center">

            <div class="text-3xl mb-2">
                🛡️
            </div>

            <p class="text-sm text-gray-500">
                Promedio goles en contra
            </p>

            <p class="text-3xl font-bold text-slate-700">
                {{ number_format($promedioGc, 2) }}
            </p>

            <p class="text-xs text-gray-400 mt-1">
                Por partido
            </p>

        </div>

    </x-card>

</div>



{{-- ==========================================================
     OTROS INDICADORES
=========================================================== --}}

<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mt-6">


    {{-- PARTIDOS MARCANDO --}}

    <x-card>

        <div class="flex justify-between items-center">

            <div>

                <p class="text-sm text-gray-500">
                    Partidos marcando
                </p>

                <p class="text-2xl font-bold text-slate-800">
                    {{ $porcentajePartidosMarcando }}%
                </p>

            </div>

            <div class="text-3xl">
                ⚽
            </div>

        </div>

        <p class="text-xs text-gray-400 mt-2">
            {{ $partidos->where('goles_favor', '>', 0)->count() }}
            de
            {{ $pj }}
            partidos
        </p>

    </x-card>



    {{-- PORTERÍAS EN CERO --}}

    <x-card>

        <div class="flex justify-between items-center">

            <div>

                <p class="text-sm text-gray-500">
                    Porterías en cero
                </p>

                <p class="text-2xl font-bold text-slate-800">
                    {{ $porcentajePorteriasCero }}%
                </p>

            </div>

            <div class="text-3xl">
                🧤
            </div>

        </div>

        <p class="text-xs text-gray-400 mt-2">
            {{ $porteriasCero }}
            de
            {{ $pj }}
            partidos
        </p>

    </x-card>



    {{-- PUNTOS --}}

    <x-card>

        <div class="flex justify-between items-center">

            <div>

                <p class="text-sm text-gray-500">
                    Puntos obtenidos
                </p>

                <p class="text-2xl font-bold text-purple-600">
                    {{ $puntosTotales }}
                </p>

            </div>

            <div class="text-3xl">
                🏆
            </div>

        </div>

        <p class="text-xs text-gray-400 mt-2">

            {{ $puntos }} por resultados
            +
            {{ $puntosAdicionales }} adicionales

        </p>

    </x-card>

</div>



{{-- ==========================================================
     GOLEADORAS
=========================================================== --}}

<x-card class="mt-6">

    <div class="flex justify-between items-center mb-5">

        <div>

            <h2 class="text-xl font-bold text-slate-800">
                ⚽ Goleadoras del torneo
            </h2>

            <p class="text-sm text-gray-500">
                Jugadoras que han marcado goles en esta competencia.
            </p>

        </div>

    </div>


    @php

        /*
        |--------------------------------------------------------------------------
        | Solo mostrar jugadoras con goles
        |--------------------------------------------------------------------------
        */

        $goleadorasConGoles = $goleadoras
            ->filter(fn ($goleadora) => $goleadora->goles > 0)
            ->values();

    @endphp


    @if($goleadorasConGoles->count())

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead>

                    <tr class="border-b text-left">

                        <th class="py-3">
                            #
                        </th>

                        <th class="py-3">
                            Jugadora
                        </th>

                        <th class="py-3 text-center">
                            PJ
                        </th>

                        <th class="py-3 text-center">
                            ⚽ Goles
                        </th>

                        <th class="py-3 text-center">
                            🎯 Asistencias
                        </th>

                    </tr>

                </thead>


                <tbody>

                @foreach($goleadorasConGoles as $index => $goleadora)

                    <tr class="border-b hover:bg-slate-50">

                        <td class="py-3 font-bold">

                            @if($index === 0)
                                🥇
                            @elseif($index === 1)
                                🥈
                            @elseif($index === 2)
                                🥉
                            @else
                                {{ $index + 1 }}
                            @endif

                        </td>


                        <td class="py-3">

                            @if($goleadora->jugador)

                                <div class="font-semibold">
                                    {{ $goleadora->jugador->apellidos }}
                                </div>

                                <div class="text-xs text-gray-500">
                                    {{ $goleadora->jugador->nombres }}
                                </div>

                            @else

                                Jugadora no disponible

                            @endif

                        </td>


                        <td class="py-3 text-center">
                            {{ $goleadora->partidos }}
                        </td>


                        <td class="py-3 text-center">

                            <span class="font-bold text-green-600 text-lg">
                                {{ $goleadora->goles }}
                            </span>

                        </td>


                        <td class="py-3 text-center">
                            {{ $goleadora->asistencias }}
                        </td>

                    </tr>

                @endforeach

                </tbody>

            </table>

        </div>

    @else

        <div class="text-center py-10 text-gray-500">

            <div class="text-4xl mb-3">
                ⚽
            </div>

            <p>
                Todavía no hay goles registrados en esta competencia.
            </p>

        </div>

    @endif

</x-card>



{{-- ==========================================================
     PARTIDOS DEL TORNEO
=========================================================== --}}

<x-card class="mt-6">

    <div class="mb-5">

        <h2 class="text-xl font-bold text-slate-800">
            ⚽ Partidos de la competencia
        </h2>

        <p class="text-sm text-gray-500">
            Resultados utilizados para calcular las estadísticas.
        </p>

    </div>


    @if($partidos->count())

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead>

                    <tr class="border-b text-left">

                        <th class="py-3">
                            Fecha
                        </th>

                        <th class="py-3">
                            Rival
                        </th>

                        <th class="py-3 text-center">
                            Resultado
                        </th>

                        <th class="py-3">
                            Estado
                        </th>

                    </tr>

                </thead>


                <tbody>

                @foreach($partidos as $partido)

                    <tr class="border-b hover:bg-slate-50">

                        <td class="py-3">

                            {{ \Carbon\Carbon::parse($partido->fecha)->format('d/m/Y') }}

                        </td>


                        <td class="py-3 font-medium">

                            {{ $partido->rival }}

                        </td>


                        <td class="py-3 text-center">

                            <span class="font-bold">

                                {{ $partido->goles_favor }}

                                :

                                {{ $partido->goles_contra }}

                            </span>

                        </td>


                        <td class="py-3">

                            @if($partido->goles_favor > $partido->goles_contra)

                                <span class="px-2 py-1 rounded bg-green-100 text-green-700">
                                    Victoria
                                </span>

                            @elseif($partido->goles_favor == $partido->goles_contra)

                                <span class="px-2 py-1 rounded bg-yellow-100 text-yellow-700">
                                    Empate
                                </span>

                            @else

                                <span class="px-2 py-1 rounded bg-red-100 text-red-700">
                                    Derrota
                                </span>

                            @endif

                        </td>

                    </tr>

                @endforeach

                </tbody>

            </table>

        </div>

    @else

        <div class="text-center py-10 text-gray-500">

            No hay partidos jugados registrados en esta competencia.

        </div>

    @endif

</x-card>



@endsection
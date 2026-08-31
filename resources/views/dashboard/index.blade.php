@extends('layouts.app')

@section('titulo', 'Dashboard')

@section('contenido')



{{-- =========================================================
     DASHBOARD ADMINISTRATIVO
========================================================= --}}

@if(!$esEntrenador && !$esDeportista)

    {{-- INDICADORES --}}

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

        <x-card>

            <div class="text-sm text-gray-500">
                👥 Jugadores
            </div>

            <div class="text-3xl font-bold text-blue-600 mt-1">
                {{ $totalActivos }}
            </div>

            <div class="text-xs text-gray-500 mt-1">
                de {{ $totalJugadores }} registrados
            </div>

        </x-card>


        <x-card>

            <div class="text-sm text-gray-500">
                ⚽ Equipos
            </div>

            <div class="text-3xl font-bold text-green-600 mt-1">
                {{ $totalEquipos }}
            </div>

            <div class="text-xs text-gray-500 mt-1">
                {{ $totalCategorias }} categorías
            </div>

        </x-card>


        <x-card>

            <div class="text-sm text-gray-500">
                🧑‍🏫 Entrenadores
            </div>

            <div class="text-3xl font-bold text-purple-600 mt-1">
                {{ $totalEntrenadores }}
            </div>

            <div class="text-xs text-gray-500 mt-1">
                entrenadores activos
            </div>

        </x-card>


        <x-card>

            <div class="text-sm text-gray-500">
                💰 Pendiente de pago
            </div>

            <div class="text-3xl font-bold text-red-600 mt-1">
                ${{ number_format($totalPendiente, 0, ',', '.') }}
            </div>

            <div class="text-xs text-gray-500 mt-1">
                {{ $totalObligacionesPendientes }}
                obligaciones pendientes
            </div>

        </x-card>

    </div>

@endif


{{-- =========================================================
     DASHBOARD ENTRENADOR
========================================================= --}}

@if($esEntrenador)

    <x-card class="mb-6">

        <div class="flex items-center justify-between mb-4">

            <div>

                <h2 class="text-xl font-bold">
                    ⚽ Mis equipos
                </h2>

                <p class="text-sm text-gray-500">
                    Equipos asignados a mi perfil.
                </p>

            </div>

        </div>


        @if($misEquipos->count())

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">

                @foreach($misEquipos as $equipo)

                    <div class="border rounded-xl p-4 bg-gray-50">

                        <div class="font-bold">
                            {{ $equipo->nombre }}
                        </div>

                    </div>

                @endforeach

            </div>

        @else

            <p class="text-gray-500">
                No tienes equipos asignados.
            </p>

        @endif

    </x-card>

@endif


{{-- =========================================================
     DASHBOARD DEPORTISTA
========================================================= --}}

@if($esDeportista && $miJugador)

    <x-card class="mb-6">

        <div class="flex flex-col sm:flex-row sm:items-center
                    justify-between gap-4">

            <div>

                <div class="text-sm text-gray-500">
                    👤 Mi perfil
                </div>

                <h2 class="text-2xl font-bold">
                    {{ $miJugador->nombres }}
                    {{ $miJugador->apellidos }}
                </h2>

            </div>


            <div>

                @if($miBeca)

                    <span class="inline-flex px-3 py-2 rounded-lg
                                 bg-purple-100 text-purple-700
                                 text-sm font-semibold">

                        🎓 Becado
                        {{ number_format($miBeca->porcentaje, 0) }}%

                    </span>

                @else

                    <span class="inline-flex px-3 py-2 rounded-lg
                                 bg-gray-100 text-gray-600
                                 text-sm">

                        🎓 Sin beca vigente

                    </span>

                @endif

            </div>

        </div>


        @if($misEquipos->count())

            <div class="mt-4">

                <div class="text-sm text-gray-500 mb-2">
                    ⚽ Mi equipo
                </div>

                <div class="flex flex-wrap gap-2">

                    @foreach($misEquipos as $equipo)

                        <span class="px-3 py-2 rounded-lg
                                     bg-blue-100 text-blue-700
                                     font-semibold">

                            {{ $equipo->nombre }}

                        </span>

                    @endforeach

                </div>

            </div>

        @endif

    </x-card>


    {{-- ESTADO DE CUENTA --}}

    @php

        $miPendiente = $misCargos->sum(function ($cargo) {

            return max(
                0,
                (float) ($cargo->valor ?? 0)
                -
                (float) ($cargo->valor_pagado ?? 0)
            );

        });

    @endphp


    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">

        <x-card>

            <div class="text-sm text-gray-500">
                💰 Mi pendiente
            </div>

            <div class="text-3xl font-bold
                        {{ $miPendiente > 0
                            ? 'text-red-600'
                            : 'text-green-600' }}">

                ${{ number_format(
                    $miPendiente,
                    0,
                    ',',
                    '.'
                ) }}

            </div>

        </x-card>


        <x-card>

            <div class="text-sm text-gray-500">
                🎓 Estado de beca
            </div>

            @if($miBeca)

                <div class="text-xl font-bold text-purple-600">
                    {{ number_format(
                        $miBeca->porcentaje,
                        0
                    ) }}%
                </div>

                <div class="text-xs text-gray-500">
                    {{ $miBeca->concepto?->nombre ?? 'Beca vigente' }}
                </div>

            @else

                <div class="text-xl font-bold text-gray-600">
                    Sin beca
                </div>

            @endif

        </x-card>

    </div>

@endif


{{-- =========================================================
     EVENTOS
========================================================= --}}

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">


    {{-- ENTRENAMIENTOS --}}

    <x-card>

        <div class="flex items-center justify-between mb-4">

            <h2 class="text-xl font-bold">
                🏃 Próximos entrenamientos
            </h2>

        </div>


        @forelse($entrenamientos as $entrenamiento)

            <div class="border-b py-3 last:border-b-0">

                <div class="font-semibold">

                    {{ $entrenamiento->equipo?->nombre
                        ?? 'Entrenamiento' }}

                </div>


                <div class="text-sm text-gray-600">

                    📅
                    {{ \Carbon\Carbon::parse(
                        $entrenamiento->fecha
                    )->format('d/m/Y') }}

                    @if($entrenamiento->hora_inicio)

                        · ⏰
                        {{ \Carbon\Carbon::parse(
                            $entrenamiento->hora_inicio
                        )->format('H:i') }}

                    @endif

                </div>


                @if($entrenamiento->lugar)

                    <div class="text-sm text-gray-500 mt-1">

                        📍 {{ $entrenamiento->lugar }}

                    </div>

                @endif

            </div>

        @empty

            <p class="text-gray-500">
                No hay entrenamientos programados.
            </p>

        @endforelse

    </x-card>


    {{-- PARTIDOS --}}

    <x-card>

        <h2 class="text-xl font-bold mb-4">
            ⚽ Próximos partidos
        </h2>


        @forelse($partidos as $partido)

            <div class="border-b py-3 last:border-b-0">

                <div class="font-semibold">

                    {{ $partido->equipo?->nombre
                        ?? 'Partido' }}

                    @if($partido->rival)

                        vs {{ $partido->rival }}

                    @endif

                </div>


                <div class="text-sm text-gray-600">

                    📅
                    {{ \Carbon\Carbon::parse(
                        $partido->fecha
                    )->format('d/m/Y') }}

                    @if($partido->hora)

                        · ⏰
                        {{ \Carbon\Carbon::parse(
                            $partido->hora
                        )->format('H:i') }}

                    @endif

                </div>


                @if($partido->lugar)

                    <div class="text-sm text-gray-500 mt-1">

                        📍 {{ $partido->lugar }}

                    </div>

                @endif

            </div>

        @empty

            <p class="text-gray-500">
                No hay partidos programados.
            </p>

        @endforelse

    </x-card>

</div>


{{-- =========================================================
     NOTICIAS / CUMPLEAÑOS
========================================================= --}}

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">


    {{-- NOTICIAS --}}

    <x-card>

        <div class="flex items-center justify-between mb-4">

            <h2 class="text-xl font-bold">
                📢 Noticias
            </h2>


            @if(auth()->user()->tienePermiso('noticias.ver'))

                <a
                    href="{{ route('noticias.index') }}"
                    class="text-sm text-blue-600
                           hover:text-blue-800"
                >
                    Ver todas →
                </a>

            @endif

        </div>


        @forelse($noticias as $noticia)

            <div class="border-b py-3 last:border-b-0">

                <div class="font-semibold">
                    {{ $noticia->titulo }}
                </div>


                <div class="text-sm text-gray-500 mt-1">

                    📅
                    {{ \Carbon\Carbon::parse(
                        $noticia->fecha_publicacion
                    )->format('d/m/Y') }}

                </div>


                @if($noticia->contenido)

                    <div class="text-sm text-gray-600 mt-2">

                        {{
                            \Illuminate\Support\Str::limit(
                                $noticia->contenido,
                                120
                            )
                        }}

                    </div>

                @endif

            </div>

        @empty

            <p class="text-gray-500">
                No hay noticias publicadas.
            </p>

        @endforelse

    </x-card>


    {{-- CUMPLEAÑOS --}}

    <x-card>

        <h2 class="text-xl font-bold mb-4">
            🎂 Próximos cumpleaños
        </h2>


        @forelse($cumpleanios as $jugador)

            <div class="flex items-center justify-between
                        border-b py-3 last:border-b-0">

                <div>

                    <div class="font-semibold">

                        {{ $jugador->nombres }}
                        {{ $jugador->apellidos }}

                    </div>


                    <div class="text-sm text-gray-500">

                        {{ $jugador->fecha_nacimiento->format('d/m') }}

                    </div>

                </div>


                <div class="text-2xl">
                    🎂
                </div>

            </div>

        @empty

            <p class="text-gray-500">
                No hay cumpleaños próximos.
            </p>

        @endforelse

    </x-card>

</div>

@endsection
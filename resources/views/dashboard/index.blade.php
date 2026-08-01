@extends('layouts.app')

@section('titulo', 'Dashboard')

@section('contenido')

<div class="mb-8">

    <h1 class="text-3xl font-bold text-slate-800">
        🏠 Panel de Control
    </h1>

    <p class="text-gray-500 mt-2">
        Bienvenido al sistema de Gestión de Clubes.
    </p>

</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

    <div class="bg-white rounded-xl shadow p-6 border-l-4 border-blue-600">

        <p class="text-gray-500 text-sm">Jugadores</p>

        <h2 class="text-4xl font-bold text-blue-600 mt-2">
            {{ $totalJugadores }}
        </h2>

    </div>

    <div class="bg-white rounded-xl shadow p-6 border-l-4 border-green-600">

        <p class="text-gray-500 text-sm">Jugadores Activos</p>

        <h2 class="text-4xl font-bold text-green-600 mt-2">
            {{ $totalActivos }}
        </h2>

    </div>

    <div class="bg-white rounded-xl shadow p-6 border-l-4 border-orange-500">

        <p class="text-gray-500 text-sm">Equipos</p>

        <h2 class="text-4xl font-bold text-orange-500 mt-2">
            {{ $totalEquipos }}
        </h2>

    </div>

    <div class="bg-white rounded-xl shadow p-6 border-l-4 border-purple-600">

        <p class="text-gray-500 text-sm">Categorías</p>

        <h2 class="text-4xl font-bold text-purple-600 mt-2">
            {{ $totalCategorias }}
        </h2>

    </div>

</div>

@endsection
@extends('layouts.app')

@section('titulo', 'Dashboard')

@section('contenido')

<div class="mb-8">
    <h1 class="text-4xl font-bold text-slate-800">
        Panel de Control
    </h1>

    <p class="text-gray-500 mt-2">
        Bienvenido al sistema de Gestión de Clubes Deportivos.
    </p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

    <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition duration-300 p-6 border border-gray-100">

        <div class="flex items-center justify-between">

            <div>
                <p class="text-sm text-gray-500 uppercase">
                    Jugadores
                </p>

                <h2 class="text-5xl font-bold text-slate-800 mt-3">
                    {{ $totalJugadores }}
                </h2>
            </div>

            <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center text-3xl">
                👥
            </div>

        </div>

    </div>

    <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition duration-300 p-6 border border-gray-100">

        <div class="flex items-center justify-between">

            <div>
                <p class="text-sm text-gray-500 uppercase">
                    Activos
                </p>

                <h2 class="text-5xl font-bold text-green-600 mt-3">
                    {{ $totalActivos }}
                </h2>
            </div>

            <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center text-3xl">
                ✅
            </div>

        </div>

    </div>

    <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition duration-300 p-6 border border-gray-100">

        <div class="flex items-center justify-between">

            <div>
                <p class="text-sm text-gray-500 uppercase">
                    Equipos
                </p>

                <h2 class="text-5xl font-bold text-orange-500 mt-3">
                    {{ $totalEquipos }}
                </h2>
            </div>

            <div class="w-16 h-16 rounded-full bg-orange-100 flex items-center justify-center text-3xl">
                ⚽
            </div>

        </div>

    </div>

    <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition duration-300 p-6 border border-gray-100">

        <div class="flex items-center justify-between">

            <div>
                <p class="text-sm text-gray-500 uppercase">
                    Categorías
                </p>

                <h2 class="text-5xl font-bold text-purple-600 mt-3">
                    {{ $totalCategorias }}
                </h2>
            </div>

            <div class="w-16 h-16 rounded-full bg-purple-100 flex items-center justify-center text-3xl">
                📂
            </div>

        </div>

    </div>

</div>

@endsection
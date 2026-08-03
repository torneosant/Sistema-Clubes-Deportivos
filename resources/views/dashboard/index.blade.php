@extends('layouts.app')

@section('titulo', 'Dashboard')

@section('contenido')

<x-page-header
    title="🏠 Panel de Control"
    subtitle="Bienvenido al sistema de Gestión de Clubes."
/>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

    {{-- Jugadores --}}
    <x-card>
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
    </x-card>

    {{-- Activos --}}
    <x-card>
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
    </x-card>

    {{-- Equipos --}}
    <x-card>
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
    </x-card>

    {{-- Categorías --}}
    <x-card>
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
    </x-card>

</div>

@endsection
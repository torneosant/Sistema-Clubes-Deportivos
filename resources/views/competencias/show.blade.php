@extends('layouts.app')

@section('titulo', $competencia->nombre)

@section('contenido')

<x-page-header
    title="🏆 {{ $competencia->nombre }}"
    subtitle="Información general de la competencia."
/>

<div class="mb-5 flex justify-between items-center">

    <a href="{{ route('competencias.index') }}"
       class="bg-gray-200 hover:bg-gray-300 px-5 py-2 rounded-lg">

        ← Volver

    </a>

    <a href="{{ route('competencias.edit', $competencia) }}"
       class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg">

        ✏️ Editar

    </a>

</div>

<x-card>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <div>
            <p class="text-sm text-gray-500">
                Nombre
            </p>

            <p class="font-semibold text-lg">
                {{ $competencia->nombre }}
            </p>
        </div>

        <div>
            <p class="text-sm text-gray-500">
                Tipo
            </p>

            <p class="font-semibold">

                @switch($competencia->tipo)

                    @case('campeonato')
                        🏆 Campeonato
                        @break

                    @case('festival')
                        🎪 Festival
                        @break

                    @case('evento')
                        🎯 Evento
                        @break

                @endswitch

            </p>
        </div>

        <div>
            <p class="text-sm text-gray-500">
                Categoría principal
            </p>

            <p class="font-semibold">
                {{ $competencia->categoria?->nombre ?? 'Todas / No especificada' }}
            </p>
        </div>

        <div>
            <p class="text-sm text-gray-500">
                Estado
            </p>

            <p class="font-semibold">

                @switch($competencia->estado)

                    @case('proximo')
                        <span class="text-blue-600">Próximo</span>
                        @break

                    @case('en_curso')
                        <span class="text-green-600">En curso</span>
                        @break

                    @case('finalizado')
                        <span class="text-gray-600">Finalizado</span>
                        @break

                    @case('cancelado')
                        <span class="text-red-600">Cancelado</span>
                        @break

                @endswitch

            </p>
        </div>

        <div>
            <p class="text-sm text-gray-500">
                Fecha de inicio
            </p>

            <p class="font-semibold">
                {{ $competencia->fecha_inicio?->format('d/m/Y') ?? '—' }}
            </p>
        </div>

        <div>
            <p class="text-sm text-gray-500">
                Fecha final
            </p>

            <p class="font-semibold">
                {{ $competencia->fecha_fin?->format('d/m/Y') ?? '—' }}
            </p>
        </div>

        <div>
            <p class="text-sm text-gray-500">
                Lugar
            </p>

            <p class="font-semibold">
                {{ $competencia->lugar ?: '—' }}
            </p>
        </div>

    </div>

    @if($competencia->descripcion)

        <div class="mt-8">

            <p class="text-sm text-gray-500">
                Descripción
            </p>

            <p class="mt-2 text-gray-700 whitespace-pre-line">
                {{ $competencia->descripcion }}
            </p>

        </div>

    @endif

</x-card>


{{-- Próximamente: participantes, partidos y estadísticas --}}

<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mt-6">

    <x-card>

        <div class="text-center">

            <div class="text-3xl mb-2">
                👥
            </div>

            <h3 class="font-semibold">
                Participantes
            </h3>

            <p class="text-sm text-gray-500 mt-1">
                Próximamente
            </p>

        </div>

    </x-card>


    <x-card>

        <div class="text-center">

            <div class="text-3xl mb-2">
                ⚽
            </div>

            <h3 class="font-semibold">
                Partidos
            </h3>

            <p class="text-sm text-gray-500 mt-1">
                Próximamente
            </p>

        </div>

    </x-card>


    <x-card>

        <div class="text-center">

            <div class="text-3xl mb-2">
                📊
            </div>

            <h3 class="font-semibold">
                Estadísticas
            </h3>

            <p class="text-sm text-gray-500 mt-1">
                Próximamente
            </p>

        </div>

    </x-card>

</div>

@endsection
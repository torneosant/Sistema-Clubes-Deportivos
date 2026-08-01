@extends('layouts.app')

@section('titulo','Detalle del Entrenamiento')

@section('contenido')

<div class="bg-white rounded-xl shadow p-8">

    <h2 class="text-2xl font-bold mb-8">
        📋 Información del Entrenamiento
    </h2>

    <div class="grid grid-cols-2 gap-6">

        <div>
            <strong>Equipo</strong><br>
            {{ $entrenamiento->equipo->nombre }}
        </div>

        <div>
            <strong>Entrenador</strong><br>
            {{ $entrenamiento->entrenador->nombres }}
            {{ $entrenamiento->entrenador->apellidos }}
        </div>

        <div>
            <strong>Fecha</strong><br>
            {{ \Carbon\Carbon::parse($entrenamiento->fecha)->format('d/m/Y') }}
        </div>

        <div>
            <strong>Horario</strong><br>

            {{ substr($entrenamiento->hora_inicio,0,5) }}

            -

            {{ substr($entrenamiento->hora_fin,0,5) }}

        </div>

        <div>
            <strong>Lugar</strong><br>
            {{ $entrenamiento->lugar }}
        </div>

        <div>
            <strong>Tipo</strong><br>
            {{ $entrenamiento->tipo }}
        </div>

        <div>
            <strong>Estado</strong><br>
            {{ $entrenamiento->estado }}
        </div>

        <div class="col-span-2">
            <strong>Observaciones</strong><br>

            {{ $entrenamiento->observaciones ?: 'Sin observaciones.' }}

        </div>

    </div>

    <div class="mt-8 flex justify-end">

        <a
            href="{{ route('entrenamientos.index') }}"
            class="bg-gray-500 hover:bg-gray-600 text-white px-8 py-3 rounded-lg">

            Volver

        </a>

    </div>

</div>

@endsection
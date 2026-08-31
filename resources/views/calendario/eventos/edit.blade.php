@extends('layouts.app')

@section('titulo', 'Editar evento')

@section('contenido')

<x-page-header
    title="✏️ Editar evento"
    subtitle="Modifica la información del evento."
/>

@if ($errors->any())

    <div class="mb-5 rounded-lg bg-red-50 border border-red-200 p-4">

        <div class="font-semibold text-red-700 mb-2">
            ⚠️ No se pudo guardar el evento
        </div>

        <ul class="list-disc list-inside text-sm text-red-600">

            @foreach ($errors->all() as $error)

                <li>{{ $error }}</li>

            @endforeach

        </ul>

    </div>

@endif


<form
    action="{{ route('calendario.eventos.update', $evento) }}"
    method="POST"
>

<x-card>

    <form
        action="{{ route('calendario.eventos.update', $evento) }}"
        method="POST"
    >

        @csrf
        @method('PUT')


        <div class="space-y-5">


            {{-- TÍTULO --}}

            <div>

                <label class="block text-sm font-semibold mb-2">
                    Título del evento
                </label>

                <input
                    type="text"
                    name="titulo"
                    value="{{ old('titulo', $evento->titulo) }}"
                    required
                    class="w-full rounded-lg border-gray-300
                           focus:border-blue-500
                           focus:ring-blue-500"
                >

            </div>


            {{-- TIPO --}}

            <div>

                <label class="block text-sm font-semibold mb-2">
                    Tipo de evento
                </label>

                <select
                    name="tipo"
                    class="w-full rounded-lg border-gray-300
                           focus:border-blue-500
                           focus:ring-blue-500"
                >

                    @foreach([
                        'General',
                        'Reunión',
                        'Actividad',
                        'Torneo',
                        'Entrega',
                        'Otro'
                    ] as $tipo)

                        <option
                            value="{{ $tipo }}"
                            @selected(
                                old('tipo', $evento->tipo) === $tipo
                            )
                        >
                            {{ $tipo }}
                        </option>

                    @endforeach

                </select>

            </div>


            {{-- DESCRIPCIÓN --}}

            <div>

                <label class="block text-sm font-semibold mb-2">
                    Descripción
                </label>

                <textarea
                    name="descripcion"
                    rows="4"
                    class="w-full rounded-lg border-gray-300
                           focus:border-blue-500
                           focus:ring-blue-500"
                >{{ old('descripcion', $evento->descripcion) }}</textarea>

            </div>


            {{-- FECHA Y HORA --}}

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>

                    <label class="block text-sm font-semibold mb-2">
                        Fecha
                    </label>

                    <input
                        type="date"
                        name="fecha_inicio"
                        value="{{ old(
                            'fecha_inicio',
                            \Carbon\Carbon::parse(
                                $evento->fecha_inicio
                            )->format('Y-m-d')
                        ) }}"
                        required
                        class="w-full rounded-lg border-gray-300
                               focus:border-blue-500
                               focus:ring-blue-500"
                    >

                </div>


                <div>

                    <label class="block text-sm font-semibold mb-2">
                        Hora
                    </label>

                    <input
                        type="time"
                        name="hora"
                        value="{{ old(
                            'hora',
                            $evento->hora
                                ? \Carbon\Carbon::parse(
                                    $evento->hora
                                )->format('H:i')
                                : ''
                        ) }}"
                        class="w-full rounded-lg border-gray-300
                               focus:border-blue-500
                               focus:ring-blue-500"
                    >

                </div>

            </div>


            {{-- LUGAR --}}

            <div>

                <label class="block text-sm font-semibold mb-2">
                    Lugar
                </label>

                <input
                    type="text"
                    name="lugar"
                    value="{{ old('lugar', $evento->lugar) }}"
                    placeholder="Ej. Sede del club"
                    class="w-full rounded-lg border-gray-300
                           focus:border-blue-500
                           focus:ring-blue-500"
                >

            </div>


            {{-- RECURRENCIA --}}

<div class="border rounded-xl p-4">

    <h3 class="font-semibold mb-4">
        🔁 Repetición
    </h3>


    <div class="space-y-3">

        {{-- NO SE REPITE --}}

        <label class="flex items-center gap-3 cursor-pointer">

            <input
                type="radio"
                name="recurrencia"
                value="unico"
                {{ old('recurrencia', $evento->recurrencia) === 'unico' ? 'checked' : '' }}
                class="text-blue-600"
            >

            <div>

                <div class="font-medium">
                    No se repite
                </div>

                <div class="text-xs text-gray-500">
                    Evento de una sola fecha.
                </div>

            </div>

        </label>


        {{-- MENSUAL --}}

        <label class="flex items-center gap-3 cursor-pointer">

            <input
                type="radio"
                name="recurrencia"
                value="mensual"
                {{ old('recurrencia', $evento->recurrencia) === 'mensual' ? 'checked' : '' }}
                class="text-blue-600"
            >

            <div>

                <div class="font-medium">
                    Mensualmente
                </div>

                <div class="text-xs text-gray-500">
                    Se repite el mismo día cada mes.
                </div>

            </div>

        </label>


        {{-- MESES SELECCIONADOS --}}

        <label class="flex items-center gap-3 cursor-pointer">

            <input
                type="radio"
                name="recurrencia"
                value="meses"
                {{ old('recurrencia', $evento->recurrencia) === 'meses' ? 'checked' : '' }}
                class="text-blue-600"
            >

            <div>

                <div class="font-medium">
                    Meses seleccionados
                </div>

                <div class="text-xs text-gray-500">
                    Se repite únicamente en los meses seleccionados.
                </div>

            </div>

        </label>

    </div>


    {{-- INFORMACIÓN ACTUAL --}}

    @if($evento->recurrencia === 'mensual')

        <div class="mt-4 p-3 rounded-lg bg-blue-50 text-sm text-blue-700">

            🔁 Este evento se repite mensualmente.

            @if($evento->dia_recurrencia)

                Día:
                <strong>
                    {{ $evento->dia_recurrencia }}
                </strong>

            @endif

        </div>

    @elseif($evento->recurrencia === 'meses')

        <div class="mt-4 p-3 rounded-lg bg-blue-50 text-sm text-blue-700">

            🔁 Este evento utiliza meses seleccionados.

        </div>

    @else

        <div class="mt-4 p-3 rounded-lg bg-gray-50 text-sm text-gray-600">

            📌 Este evento no se repite.

        </div>

    @endif

</div> 

            {{-- BOTONES --}}

            <div
                class="flex
                       flex-col
                       sm:flex-row
                       justify-between
                       gap-3
                       pt-4"
            >

                <a
                    href="{{ route('calendario.index') }}"
                    class="inline-flex
                           items-center
                           justify-center
                           px-4 py-2
                           rounded-lg
                           bg-gray-200
                           hover:bg-gray-300
                           text-gray-700
                           font-semibold"
                >
                    ← Volver al calendario
                </a>


                <x-button
                    type="submit"
                    color="blue"
                >
                    💾 Guardar cambios
                </x-button>

            </div>

        </div>
<input
    type="hidden"
    name="activo"
    value="1"
>
    </form>

</x-card>

@endsection
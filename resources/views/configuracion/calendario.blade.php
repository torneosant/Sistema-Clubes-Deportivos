@extends('layouts.app')

@section('titulo', 'Configuración del Calendario')

@section('contenido')

<x-page-header
    title="📅 Calendario"
    subtitle="Configura qué información deseas mostrar en el calendario del club."
/>

<form
    action="{{ route('configuracion.calendario.update') }}"
    method="POST"
>
    @csrf
    @method('PUT')

    <x-card>

        <h2 class="text-xl font-bold mb-2">
            Información visible
        </h2>

        <p class="text-sm text-gray-500 mb-6">
            Selecciona qué tipos de información estarán disponibles
            en el calendario del club.
        </p>

        <div class="space-y-4">

            {{-- PARTIDOS --}}
            <label class="flex items-center gap-3 border rounded-xl p-4 cursor-pointer hover:bg-gray-50">

                <input
                    type="checkbox"
                    name="calendario_partidos"
                    value="1"
                    class="w-5 h-5"
                    @checked($configuracion->calendario_partidos)
                >

                <div>
                    <div class="font-semibold">
                        ⚽ Partidos
                    </div>

                    <div class="text-sm text-gray-500">
                        Mostrar los partidos programados del club.
                    </div>
                </div>

            </label>


            {{-- ENTRENAMIENTOS --}}
            <label class="flex items-center gap-3 border rounded-xl p-4 cursor-pointer hover:bg-gray-50">

                <input
                    type="checkbox"
                    name="calendario_entrenamientos"
                    value="1"
                    class="w-5 h-5"
                    @checked($configuracion->calendario_entrenamientos)
                >

                <div>
                    <div class="font-semibold">
                        🏃 Entrenamientos
                    </div>

                    <div class="text-sm text-gray-500">
                        Mostrar los entrenamientos programados.
                    </div>
                </div>

            </label>


            {{-- CUMPLEAÑOS --}}
            <label class="flex items-center gap-3 border rounded-xl p-4 cursor-pointer hover:bg-gray-50">

                <input
                    type="checkbox"
                    name="calendario_cumpleanos"
                    value="1"
                    class="w-5 h-5"
                    @checked($configuracion->calendario_cumpleanos)
                >

                <div>
                    <div class="font-semibold">
                        🎂 Cumpleaños
                    </div>

                    <div class="text-sm text-gray-500">
                        Mostrar los cumpleaños de los jugadores.
                    </div>
                </div>

            </label>


            {{-- EVENTOS --}}
            <label class="flex items-center gap-3 border rounded-xl p-4 cursor-pointer hover:bg-gray-50">

                <input
                    type="checkbox"
                    name="calendario_eventos"
                    value="1"
                    class="w-5 h-5"
                    @checked($configuracion->calendario_eventos)
                >

                <div>
                    <div class="font-semibold">
                        📌 Eventos
                    </div>

                    <div class="text-sm text-gray-500">
                        Mostrar los eventos generales del club.
                    </div>
                </div>

            </label>

        </div>


        <div class="flex justify-end mt-6">

            <x-button
                type="submit"
                color="blue"
            >
                💾 Guardar configuración
            </x-button>

        </div>

    </x-card>

</form>

@endsection
@extends('layouts.app')

@section('titulo')
📊 Estadísticas del Partido
@endsection

@section('contenido')

{{-- ==========================================================
     CABECERA DEL PARTIDO
=========================================================== --}}

<div class="bg-white rounded-xl shadow-lg mb-6">

    <div class="bg-slate-800 text-white p-6 rounded-t-xl">

        <h2 class="text-2xl font-bold">
            {{ $partido->competencia ?: 'Amistoso' }}
        </h2>

    </div>

    <div class="p-6">

        <div class="flex justify-between items-center">

            {{-- EQUIPO --}}

            <div class="text-xl font-bold">
                {{ $partido->equipo->nombre }}
            </div>


            {{-- RESULTADO --}}

            <div class="text-center">

                <div class="text-5xl font-extrabold text-slate-700">

                    {{ $partido->goles_favor ?? '-' }}

                    <span class="mx-3 text-gray-400">
                        :
                    </span>

                    {{ $partido->goles_contra ?? '-' }}

                </div>

                <div class="text-sm text-gray-500">
                    Resultado
                </div>

            </div>


            {{-- RIVAL --}}

            <div class="text-xl font-bold">
                {{ $partido->rival }}
            </div>

        </div>


        <hr class="my-6">


        {{-- INFORMACIÓN DEL PARTIDO --}}

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

                {{ $partido->lugar ?: '—' }}

            </div>


            <div>

                <strong>Categoría:</strong><br>

                {{ $partido->categoria?->nombre ?? '—' }}

            </div>


            <div>

                <strong>Condición:</strong><br>

                {{ $partido->condicion ?: '—' }}

            </div>


            <div>

                <strong>Estado:</strong><br>

                {{ $partido->estado }}

            </div>

        </div>

    </div>

</div>



{{-- ==========================================================
     ESTADÍSTICAS DE LAS JUGADORAS
=========================================================== --}}

<div class="bg-white rounded-xl shadow-lg">


    <form
        method="POST"
        action="{{ route('partidos.estadisticas.store', $partido) }}"
        id="formEstadisticas"
    >

        @csrf


        <div class="overflow-x-auto">

            <table class="min-w-full text-sm">


                {{-- ==================================================
                     ENCABEZADOS
                =================================================== --}}

                <thead class="bg-gray-100">

                    <tr>

                        <th class="p-3 text-left">
                            Jugadora
                        </th>


                        <th class="text-center px-3 whitespace-nowrap">
                            11 inicialista
                        </th>


                        <th class="text-center px-3">
                            Participación
                        </th>


                        <th class="text-center px-3">
                            Min
                        </th>


                        <th class="text-center px-3">
                            ⚽
                        </th>


                        <th class="text-center px-3">
                            🎯
                        </th>


                        <th class="text-center px-3">
                            🟨
                        </th>


                        <th class="text-center px-3">
                            🟥
                        </th>


                        <th class="text-center px-3">
                            ⭐
                        </th>

                    </tr>

                </thead>



                {{-- ==================================================
                     JUGADORAS
                =================================================== --}}

                <tbody>

                @forelse($jugadores as $jugador)

                    @php

                        $e = $estadisticas[$jugador->id] ?? null;

                        $esTitular = $e
                            ? (bool) $e->titular
                            : false;

                        $participacion = $e
                            ? $e->participacion
                            : 'No jugó';

                    @endphp


                    <tr
                        class="border-b hover:bg-gray-50"
                        data-jugadora="{{ $jugador->id }}"
                    >


                        {{-- ==================================================
                             JUGADORA
                        =================================================== --}}

                        <td class="p-3">

                            <div class="font-semibold">
                                {{ $jugador->apellidos }}
                            </div>

                            <div class="text-gray-500 text-xs">
                                {{ $jugador->nombres }}
                            </div>

                        </td>



                        {{-- ==================================================
                             11 INICIALISTA
                        =================================================== --}}

                        <td class="text-center px-3">

                            <label
                                class="inline-flex items-center gap-2 cursor-pointer"
                            >

                                <input
                                    type="checkbox"
                                    name="titular[{{ $jugador->id }}]"
                                    value="1"
                                    class="titular-checkbox w-5 h-5"
                                    data-id="{{ $jugador->id }}"
                                    {{ $esTitular ? 'checked' : '' }}
                                >

                                <span class="text-xs text-gray-600">
                                    Sí
                                </span>

                            </label>

                        </td>



                        {{-- ==================================================
                             PARTICIPACIÓN
                        =================================================== --}}

                        <td class="text-center px-3">

                            <select
                                name="participacion[{{ $jugador->id }}]"
                                class="participacion-select border rounded px-2 py-1"
                                data-id="{{ $jugador->id }}"
                            >

                                {{-- NO JUGÓ --}}

                                <option
                                    value="No jugó"
                                    {{ (!$esTitular && $participacion === 'No jugó') ? 'selected' : '' }}
                                >
                                    No jugó
                                </option>


                                {{-- SUPLENTE --}}

                                <option
                                    value="Suplente"
                                    {{ (!$esTitular && $participacion === 'Suplente') ? 'selected' : '' }}
                                >
                                    Suplente
                                </option>


                                {{-- TITULAR --}}

                                <option
                                    value="Titular"
                                    {{ $esTitular ? 'selected' : '' }}
                                >
                                    Titular
                                </option>

                            </select>


                            @if($esTitular)

                                <div class="text-xs text-green-600 mt-1">
                                    11 inicial
                                </div>

                            @endif

                        </td>



                        {{-- ==================================================
                             MINUTOS
                        =================================================== --}}

                        <td class="text-center px-3">

                            <input
                                type="number"
                                min="0"
                                max="120"
                                class="w-16 border rounded text-center stat-input"
                                name="minutos[{{ $jugador->id }}]"
                                value="{{ $e->minutos ?? 0 }}"
                            >

                        </td>



                        {{-- ==================================================
                             GOLES
                        =================================================== --}}

                        <td class="text-center px-3">

                            <input
                                type="number"
                                min="0"
                                class="w-14 border rounded text-center stat-input"
                                name="goles[{{ $jugador->id }}]"
                                value="{{ $e->goles ?? 0 }}"
                            >

                        </td>



                        {{-- ==================================================
                             ASISTENCIAS
                        =================================================== --}}

                        <td class="text-center px-3">

                            <input
                                type="number"
                                min="0"
                                class="w-14 border rounded text-center stat-input"
                                name="asistencias[{{ $jugador->id }}]"
                                value="{{ $e->asistencias ?? 0 }}"
                            >

                        </td>



                        {{-- ==================================================
                             AMARILLAS
                        =================================================== --}}

                        <td class="text-center px-3">

                            <input
                                type="number"
                                min="0"
                                class="w-14 border rounded text-center stat-input"
                                name="amarillas[{{ $jugador->id }}]"
                                value="{{ $e->amarillas ?? 0 }}"
                            >

                        </td>



                        {{-- ==================================================
                             ROJAS
                        =================================================== --}}

                        <td class="text-center px-3">

                            <input
                                type="number"
                                min="0"
                                class="w-14 border rounded text-center stat-input"
                                name="rojas[{{ $jugador->id }}]"
                                value="{{ $e->rojas ?? 0 }}"
                            >

                        </td>



                        {{-- ==================================================
                             FIGURA
                        =================================================== --}}

                        <td class="text-center px-3">

                            <input
                                type="checkbox"
                                name="figura[{{ $jugador->id }}]"
                                value="1"
                                class="w-5 h-5 figura-checkbox"
                                {{ $e && $e->figura ? 'checked' : '' }}
                            >

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="9"
                            class="text-center py-10 text-gray-500"
                        >

                            No hay jugadoras disponibles para este partido.

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>



        {{-- ==========================================================
             BOTONES
        =========================================================== --}}

        <div class="flex justify-end gap-3 p-6 border-t">

            <a
                href="{{ route('partidos.index') }}"
                class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg"
            >

                Cancelar

            </a>


            <button
                type="submit"
                class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg"
            >

                💾 Guardar Estadísticas

            </button>

        </div>

    </form>

</div>



{{-- ==========================================================
     JAVASCRIPT
=========================================================== --}}

<script>

document.addEventListener('DOMContentLoaded', function () {


    /*
    |--------------------------------------------------------------------------
    | Actualizar una jugadora
    |--------------------------------------------------------------------------
    */

    function actualizarJugadora(id) {

        const titular =
            document.querySelector(
                '.titular-checkbox[data-id="' + id + '"]'
            );


        const participacion =
            document.querySelector(
                '.participacion-select[data-id="' + id + '"]'
            );


        const fila =
            document.querySelector(
                'tr[data-jugadora="' + id + '"]'
            );


        if (
            !titular ||
            !participacion ||
            !fila
        ) {

            return;

        }


        const inputs =
            fila.querySelectorAll('.stat-input');


        const figura =
            fila.querySelector('.figura-checkbox');



        /*
        |--------------------------------------------------------------------------
        | TITULAR
        |--------------------------------------------------------------------------
        */

        if (titular.checked) {


            /*
            | El titular siempre tiene participación "Titular".
            */

            participacion.value = 'Titular';


            /*
            | No necesitamos modificar manualmente
            | el valor enviado por el select.
            */

            participacion.disabled = false;


            /*
            | Puede registrar estadísticas.
            */

            inputs.forEach(function (input) {

                input.disabled = false;

            });


            if (figura) {

                figura.disabled = false;

            }


            return;

        }



        /*
        |--------------------------------------------------------------------------
        | NO TITULAR
        |--------------------------------------------------------------------------
        */

        /*
        | Si estaba en "Titular", cambiamos automáticamente
        | a "No jugó".
        */

        if (participacion.value === 'Titular') {

            participacion.value = 'No jugó';

        }


        participacion.disabled = false;



        /*
        |--------------------------------------------------------------------------
        | NO JUGÓ
        |--------------------------------------------------------------------------
        */

        if (
            participacion.value === 'No jugó'
        ) {


            inputs.forEach(function (input) {

                input.value = 0;

                input.disabled = true;

            });


            if (figura) {

                figura.checked = false;

                figura.disabled = true;

            }


        } else {


            /*
            |--------------------------------------------------------------------------
            | SUPLENTE
            |--------------------------------------------------------------------------
            */

            inputs.forEach(function (input) {

                input.disabled = false;

            });


            if (figura) {

                figura.disabled = false;

            }

        }

    }



    /*
    |--------------------------------------------------------------------------
    | Cambio de titular
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll('.titular-checkbox')
        .forEach(function (checkbox) {

            checkbox.addEventListener(
                'change',
                function () {

                    actualizarJugadora(
                        this.dataset.id
                    );

                }
            );

        });



    /*
    |--------------------------------------------------------------------------
    | Cambio de participación
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll('.participacion-select')
        .forEach(function (select) {

            select.addEventListener(
                'change',
                function () {

                    actualizarJugadora(
                        this.dataset.id
                    );

                }
            );

        });



    /*
    |--------------------------------------------------------------------------
    | Estado inicial
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll('.titular-checkbox')
        .forEach(function (checkbox) {

            actualizarJugadora(
                checkbox.dataset.id
            );

        });

});

</script>

@endsection
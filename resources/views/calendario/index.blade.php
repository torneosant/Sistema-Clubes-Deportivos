@extends('layouts.app')

@section('titulo', 'Calendario')

@section('contenido')

<x-page-header
    title="📅 Calendario"
    subtitle="Agenda del club."
/>


{{-- ========================================================= --}}
{{-- CALENDARIO --}}
{{-- ========================================================= --}}

<x-card>

    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-5">

        <div>

            <h2 class="text-xl font-bold">
                Agenda deportiva
            </h2>

            <p class="text-sm text-gray-500">
                Consulta los partidos, entrenamientos,
                cumpleaños y eventos del club.
            </p>

        </div>


        {{-- LEYENDA --}}

        <div class="flex flex-wrap items-center gap-4 text-xs">

            @if($mostrarPartidos)

                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-blue-600"></span>
                    Partidos
                </div>

            @endif


            @if($mostrarEntrenamientos)

                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-green-600"></span>
                    Entrenamientos
                </div>

            @endif


            @if($mostrarCumpleanos)

                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-purple-600"></span>
                    Cumpleaños
                </div>

            @endif


            @if($mostrarEventos)

                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-orange-500"></span>
                    Eventos
                </div>

            @endif

        </div>

    </div>


    {{-- CALENDARIO --}}

    <div
        id="calendar"
        class="calendar-moderno"
    ></div>

</x-card>



{{-- ========================================================= --}}
{{-- PRÓXIMOS EVENTOS --}}
{{-- ========================================================= --}}

<x-card>

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-5">

        <div>

            <h2 class="text-xl font-bold">
                📌 Próximos eventos
            </h2>

            <p class="text-sm text-gray-500">
                Las próximas actividades programadas del club.
            </p>

        </div>


        {{-- NUEVO EVENTO --}}

        @if($puedeConfigurarCalendario)

            <x-button
                type="button"
                color="blue"
                onclick="abrirModalEvento()"
            >
                ➕ Nuevo evento
            </x-button>

        @endif

    </div>


    {{-- LISTADO --}}

    <div class="space-y-3">

        @forelse($proximosEventos as $evento)

            @php

                $tipo =
                    $evento['extendedProps']['tipo'] ?? '';

            @endphp


            <div
                class="border rounded-xl p-4
                       flex flex-col lg:flex-row
                       lg:items-center
                       lg:justify-between
                       gap-4
                       hover:bg-gray-50
                       transition"
            >


                {{-- INFORMACIÓN --}}

                <div class="flex items-start gap-4">


                    {{-- ICONO --}}

                    <div
                        class="w-11 h-11
                               flex-shrink-0
                               rounded-xl
                               flex items-center
                               justify-center
                               text-lg

                               @if($tipo === 'Partido')
                                   bg-blue-100
                               @elseif($tipo === 'Entrenamiento')
                                   bg-green-100
                               @elseif($tipo === 'Cumpleaños')
                                   bg-purple-100
                               @else
                                   bg-orange-100
                               @endif"
                    >

                        @if($tipo === 'Partido')

                            ⚽

                        @elseif($tipo === 'Entrenamiento')

                            🏃

                        @elseif($tipo === 'Cumpleaños')

                            🎂

                        @else

                            📌

                        @endif

                    </div>


                    {{-- DATOS --}}

                    <div>

                        <div class="font-semibold text-gray-900">
                            {{ $evento['title'] }}
                        </div>


                        <div class="text-sm text-gray-500 mt-1">

                            📅

                            {{
                                \Carbon\Carbon::parse(
                                    $evento['start']
                                )->format('d/m/Y')
                            }}


                            @if(
                                !empty(
                                    $evento['extendedProps']['hora']
                                )
                            )

                                <span class="mx-1">
                                    ·
                                </span>

                                ⏰

                                {{
                                    \Carbon\Carbon::parse(
                                        $evento['extendedProps']['hora']
                                    )->format('H:i')
                                }}

                            @endif

                        </div>


                        @if(
                            !empty(
                                $evento['extendedProps']['lugar']
                            )
                        )

                            <div class="text-sm text-gray-500 mt-1">

                                📍
                                {{ $evento['extendedProps']['lugar'] }}

                            </div>

                        @endif

                    </div>

                </div>



                {{-- ================================================= --}}
                {{-- TIPO + ACCIONES --}}
                {{-- ================================================= --}}

                <div
                    class="flex flex-col sm:flex-row
                           sm:items-center
                           gap-3"
                >


                    {{-- TIPO --}}

                    @if($tipo === 'Partido')

                        <span
                            class="inline-flex
                                   items-center
                                   px-3 py-1
                                   rounded-full
                                   text-xs
                                   font-semibold
                                   bg-blue-100
                                   text-blue-700"
                        >
                            ⚽ Partido
                        </span>


                    @elseif($tipo === 'Entrenamiento')

                        <span
                            class="inline-flex
                                   items-center
                                   px-3 py-1
                                   rounded-full
                                   text-xs
                                   font-semibold
                                   bg-green-100
                                   text-green-700"
                        >
                            🏃 Entrenamiento
                        </span>


                    @elseif($tipo === 'Cumpleaños')

                        <span
                            class="inline-flex
                                   items-center
                                   px-3 py-1
                                   rounded-full
                                   text-xs
                                   font-semibold
                                   bg-purple-100
                                   text-purple-700"
                        >
                            🎂 Cumpleaños
                        </span>


                    @elseif($tipo === 'Evento')

                        <span
                            class="inline-flex
                                   items-center
                                   px-3 py-1
                                   rounded-full
                                   text-xs
                                   font-semibold
                                   bg-orange-100
                                   text-orange-700"
                        >
                            📌 Evento
                        </span>

                    @endif



                    {{-- ACCIONES DE EVENTOS --}}

                    @if(
                        $tipo === 'Evento'
                        &&
                        $puedeConfigurarCalendario
                    )

                        <div class="flex items-center gap-2">


                            {{-- EDITAR --}}

                            <a
                                href="{{ route(
                                    'calendario.eventos.edit',
                                    $evento['extendedProps']['evento_id']
                                ) }}"
                                class="inline-flex
                                       items-center
                                       justify-center
                                       gap-1
                                       px-3
                                       py-1.5
                                       rounded-lg
                                       bg-blue-50
                                       hover:bg-blue-100
                                       text-blue-700
                                       text-xs
                                       font-semibold
                                       transition"
                                title="Editar evento"
                            >
                                ✏️ Editar
                            </a>


                            {{-- ELIMINAR --}}

                            <form
                                action="{{ route(
                                    'calendario.eventos.destroy',
                                    $evento['extendedProps']['evento_id']
                                ) }}"
                                method="POST"
                                class="inline formulario-eliminar"
                            >

                                @csrf
                                @method('DELETE')

                                <x-button
                                    type="submit"
                                    color="red"
                                    icon
                                    title="Eliminar"
                                >
                                    🗑️
                                </x-button>

                            </form>

                        </div>

                    @endif

                </div>

            </div>


        @empty


            {{-- SIN EVENTOS --}}

            <div
                class="text-center
                       py-10
                       border
                       border-dashed
                       rounded-xl"
            >

                <div class="text-4xl mb-3">
                    📅
                </div>

                <p class="font-semibold text-gray-700">
                    No hay próximos eventos
                </p>

                <p class="text-sm text-gray-500 mt-1">
                    Cuando programes actividades aparecerán aquí.
                </p>

            </div>

        @endforelse

    </div>

</x-card>



{{-- ========================================================= --}}
{{-- CONFIGURACIÓN DEL CALENDARIO --}}
{{-- ========================================================= --}}

@if($puedeConfigurarCalendario)

    <x-card>

        <div class="mb-5">

            <h2 class="text-xl font-bold">
                ⚙️ Configuración del calendario
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Selecciona qué información deseas mostrar
                en el calendario del club.
            </p>

        </div>


        <form
            action="{{ route(
                'calendario.configuracion.update'
            ) }}"
            method="POST"
        >

            @csrf
            @method('PUT')


            <div
                class="grid
                       grid-cols-1
                       md:grid-cols-2
                       gap-3"
            >


                {{-- PARTIDOS --}}

                <label
                    class="flex items-center gap-3
                           border rounded-lg
                           p-4 cursor-pointer
                           hover:bg-gray-50"
                >

                    <input
                        type="checkbox"
                        name="calendario_partidos"
                        value="1"
                        {{ $mostrarPartidos ? 'checked' : '' }}
                        class="rounded text-blue-600"
                    >

                    <div>

                        <div class="font-semibold">
                            ⚽ Partidos
                        </div>

                        <div class="text-xs text-gray-500">
                            Mostrar los partidos programados.
                        </div>

                    </div>

                </label>



                {{-- ENTRENAMIENTOS --}}

                <label
                    class="flex items-center gap-3
                           border rounded-lg
                           p-4 cursor-pointer
                           hover:bg-gray-50"
                >

                    <input
                        type="checkbox"
                        name="calendario_entrenamientos"
                        value="1"
                        {{ $mostrarEntrenamientos ? 'checked' : '' }}
                        class="rounded text-green-600"
                    >

                    <div>

                        <div class="font-semibold">
                            🏃 Entrenamientos
                        </div>

                        <div class="text-xs text-gray-500">
                            Mostrar los entrenamientos programados.
                        </div>

                    </div>

                </label>



                {{-- CUMPLEAÑOS --}}

                <label
                    class="flex items-center gap-3
                           border rounded-lg
                           p-4 cursor-pointer
                           hover:bg-gray-50"
                >

                    <input
                        type="checkbox"
                        name="calendario_cumpleanos"
                        value="1"
                        {{ $mostrarCumpleanos ? 'checked' : '' }}
                        class="rounded text-purple-600"
                    >

                    <div>

                        <div class="font-semibold">
                            🎂 Cumpleaños
                        </div>

                        <div class="text-xs text-gray-500">
                            Mostrar los cumpleaños de los jugadores.
                        </div>

                    </div>

                </label>



                {{-- EVENTOS --}}

                <label
                    class="flex items-center gap-3
                           border rounded-lg
                           p-4 cursor-pointer
                           hover:bg-gray-50"
                >

                    <input
                        type="checkbox"
                        name="calendario_eventos"
                        value="1"
                        {{ $mostrarEventos ? 'checked' : '' }}
                        class="rounded text-orange-500"
                    >

                    <div>

                        <div class="font-semibold">
                            📌 Eventos
                        </div>

                        <div class="text-xs text-gray-500">
                            Mostrar los eventos generales del club.
                        </div>

                    </div>

                </label>

            </div>


            <div class="flex justify-end mt-5">

                <x-button
                    type="submit"
                    color="blue"
                >
                    💾 Guardar configuración
                </x-button>

            </div>

        </form>

    </x-card>

@endif



{{-- ========================================================= --}}
{{-- MODAL NUEVO EVENTO --}}
{{-- ========================================================= --}}

@if($puedeConfigurarCalendario)

<div
    id="modalEvento"
    class="fixed inset-0 z-50 hidden"
>


    {{-- FONDO --}}

    <div
        class="absolute inset-0 bg-black/50"
        onclick="cerrarModalEvento()"
    ></div>


    {{-- CONTENEDOR --}}

    <div
        class="relative
               min-h-screen
               flex items-center
               justify-center
               p-4"
    >

        <div
            class="bg-white
                   w-full
                   max-w-2xl
                   max-h-[90vh]
                   rounded-2xl
                   shadow-2xl
                   overflow-hidden
                   flex flex-col"
        >


            {{-- CABECERA --}}

            <div
                class="px-6 py-4
                       border-b
                       flex items-center
                       justify-between"
            >

                <div>

                    <h2 class="text-xl font-bold">
                        📌 Nuevo evento
                    </h2>

                    <p class="text-sm text-gray-500">
                        Programa una actividad del club.
                    </p>

                </div>


                <button
                    type="button"
                    onclick="cerrarModalEvento()"
                    class="w-9 h-9
                           rounded-lg
                           hover:bg-gray-100
                           text-gray-500
                           text-xl"
                >
                    ×
                </button>

            </div>



            {{-- FORMULARIO --}}

            <form
                action="{{ route(
                    'calendario.eventos.store'
                ) }}"
                method="POST"
                class="flex flex-col overflow-hidden"
            >

                @csrf


                <div
                    class="p-6
                           space-y-5
                           overflow-y-auto"
                >


                    {{-- TÍTULO --}}

                    <div>

                        <label
                            class="block
                                   text-sm
                                   font-semibold
                                   mb-2"
                        >
                            Título del evento
                        </label>

                        <input
                            type="text"
                            name="titulo"
                            required
                            placeholder="Ej. Reunión de padres"
                            class="w-full
                                   rounded-lg
                                   border-gray-300
                                   focus:border-blue-500
                                   focus:ring-blue-500"
                        >

                    </div>



                    {{-- TIPO --}}

                    <div>

                        <label
                            class="block
                                   text-sm
                                   font-semibold
                                   mb-2"
                        >
                            Tipo de evento
                        </label>

                        <select
                            name="tipo"
                            class="w-full
                                   rounded-lg
                                   border-gray-300
                                   focus:border-blue-500
                                   focus:ring-blue-500"
                        >

                            <option value="General">
                                📌 General
                            </option>

                            <option value="Reunión">
                                👥 Reunión
                            </option>

                            <option value="Actividad">
                                🏃 Actividad
                            </option>

                            <option value="Torneo">
                                🏆 Torneo
                            </option>

                            <option value="Entrega">
                                🎽 Entrega
                            </option>

                            <option value="Otro">
                                📋 Otro
                            </option>

                        </select>

                    </div>



                    {{-- DESCRIPCIÓN --}}

                    <div>

                        <label
                            class="block
                                   text-sm
                                   font-semibold
                                   mb-2"
                        >
                            Descripción
                        </label>

                        <textarea
                            name="descripcion"
                            rows="3"
                            placeholder="Información adicional..."
                            class="w-full
                                   rounded-lg
                                   border-gray-300
                                   focus:border-blue-500
                                   focus:ring-blue-500"
                        ></textarea>

                    </div>



                    {{-- FECHA Y HORA --}}

                    <div
                        class="grid
                               grid-cols-1
                               md:grid-cols-2
                               gap-4"
                    >

                        <div>

                            <label
                                class="block
                                       text-sm
                                       font-semibold
                                       mb-2"
                            >
                                Fecha
                            </label>

                            <input
                                type="date"
                                name="fecha_inicio"
                                required
                                value="{{ now()->format('Y-m-d') }}"
                                class="w-full
                                       rounded-lg
                                       border-gray-300
                                       focus:border-blue-500
                                       focus:ring-blue-500"
                            >

                        </div>


                        <div>

                            <label
                                class="block
                                       text-sm
                                       font-semibold
                                       mb-2"
                            >
                                Hora
                            </label>

                            <input
                                type="time"
                                name="hora"
                                class="w-full
                                       rounded-lg
                                       border-gray-300
                                       focus:border-blue-500
                                       focus:ring-blue-500"
                            >

                        </div>

                    </div>



                    {{-- LUGAR --}}

                    <div>

                        <label
                            class="block
                                   text-sm
                                   font-semibold
                                   mb-2"
                        >
                            Lugar
                        </label>

                        <input
                            type="text"
                            name="lugar"
                            placeholder="Ej. Sede del club"
                            class="w-full
                                   rounded-lg
                                   border-gray-300
                                   focus:border-blue-500
                                   focus:ring-blue-500"
                        >

                    </div>



                    {{-- RECURRENCIA --}}

                    <div
                        class="border
                               rounded-xl
                               p-4"
                    >

                        <h3 class="font-semibold mb-4">
                            🔁 Repetición
                        </h3>


                        <div class="space-y-3">


                            <label
                                class="flex items-center gap-3 cursor-pointer"
                            >

                                <input
                                    type="radio"
                                    name="recurrencia"
                                    value="unico"
                                    checked
                                    onchange="cambiarRecurrencia()"
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



                            <label
                                class="flex items-center gap-3 cursor-pointer"
                            >

                                <input
                                    type="radio"
                                    name="recurrencia"
                                    value="mensual"
                                    onchange="cambiarRecurrencia()"
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



                            <label
                                class="flex items-center gap-3 cursor-pointer"
                            >

                                <input
                                    type="radio"
                                    name="recurrencia"
                                    value="meses"
                                    onchange="cambiarRecurrencia()"
                                    class="text-blue-600"
                                >

                                <div>

                                    <div class="font-medium">
                                        Meses seleccionados
                                    </div>

                                    <div class="text-xs text-gray-500">
                                        Selecciona los meses en los que se realizará.
                                    </div>

                                </div>

                            </label>

                        </div>



                        {{-- OPCIONES --}}

                        <div
                            id="opcionesRecurrencia"
                            class="hidden mt-5 space-y-4"
                        >


                            {{-- DÍA --}}

                            <div>

                                <label
                                    class="block
                                           text-sm
                                           font-semibold
                                           mb-2"
                                >
                                    Día del mes
                                </label>

                                <input
                                    type="number"
                                    name="dia_recurrencia"
                                    min="1"
                                    max="31"
                                    placeholder="Ej. 5"
                                    class="w-full
                                           rounded-lg
                                           border-gray-300
                                           focus:border-blue-500
                                           focus:ring-blue-500"
                                >

                                <p class="text-xs text-gray-500 mt-1">
                                    Si el mes no tiene ese día,
                                    se utilizará el último día disponible.
                                </p>

                            </div>



                            {{-- FECHA FINAL --}}

                            <div>

                                <label
                                    class="block
                                           text-sm
                                           font-semibold
                                           mb-2"
                                >
                                    Repetir hasta
                                </label>

                                <input
                                    type="date"
                                    name="fecha_fin_recurrencia"
                                    class="w-full
                                           rounded-lg
                                           border-gray-300
                                           focus:border-blue-500
                                           focus:ring-blue-500"
                                >

                            </div>



                            {{-- MESES --}}

                            <div
                                id="seleccionMeses"
                                class="hidden"
                            >

                                <label
                                    class="block
                                           text-sm
                                           font-semibold
                                           mb-3"
                                >
                                    Selecciona los meses
                                </label>


                                @php

                                    $meses = [
                                        1 => 'Enero',
                                        2 => 'Febrero',
                                        3 => 'Marzo',
                                        4 => 'Abril',
                                        5 => 'Mayo',
                                        6 => 'Junio',
                                        7 => 'Julio',
                                        8 => 'Agosto',
                                        9 => 'Septiembre',
                                        10 => 'Octubre',
                                        11 => 'Noviembre',
                                        12 => 'Diciembre',
                                    ];

                                @endphp


                                <div
                                    class="grid
                                           grid-cols-2
                                           md:grid-cols-4
                                           gap-2"
                                >

                                    @foreach($meses as $numero => $nombre)

                                        <label
                                            class="flex items-center
                                                   gap-2
                                                   p-2
                                                   rounded-lg
                                                   border
                                                   cursor-pointer
                                                   hover:bg-gray-50"
                                        >

                                            <input
                                                type="checkbox"
                                                name="meses_recurrencia[]"
                                                value="{{ $numero }}"
                                                class="rounded
                                                       text-blue-600"
                                            >

                                            <span class="text-sm">
                                                {{ $nombre }}
                                            </span>

                                        </label>

                                    @endforeach

                                </div>

                            </div>

                        </div>

                    </div>

                </div>



                {{-- BOTONES --}}

                <div
                    class="px-6 py-4
                           bg-gray-50
                           border-t
                           flex justify-end
                           gap-3"
                >

                    <button
                        type="button"
                        onclick="cerrarModalEvento()"
                        class="px-4 py-2
                               rounded-lg
                               bg-gray-200
                               hover:bg-gray-300
                               text-gray-700
                               font-semibold"
                    >
                        Cancelar
                    </button>


                    <x-button
                        type="submit"
                        color="blue"
                    >
                        💾 Guardar evento
                    </x-button>

                </div>

            </form>

        </div>

    </div>

</div>

@endif



{{-- ========================================================= --}}
{{-- DATOS DEL CALENDARIO --}}
{{-- ========================================================= --}}

<script>

window.eventosCalendario =
    @json($eventos);

window.anioTrabajo =
    @json($anio ?? date('Y'));

</script>



{{-- ========================================================= --}}
{{-- JAVASCRIPT --}}
{{-- ========================================================= --}}

@if($puedeConfigurarCalendario)

<script>

function abrirModalEvento()
{
    const modal =
        document.getElementById('modalEvento');

    if (!modal) {
        return;
    }

    modal.classList.remove('hidden');

    document.body.classList.add(
        'overflow-hidden'
    );
}


function cerrarModalEvento()
{
    const modal =
        document.getElementById('modalEvento');

    if (!modal) {
        return;
    }

    modal.classList.add('hidden');

    document.body.classList.remove(
        'overflow-hidden'
    );
}


function cambiarRecurrencia()
{
    const seleccion =
        document.querySelector(
            'input[name="recurrencia"]:checked'
        );


    if (!seleccion) {
        return;
    }


    const opciones =
        document.getElementById(
            'opcionesRecurrencia'
        );


    const meses =
        document.getElementById(
            'seleccionMeses'
        );


    if (
        seleccion.value === 'unico'
    ) {

        opciones.classList.add(
            'hidden'
        );

        meses.classList.add(
            'hidden'
        );

        return;
    }


    opciones.classList.remove(
        'hidden'
    );


    if (
        seleccion.value === 'meses'
    ) {

        meses.classList.remove(
            'hidden'
        );

    } else {

        meses.classList.add(
            'hidden'
        );

    }
}


document.addEventListener(
    'keydown',
    function(event) {

        if (
            event.key === 'Escape'
        ) {

            cerrarModalEvento();

        }

    }
);

</script>

@endif



{{-- ========================================================= --}}
{{-- ESTILOS --}}
{{-- ========================================================= --}}

<style>

.calendar-moderno {
    min-height: 650px;
}


.calendar-moderno .fc-toolbar-title {
    font-size: 1.35rem;
    font-weight: 700;
}


.calendar-moderno .fc-button {
    border: none !important;
    box-shadow: none !important;
    border-radius: 8px !important;
    font-weight: 600 !important;
    padding: 8px 12px !important;
}


.calendar-moderno .fc-col-header-cell {
    background: #f8fafc;
    border-color: #e5e7eb;
}


.calendar-moderno .fc-col-header-cell-cushion {
    padding: 12px 4px;
    font-size: .8rem;
    font-weight: 700;
    text-transform: uppercase;
    color: #6b7280;
    text-decoration: none;
}


.calendar-moderno .fc-daygrid-day {
    border-color: #e5e7eb;
}


.calendar-moderno .fc-daygrid-day-frame {
    min-height: 105px;
    padding: 5px;
}


.calendar-moderno .fc-daygrid-day-number {
    font-size: .85rem;
    font-weight: 600;
    color: #4b5563;
    text-decoration: none;
    padding: 6px;
}


.calendar-moderno .fc-day-today {
    background: #eff6ff !important;
}


.calendar-moderno
.fc-day-today
.fc-daygrid-day-number {

    background: #2563eb;
    color: white;
    border-radius: 999px;

    width: 28px;
    height: 28px;

    display: flex;
    align-items: center;
    justify-content: center;
}


.calendar-moderno .fc-event {
    border: none !important;
    border-radius: 6px !important;
    padding: 3px 6px !important;
    margin-bottom: 3px !important;
    font-size: .78rem !important;
    font-weight: 600 !important;
    cursor: pointer;
}


.calendar-moderno .fc-event:hover {
    opacity: .85;
}


.calendar-moderno .fc-more-link {
    color: #2563eb;
    font-weight: 600;
}


@media (max-width: 768px) {

    .calendar-moderno {
        min-height: 500px;
    }

    .calendar-moderno .fc-toolbar {
        flex-direction: column;
        gap: 10px;
    }

    .calendar-moderno .fc-toolbar-chunk {
        display: flex;
        justify-content: center;
    }

    .calendar-moderno .fc-toolbar-title {
        font-size: 1.1rem;
    }

    .calendar-moderno .fc-daygrid-day-frame {
        min-height: 75px;
    }

    .calendar-moderno .fc-event {
        font-size: .68rem !important;
    }

}

</style>

@endsection
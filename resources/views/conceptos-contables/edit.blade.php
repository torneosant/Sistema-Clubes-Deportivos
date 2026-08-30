@extends('layouts.app')

@section('titulo', 'Editar concepto contable')

@section('contenido')

<x-page-header
    title="✏️ Editar concepto contable"
    subtitle="Configura el concepto, sus cobros y las excepciones de jugadores."
/>


{{-- =========================================================
     MENSAJES
========================================================= --}}

@if(session('success'))

    <div class="mb-5 rounded-xl bg-green-50 border border-green-200
                text-green-700 px-4 py-3 text-sm">

        {{ session('success') }}

    </div>

@endif


@if($errors->any())

    <div class="mb-5 rounded-xl bg-red-50 border border-red-200
                text-red-700 px-4 py-3 text-sm">

        <div class="font-semibold mb-2">
            Revisa la información:
        </div>

        <ul class="list-disc ml-5">

            @foreach($errors->all() as $error)

                <li>{{ $error }}</li>

            @endforeach

        </ul>

    </div>

@endif


{{-- =========================================================
     ACCIONES
========================================================= --}}

<x-actions>

    <a href="{{ route('conceptos-contables.index') }}">

        <x-button color="gray">
            ← Volver
        </x-button>

    </a>

</x-actions>


<div class="max-w-4xl mx-auto">


    {{-- =====================================================
         FORMULARIO PRINCIPAL DEL CONCEPTO
    ====================================================== --}}

    <form
        method="POST"
        action="{{ route(
            'conceptos-contables.update',
            $conceptoContable
        ) }}"
    >

        @csrf
        @method('PUT')


        {{-- =================================================
             INFORMACIÓN GENERAL
        ================================================== --}}

        <x-card class="mb-6">

            <div class="flex items-center gap-3 mb-6">

                <div
                    class="w-10 h-10 rounded-xl
                           bg-blue-100
                           flex items-center justify-center"
                >
                    📚
                </div>

                <div>

                    <h2 class="text-lg font-bold text-slate-800">
                        {{ $conceptoContable->nombre }}
                    </h2>

                    <p class="text-xs text-gray-500">
                        Información general del concepto.
                    </p>

                </div>

            </div>


            <div class="space-y-5">


                {{-- NOMBRE --}}

                <x-input
                    name="nombre"
                    label="Nombre del concepto"
                    :value="old(
                        'nombre',
                        $conceptoContable->nombre
                    )"
                    placeholder="Ej. MENSUALIDAD"
                    required
                />


                {{-- TIPO --}}

                <div>

                    <label
                        class="block text-sm font-semibold
                               text-gray-700 mb-1"
                    >
                        Tipo
                    </label>

                    <select
                        name="tipo"
                        id="tipo"
                        required
                        class="w-full h-10 rounded-lg
                               border border-gray-300
                               bg-white px-3 text-sm
                               focus:ring-2 focus:ring-blue-500"
                    >

                        <option
                            value="Ingreso"
                            @selected(
                                old(
                                    'tipo',
                                    $conceptoContable->tipo
                                ) === 'Ingreso'
                            )
                        >
                            💰 Ingreso
                        </option>

                        <option
                            value="Egreso"
                            @selected(
                                old(
                                    'tipo',
                                    $conceptoContable->tipo
                                ) === 'Egreso'
                            )
                        >
                            💸 Egreso
                        </option>

                    </select>

                    <p class="text-xs text-gray-400 mt-1">
                        Solo los ingresos pueden generar cobros a jugadores.
                    </p>

                </div>


                {{-- VALOR PREDETERMINADO --}}

                <x-input
                    type="number"
                    name="valor_predeterminado"
                    label="Valor predeterminado"
                    :value="old(
                        'valor_predeterminado',
                        $conceptoContable->valor_predeterminado
                    )"
                    placeholder="20000"
                />


                {{-- DESCRIPCIÓN --}}

                <div>

                    <label
                        class="block text-sm font-semibold
                               text-gray-700 mb-1"
                    >
                        Descripción
                    </label>

                    <textarea
                        name="descripcion"
                        rows="3"
                        class="w-full border border-gray-300
                               rounded-lg px-3 py-2.5
                               focus:ring-2 focus:ring-blue-500"
                        placeholder="Descripción opcional..."
                    >{{ old(
                        'descripcion',
                        $conceptoContable->descripcion
                    ) }}</textarea>

                </div>


                {{-- ESTADO --}}

                <div
                    class="rounded-xl bg-gray-50
                           border border-gray-200 p-4"
                >

                    <label
                        class="flex items-center gap-3 cursor-pointer"
                    >

                        <input
                            type="checkbox"
                            name="activo"
                            value="1"
                            @checked(
                                old(
                                    'activo',
                                    $conceptoContable->activo
                                )
                            )
                            class="w-4 h-4 text-blue-600
                                   border-gray-300 rounded"
                        >

                        <div>

                            <div
                                class="text-sm font-semibold
                                       text-gray-700"
                            >
                                Concepto activo
                            </div>

                            <div class="text-xs text-gray-500">
                                Disponible para nuevos registros.
                            </div>

                        </div>

                    </label>

                </div>

            </div>

        </x-card>


        {{-- =====================================================
             CONFIGURACIÓN DE COBRO
        ====================================================== --}}

        <x-card class="mb-6">

            <div class="flex items-center gap-3 mb-5">

                <div
                    class="w-10 h-10 rounded-xl
                           bg-blue-100
                           flex items-center justify-center"
                >
                    ⚙️
                </div>

                <div>

                    <h2 class="text-lg font-bold text-slate-800">
                        Configuración de cobro
                    </h2>

                    <p class="text-xs text-gray-500">
                        Define cómo se generan las obligaciones de este concepto.
                    </p>

                </div>

            </div>


            {{-- GENERA COBRO --}}

            <div
                class="rounded-xl bg-blue-50
                       border border-blue-200 p-4 mb-5"
            >

                <label
                    class="flex items-center gap-3 cursor-pointer"
                >

                    <input
                        type="checkbox"
                        name="genera_cobro"
                        id="generaCobro"
                        value="1"
                        @checked(
                            old(
                                'genera_cobro',
                                $conceptoContable->genera_cobro
                            )
                        )
                        class="w-5 h-5 text-blue-600
                               border-gray-300 rounded"
                    >

                    <div>

                        <div class="font-semibold text-gray-800">
                            Este concepto genera cobros
                        </div>

                        <div class="text-xs text-gray-500">
                            Al activarlo podrás generar obligaciones para los jugadores.
                        </div>

                    </div>

                </label>

            </div>


            {{-- CAMPOS DE COBRO --}}

            <div
                id="camposCobro"
                class="space-y-5
                    {{ old(
                        'genera_cobro',
                        $conceptoContable->genera_cobro
                    ) ? '' : 'hidden' }}"
            >


                {{-- TIPO DE COBRO --}}

                <div>

                    <label
                        class="block text-sm font-semibold
                               text-gray-700 mb-1"
                    >
                        Tipo de cobro
                    </label>

                    <select
                        name="tipo_cobro"
                        id="tipoCobro"
                        class="w-full h-10 rounded-lg
                               border border-gray-300
                               bg-white px-3 text-sm
                               focus:ring-2 focus:ring-blue-500"
                    >

                        <option value="">
                            Seleccione...
                        </option>

                        <option
                            value="Mensual"
                            @selected(
                                old(
                                    'tipo_cobro',
                                    $conceptoContable->tipo_cobro
                                ) === 'Mensual'
                            )
                        >
                            📅 Mensual
                        </option>

                        <option
                            value="Unico"
                            @selected(
                                old(
                                    'tipo_cobro',
                                    $conceptoContable->tipo_cobro
                                ) === 'Unico'
                            )
                        >
                            📌 Único
                        </option>

                    </select>

                    <p class="text-xs text-gray-400 mt-1">
                        Para una mensualidad selecciona "Mensual".
                    </p>

                </div>


                {{-- VALOR --}}

                <x-input
                    type="number"
                    name="valor_cobro"
                    label="Valor del cobro"
                    :value="old(
                        'valor_cobro',
                        $conceptoContable->valor_cobro
                    )"
                    placeholder="20000"
                />


                {{-- CONFIGURACIÓN MENSUAL --}}

                <div
                    id="campoMensual"
                    class="hidden"
                >

                    <label
                        class="block text-sm font-semibold
                               text-gray-700 mb-1"
                    >
                        Día de cobro mensual
                    </label>

                    <select
                        name="dia_cobro"
                        class="w-full h-10 rounded-lg
                               border border-gray-300
                               bg-white px-3 text-sm"
                    >

                        <option value="">
                            Seleccione el día
                        </option>

                        @for($dia = 1; $dia <= 31; $dia++)

                            <option
                                value="{{ $dia }}"
                                @selected(
                                    old(
                                        'dia_cobro',
                                        $conceptoContable->dia_cobro
                                    ) == $dia
                                )
                            >
                                Día {{ $dia }}
                            </option>

                        @endfor

                    </select>

                    <p class="text-xs text-gray-500 mt-1">
                        Día en que normalmente se genera la obligación.
                    </p>

                </div>


                {{-- CONFIGURACIÓN ÚNICA --}}

                <div
                    id="campoUnico"
                    class="hidden"
                >

                    <label
                        class="block text-sm font-semibold
                               text-gray-700 mb-1"
                    >
                        Fecha máxima de cobro
                    </label>

                    <input
                        type="date"
                        name="fecha_maxima"
                        value="{{ old(
                            'fecha_maxima',
                            $conceptoContable->fecha_maxima
                                ? $conceptoContable->fecha_maxima->format('Y-m-d')
                                : ''
                        ) }}"
                        class="w-full h-10 rounded-lg
                               border border-gray-300
                               px-3 text-sm"
                    >

                </div>


                {{-- FECHA DE INICIO --}}

                <div>

                    <label
                        class="block text-sm font-semibold
                               text-gray-700 mb-1"
                    >
                        Fecha de inicio
                    </label>

                    <input
                        type="date"
                        name="fecha_inicio"
                        value="{{ old(
                            'fecha_inicio',
                            $conceptoContable->fecha_inicio
                                ? $conceptoContable->fecha_inicio->format('Y-m-d')
                                : ''
                        ) }}"
                        class="w-full h-10 rounded-lg
                               border border-gray-300
                               px-3 text-sm"
                    >

                    <p class="text-xs text-gray-500 mt-1">
                        Fecha desde la cual puede comenzar a generar cobros.
                    </p>

                </div>


                {{-- INFORMACIÓN --}}

                <div
                    class="rounded-xl
                           bg-amber-50
                           border border-amber-200
                           px-4 py-3"
                >

                    <div class="flex gap-3">

                        <div class="text-lg">
                            💡
                        </div>

                        <div>

                            <div
                                class="font-semibold
                                       text-amber-800 text-sm"
                            >
                                Becas y exoneraciones
                            </div>

                            <div
                                class="text-xs
                                       text-amber-700 mt-1"
                            >
                                Al generar los cobros, el sistema
                                comprobará las becas y exoneraciones
                                vigentes de cada jugador.
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </x-card>


        {{-- =====================================================
             GUARDAR CONCEPTO
        ====================================================== --}}

        <x-actions>

            <a
                href="{{ route('conceptos-contables.index') }}"
            >

                <x-button color="gray">
                    Cancelar
                </x-button>

            </a>

            <x-button
                type="submit"
                color="blue"
            >
                💾 Guardar cambios
            </x-button>

        </x-actions>

    </form>


    {{-- =========================================================
     GENERAR COBROS
========================================================= --}}

@if(
    $conceptoContable->activo &&
    $conceptoContable->genera_cobro &&
    $conceptoContable->tipo_cobro === 'Mensual'
)

    <x-card class="mt-6 mb-6">

        <div class="flex items-center gap-3 mb-5">

            <div
                class="w-10 h-10 rounded-xl
                       bg-green-100
                       flex items-center justify-center"
            >
                ⚡
            </div>

            <div>

                <h2 class="text-lg font-bold text-slate-800">
                    Generar cobros
                </h2>

                <p class="text-xs text-gray-500">
                    Genera las obligaciones mensuales del año.
                </p>

            </div>

        </div>


        <form
            method="POST"
            action="{{ route(
                'conceptos-contables.generar-cobros',
                $conceptoContable
            ) }}"
        >

            @csrf


            {{-- AÑO --}}

            <div class="mb-6">

                <label
                    class="block text-sm font-semibold
                           text-gray-700 mb-1"
                >
                    Año
                </label>

                <select
                    name="anio"
                    id="anioGeneracion"
                    class="w-full h-10 rounded-lg
                           border border-gray-300
                           bg-white px-3 text-sm
                           focus:ring-2
                           focus:ring-blue-500"
                >

                    @for(
                        $anio = date('Y') - 1;
                        $anio <= date('Y') + 2;
                        $anio++
                    )

                        <option
                            value="{{ $anio }}"
                            @selected(
                                $anio == date('Y')
                            )
                        >
                            {{ $anio }}
                        </option>

                    @endfor

                </select>

            </div>


            {{-- TODO EL AÑO --}}

            <div
                class="rounded-xl
                       bg-green-50
                       border border-green-200
                       p-4 mb-5"
            >

                <label
                    class="flex items-start
                           gap-3 cursor-pointer"
                >

                    <input
                        type="checkbox"
                        name="todo_el_anio"
                        value="1"
                        id="todoElAnio"
                        class="w-5 h-5 mt-0.5
                               text-green-600
                               border-gray-300
                               rounded"
                    >

                    <div>

                        <div
                            class="text-sm
                                   font-semibold
                                   text-gray-800"
                        >
                            ⚡ Generar todo el año
                        </div>

                        <div
                            class="text-xs
                                   text-gray-500 mt-1"
                        >
                            Genera enero a diciembre de una sola vez.
                            Las becas y exoneraciones se respetan
                            automáticamente.
                        </div>

                    </div>

                </label>

            </div>


            {{-- MESES --}}

            <div>

                <div
                    class="flex flex-col
                           sm:flex-row
                           sm:items-center
                           sm:justify-between
                           gap-2 mb-3"
                >

                    <div>

                        <label
                            class="block text-sm
                                   font-semibold
                                   text-gray-700"
                        >
                            Meses a generar
                        </label>

                        <p class="text-xs text-gray-500">
                            Selecciona uno o varios meses.
                        </p>

                    </div>


                    <button
                        type="button"
                        id="seleccionarMeses"
                        class="text-xs
                               font-semibold
                               text-blue-600
                               hover:text-blue-800"
                    >
                        Seleccionar todos
                    </button>

                </div>


                <div
                    class="grid
                           grid-cols-2
                           sm:grid-cols-3
                           md:grid-cols-4
                           gap-3"
                >

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


                    @foreach($meses as $numero => $nombre)

                        <label
                            class="flex items-center
                                   gap-2
                                   rounded-xl
                                   border
                                   border-gray-200
                                   bg-gray-50
                                   px-3 py-3
                                   cursor-pointer
                                   hover:bg-gray-100"
                        >

                            <input
                                type="checkbox"
                                name="meses[]"
                                value="{{ $numero }}"
                                class="mes-checkbox
                                       w-4 h-4
                                       text-blue-600
                                       border-gray-300
                                       rounded"
                            >

                            <span
                                class="text-sm
                                       font-medium
                                       text-gray-700"
                            >
                                {{ $nombre }}
                            </span>

                        </label>

                    @endforeach

                </div>

            </div>


            {{-- INFORMACIÓN --}}

            <div
                class="rounded-xl
                       bg-blue-50
                       border border-blue-200
                       px-4 py-3 mt-5"
            >

                <div class="flex gap-3">

                    <div class="text-lg">
                        ℹ️
                    </div>

                    <div>

                        <div
                            class="font-semibold
                                   text-blue-800 text-sm"
                        >
                            Importante
                        </div>

                        <div
                            class="text-xs
                                   text-blue-700 mt-1"
                        >

                            El sistema no duplicará cobros existentes.
                            Los jugadores con beca del 100% no recibirán
                            obligación durante la vigencia de la beca.

                            Las becas parciales reducirán automáticamente
                            el valor del cobro.

                        </div>

                    </div>

                </div>

            </div>


            {{-- BOTÓN --}}

            <x-actions>

                <x-button
                    type="submit"
                    color="green"
                >
                    ⚡ Generar cobros seleccionados
                </x-button>

            </x-actions>

        </form>

    </x-card>

@endif



    {{-- =========================================================
         BECAS Y EXONERACIONES
    ========================================================== --}}

    @if($conceptoContable->genera_cobro)

        <x-card class="mb-6">

            <div class="flex items-center gap-3 mb-5">

                <div
                    class="w-10 h-10 rounded-xl
                           bg-purple-100
                           flex items-center justify-center"
                >
                    🎓
                </div>

                <div>

                    <h2 class="text-lg font-bold text-slate-800">
                        Becas y exoneraciones
                    </h2>

                    <p class="text-xs text-gray-500">
                        Excluye o reduce el cobro para jugadores específicos.
                    </p>

                </div>

            </div>


            {{-- =================================================
                 BECAS EXISTENTES
            ================================================== --}}

            @if($becas->count())

                <div class="space-y-3 mb-6">

                    @foreach($becas as $beca)

                        <div
                            class="rounded-xl border
                                   border-gray-200
                                   bg-gray-50 p-4"
                        >

                            <div
                                class="flex flex-col
                                       md:flex-row
                                       md:items-center
                                       md:justify-between
                                       gap-4"
                            >

                                <div>

                                    <div
                                        class="font-semibold
                                               text-gray-800"
                                    >

                                        {{ $beca->jugador->apellidos }}
                                        {{ $beca->jugador->nombres }}

                                    </div>


                                    <div
                                        class="text-sm
                                               font-semibold
                                               text-purple-700 mt-1"
                                    >

                                        {{ number_format(
                                            $beca->porcentaje,
                                            0
                                        ) }}% de descuento

                                    </div>


                                    <div
                                        class="text-xs
                                               text-gray-500 mt-1"
                                    >

                                        Desde:
                                        {{ $beca->fecha_inicio->format('d/m/Y') }}

                                        @if($beca->fecha_fin)

                                            · Hasta:
                                            {{ $beca->fecha_fin->format('d/m/Y') }}

                                        @else

                                            · ♾️ Por siempre

                                        @endif

                                    </div>


                                    @if($beca->motivo)

                                        <div
                                            class="text-xs
                                                   text-gray-500 mt-1"
                                        >

                                            Motivo:
                                            {{ $beca->motivo }}

                                        </div>

                                    @endif

                                </div>


                                <div>

                                    @if($beca->activo)

                                        <form
                                            method="POST"
                                            action="{{ route(
                                                'becas-jugadores.desactivar',
                                                $beca
                                            ) }}"
                                            onsubmit="return confirm(
                                                '¿Deseas desactivar esta beca?'
                                            )"
                                        >

                                            @csrf

                                            <x-button
                                                type="submit"
                                                color="red"
                                            >
                                                Desactivar
                                            </x-button>

                                        </form>

                                    @else

                                        <span
                                            class="inline-flex
                                                   items-center
                                                   rounded-full
                                                   bg-gray-200
                                                   px-3 py-1
                                                   text-xs
                                                   font-semibold
                                                   text-gray-600"
                                        >
                                            Inactiva
                                        </span>

                                    @endif

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

            @else

                <div
                    class="rounded-xl
                           border border-dashed
                           border-purple-300
                           bg-purple-50
                           px-5 py-6
                           text-center mb-6"
                >

                    <div class="text-3xl mb-2">
                        🎓
                    </div>

                    <div
                        class="font-semibold text-gray-700"
                    >
                        No hay becas configuradas
                    </div>

                    <div
                        class="text-xs text-gray-500 mt-1"
                    >
                        Las becas que agregues aparecerán aquí.
                    </div>

                </div>

            @endif


            {{-- =================================================
                 NUEVA BECA
            ================================================== --}}

            <div
                class="border-t
                       border-gray-200
                       pt-5"
            >

                <h3
                    class="text-base
                           font-bold
                           text-slate-800 mb-4"
                >
                    ➕ Agregar beca o exoneración
                </h3>


                <form
                    method="POST"
                    action="{{ route(
                        'conceptos-contables.becas.guardar',
                        $conceptoContable
                    ) }}"
                    class="space-y-5"
                >

                    @csrf


                    {{-- JUGADOR --}}

                    <div>

                        <label
                            class="block text-sm
                                   font-semibold
                                   text-gray-700 mb-1"
                        >
                            Jugador
                        </label>

                        <select
                            name="jugador_id"
                            required
                            class="w-full h-10
                                   rounded-lg
                                   border border-gray-300
                                   bg-white px-3 text-sm"
                        >

                            <option value="">
                                Seleccione un jugador
                            </option>

                            @foreach($jugadores as $jugador)

                                <option
                                    value="{{ $jugador->id }}"
                                    @selected(
                                        old('jugador_id') == $jugador->id
                                    )
                                >

                                    {{ $jugador->apellidos }}
                                    {{ $jugador->nombres }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- VIGENCIA --}}

                    <div>

                        <label
                            class="block text-sm
                                   font-semibold
                                   text-gray-700 mb-2"
                        >
                            Vigencia de la beca
                        </label>


                        <div class="space-y-2">


                            {{-- SIEMPRE --}}

                            <label
                                class="flex items-start
                                       gap-3 rounded-xl
                                       border border-gray-200
                                       bg-white p-3
                                       cursor-pointer
                                       hover:bg-gray-50"
                            >

                                <input
                                    type="radio"
                                    name="tipo_vigencia"
                                    value="siempre"
                                    id="vigenciaSiempre"
                                    @checked(
                                        old(
                                            'tipo_vigencia',
                                            'siempre'
                                        ) === 'siempre'
                                    )
                                    class="mt-1 w-4 h-4
                                           text-purple-600"
                                >

                                <div>

                                    <div
                                        class="text-sm
                                               font-semibold
                                               text-gray-800"
                                    >
                                        ♾️ Por siempre
                                    </div>

                                    <div
                                        class="text-xs
                                               text-gray-500"
                                    >
                                        Mientras el jugador pertenezca al club.
                                    </div>

                                </div>

                            </label>


                            {{-- TODO EL AÑO --}}

                            <label
                                class="flex items-start
                                       gap-3 rounded-xl
                                       border border-gray-200
                                       bg-white p-3
                                       cursor-pointer
                                       hover:bg-gray-50"
                            >

                                <input
                                    type="radio"
                                    name="tipo_vigencia"
                                    value="anio"
                                    id="vigenciaAnio"
                                    @checked(
                                        old('tipo_vigencia') === 'anio'
                                    )
                                    class="mt-1 w-4 h-4
                                           text-purple-600"
                                >

                                <div>

                                    <div
                                        class="text-sm
                                               font-semibold
                                               text-gray-800"
                                    >
                                        📅 Todo el año
                                    </div>

                                    <div
                                        class="text-xs
                                               text-gray-500"
                                    >
                                        Aplica desde enero hasta diciembre.
                                    </div>

                                </div>

                            </label>


                            {{-- PERSONALIZADO --}}

                            <label
                                class="flex items-start
                                       gap-3 rounded-xl
                                       border border-gray-200
                                       bg-white p-3
                                       cursor-pointer
                                       hover:bg-gray-50"
                            >

                                <input
                                    type="radio"
                                    name="tipo_vigencia"
                                    value="personalizado"
                                    id="vigenciaPersonalizada"
                                    @checked(
                                        old('tipo_vigencia') === 'personalizado'
                                    )
                                    class="mt-1 w-4 h-4
                                           text-purple-600"
                                >

                                <div>

                                    <div
                                        class="text-sm
                                               font-semibold
                                               text-gray-800"
                                    >
                                        🗓️ Periodo personalizado
                                    </div>

                                    <div
                                        class="text-xs
                                               text-gray-500"
                                    >
                                        Define las fechas exactas.
                                    </div>

                                </div>

                            </label>

                        </div>

                    </div>


                    {{-- AÑO --}}

                    <div
                        id="campoAnio"
                        class="hidden"
                    >

                        <label
                            class="block text-sm
                                   font-semibold
                                   text-gray-700 mb-1"
                        >
                            Año
                        </label>

                        <select
                            name="anio"
                            id="anio"
                            class="w-full h-10
                                   rounded-lg
                                   border border-gray-300
                                   bg-white px-3 text-sm"
                        >

                            @for(
                                $anio = date('Y') - 1;
                                $anio <= date('Y') + 2;
                                $anio++
                            )

                                <option
                                    value="{{ $anio }}"
                                    @selected(
                                        old(
                                            'anio',
                                            date('Y')
                                        ) == $anio
                                    )
                                >
                                    {{ $anio }}
                                </option>

                            @endfor

                        </select>

                    </div>


                    {{-- FECHAS PERSONALIZADAS --}}

                    <div
                        id="camposPersonalizados"
                        class="hidden grid
                               grid-cols-1 md:grid-cols-2
                               gap-4"
                    >

                        <div>

                            <label
                                class="block text-sm
                                       font-semibold
                                       text-gray-700 mb-1"
                            >
                                Desde
                            </label>

                            <input
                                type="date"
                                name="fecha_inicio"
                                id="fechaInicio"
                                value="{{ old('fecha_inicio') }}"
                                class="w-full h-10
                                       rounded-lg
                                       border border-gray-300
                                       px-3 text-sm"
                            >

                        </div>


                        <div>

                            <label
                                class="block text-sm
                                       font-semibold
                                       text-gray-700 mb-1"
                            >
                                Hasta
                            </label>

                            <input
                                type="date"
                                name="fecha_fin"
                                id="fechaFin"
                                value="{{ old('fecha_fin') }}"
                                class="w-full h-10
                                       rounded-lg
                                       border border-gray-300
                                       px-3 text-sm"
                            >

                        </div>

                    </div>


                    {{-- PORCENTAJE --}}

                    <div>

                        <label
                            class="block text-sm
                                   font-semibold
                                   text-gray-700 mb-1"
                        >
                            Descuento
                        </label>

                        <div class="flex items-center gap-2">

                            <input
                                type="number"
                                name="porcentaje"
                                min="1"
                                max="100"
                                step="1"
                                value="{{ old(
                                    'porcentaje',
                                    100
                                ) }}"
                                required
                                class="w-32 h-10
                                       rounded-lg
                                       border border-gray-300
                                       px-3 text-sm"
                            >

                            <span class="text-sm text-gray-600">
                                %
                            </span>

                        </div>

                        <p class="text-xs text-gray-500 mt-1">
                            100% = exonerado. 50% = paga la mitad.
                        </p>

                    </div>


                    {{-- MOTIVO --}}

                    <div>

                        <label
                            class="block text-sm
                                   font-semibold
                                   text-gray-700 mb-1"
                        >
                            Motivo
                        </label>

                        <textarea
                            name="motivo"
                            rows="2"
                            class="w-full border
                                   border-gray-300
                                   rounded-lg
                                   px-3 py-2.5
                                   text-sm"
                            placeholder="Ej. Beca deportiva..."
                        >{{ old('motivo') }}</textarea>

                    </div>


                    {{-- GUARDAR BECA --}}

                    <x-actions>

                        <x-button
                            type="submit"
                            color="purple"
                        >
                            🎓 Guardar beca
                        </x-button>

                    </x-actions>

                </form>

            </div>

        </x-card>

    @endif

</div>


{{-- =========================================================
     JAVASCRIPT
========================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {


    /* ========================================================
       CONFIGURACIÓN DE COBRO
    ======================================================== */

    const generaCobro =
        document.getElementById('generaCobro');

    const camposCobro =
        document.getElementById('camposCobro');

    const tipoCobro =
        document.getElementById('tipoCobro');

    const campoMensual =
        document.getElementById('campoMensual');

    const campoUnico =
        document.getElementById('campoUnico');


    function actualizarCobro()
    {

        if (!generaCobro || !camposCobro) {
            return;
        }


        if (generaCobro.checked) {

            camposCobro.classList.remove('hidden');

        } else {

            camposCobro.classList.add('hidden');

        }


        actualizarTipoCobro();

    }


    function actualizarTipoCobro()
    {

        if (!tipoCobro) {
            return;
        }


        if (campoMensual) {
            campoMensual.classList.add('hidden');
        }

        if (campoUnico) {
            campoUnico.classList.add('hidden');
        }


        if (tipoCobro.value === 'Mensual') {

            campoMensual?.classList.remove('hidden');

        }


        if (tipoCobro.value === 'Unico') {

            campoUnico?.classList.remove('hidden');

        }

    }


    generaCobro?.addEventListener(
        'change',
        actualizarCobro
    );


    tipoCobro?.addEventListener(
        'change',
        actualizarTipoCobro
    );


    actualizarCobro();


    /* ========================================================
       BECAS
    ======================================================== */

    const siempre =
        document.getElementById(
            'vigenciaSiempre'
        );

    const anio =
        document.getElementById(
            'vigenciaAnio'
        );

    const personalizada =
        document.getElementById(
            'vigenciaPersonalizada'
        );

    const campoAnio =
        document.getElementById(
            'campoAnio'
        );

    const camposPersonalizados =
        document.getElementById(
            'camposPersonalizados'
        );

    const fechaInicio =
        document.getElementById(
            'fechaInicio'
        );

    const fechaFin =
        document.getElementById(
            'fechaFin'
        );


    function actualizarVigencia()
    {

        if (!campoAnio || !camposPersonalizados) {
            return;
        }


        campoAnio.classList.add(
            'hidden'
        );

        camposPersonalizados.classList.add(
            'hidden'
        );


        if (fechaInicio) {
            fechaInicio.required = false;
        }

        if (fechaFin) {
            fechaFin.required = false;
        }


        if (
            anio &&
            anio.checked
        ) {

            campoAnio.classList.remove(
                'hidden'
            );

        }


        if (
            personalizada &&
            personalizada.checked
        ) {

            camposPersonalizados.classList.remove(
                'hidden'
            );

            if (fechaInicio) {
                fechaInicio.required = true;
            }

            if (fechaFin) {
                fechaFin.required = true;
            }

        }

    }


    siempre?.addEventListener(
        'change',
        actualizarVigencia
    );


    anio?.addEventListener(
        'change',
        actualizarVigencia
    );


    personalizada?.addEventListener(
        'change',
        actualizarVigencia
    );


    actualizarVigencia();

});

/* ========================================================
   GENERACIÓN DE COBROS
======================================================== */

const todoElAnio =
    document.getElementById('todoElAnio');

const seleccionarMeses =
    document.getElementById('seleccionarMeses');

const mesesCheckbox =
    document.querySelectorAll('.mes-checkbox');


todoElAnio?.addEventListener(
    'change',
    function () {

        mesesCheckbox.forEach(function (checkbox) {

            checkbox.checked =
                todoElAnio.checked;

            checkbox.disabled =
                todoElAnio.checked;

        });

        seleccionarMeses.textContent =
            todoElAnio.checked
                ? 'Quitar selección'
                : 'Seleccionar todos';

    }
);


seleccionarMeses?.addEventListener(
    'click',
    function () {

        const todosMarcados =
            Array.from(mesesCheckbox)
                .every(
                    checkbox => checkbox.checked
                );


        mesesCheckbox.forEach(function (checkbox) {

            checkbox.checked =
                !todosMarcados;

        });


        seleccionarMeses.textContent =
            todosMarcados
                ? 'Seleccionar todos'
                : 'Quitar selección';

    }
);

</script>

@endsection
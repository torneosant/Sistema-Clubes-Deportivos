@extends('layouts.app')

@section('titulo', 'Contabilidad')

@section('contenido')


{{-- =========================================================
     ENCABEZADO
========================================================= --}}

<x-page-header
    title="💰 Contabilidad"
    subtitle="Control financiero y seguimiento de pagos del club."
>

    <div class="flex flex-wrap items-center gap-2">

        <x-stat
            label="Ingresos"
            :value="'$ ' . number_format($ingresos, 0, ',', '.')"
            icon="💰"
            color="green"
        />

        <x-stat
            label="Gastos"
            :value="'$ ' . number_format($gastos, 0, ',', '.')"
            icon="💸"
            color="red"
        />

        <x-stat
            label="Saldo"
            :value="'$ ' . number_format($saldo, 0, ',', '.')"
            icon="💵"
            color="blue"
        />

        <x-stat
            label="Pendiente"
            :value="'$ ' . number_format($totalPendiente, 0, ',', '.')"
            icon="⏳"
            color="yellow"
        />

    </div>

</x-page-header>


{{-- =========================================================
     MENSAJE
========================================================= --}}

@if(session('success'))

    <div
        class="mb-6 rounded-lg
               bg-green-100
               border border-green-300
               text-green-700
               px-4 py-3"
    >
        {{ session('success') }}
    </div>

@endif


{{-- =========================================================
     BOTONES PRINCIPALES
========================================================= --}}

<x-actions>

    <a href="{{ route('contabilidad.create') }}">

        <x-button color="blue">
            ➕ Nuevo movimiento
        </x-button>

    </a>


       <a href="{{ route('contabilidad.exportExcel', request()->query()) }}">
        <x-button color="green">
            📊 Excel
        </x-button>
    </a>

    <a href="{{ route('contabilidad.pdf', request()->query()) }}">
        <x-button color="red">
            📄 PDF
        </x-button>
    </a>

</x-actions>


{{-- =========================================================
     FILTROS MOVIMIENTOS
========================================================= --}}

<x-filter
    :action="route('contabilidad.index')"
>

    {{-- TIPO --}}

    <div class="w-full md:w-[180px]">

        <select
            name="tipo"
            class="w-full h-10 rounded-lg
                   border border-gray-300
                   bg-white px-3
                   text-sm text-slate-700
                   focus:ring-2
                   focus:ring-blue-500
                   focus:border-blue-500
                   transition"
        >

            <option value="">
                Todos los movimientos
            </option>

            <option
                value="Ingreso"
                @selected(request('tipo') === 'Ingreso')
            >
                💰 Ingresos
            </option>

            <option
                value="Egreso"
                @selected(request('tipo') === 'Egreso')
            >
                💸 Gastos
            </option>

        </select>

    </div>


    {{-- CONCEPTO --}}

    <div class="w-full md:w-[220px]">

        <select
            name="concepto"
            class="w-full h-10 rounded-lg
                   border border-gray-300
                   bg-white px-3
                   text-sm text-slate-700
                   focus:ring-2
                   focus:ring-blue-500
                   focus:border-blue-500
                   transition"
        >

            <option value="">
                Todos los conceptos
            </option>

            @foreach($conceptos as $concepto)

                <option
                    value="{{ $concepto->id }}"
                    @selected(
                        request('concepto') == $concepto->id
                    )
                >
                    {{ $concepto->nombre }}
                </option>

            @endforeach

        </select>

    </div>


    {{-- JUGADOR --}}

    <div class="w-full md:w-[240px]">

        <select
            name="jugador_movimiento"
            class="w-full h-10 rounded-lg
                   border border-gray-300
                   bg-white px-3
                   text-sm text-slate-700
                   focus:ring-2
                   focus:ring-blue-500
                   focus:border-blue-500
                   transition"
        >

            <option value="">
                Todos los jugadores
            </option>

            @foreach($jugadores as $jugador)

                <option
                    value="{{ $jugador->id }}"
                    @selected(
                        request('jugador_movimiento') == $jugador->id
                    )
                >

                    {{ $jugador->apellidos }}
                    {{ $jugador->nombres }}

                </option>

            @endforeach

        </select>

    </div>


    {{-- DESDE --}}

    <div class="w-full md:w-[170px]">

        <input
            type="date"
            name="desde"
            value="{{ request('desde') }}"
            class="w-full h-10 rounded-lg
                   border border-gray-300
                   bg-white px-3
                   text-sm text-slate-700
                   focus:ring-2
                   focus:ring-blue-500"
        >

    </div>


    {{-- HASTA --}}

    <div class="w-full md:w-[170px]">

        <input
            type="date"
            name="hasta"
            value="{{ request('hasta') }}"
            class="w-full h-10 rounded-lg
                   border border-gray-300
                   bg-white px-3
                   text-sm text-slate-700
                   focus:ring-2
                   focus:ring-blue-500"
        >

    </div>


    <x-button
        type="submit"
        color="blue"
    >
        🔍 Filtrar
    </x-button>


    <a
        href="{{ route('contabilidad.index') }}"
        class="inline-flex
               items-center
               justify-center
               bg-gray-600
               hover:bg-gray-700
               text-white
               px-5 py-2
               rounded-xl
               font-semibold
               transition-all
               duration-300
               shadow-sm
               hover:shadow-md"
    >
        Limpiar
    </a>

</x-filter>


{{-- =========================================================
     MOVIMIENTOS CONTABLES
========================================================= --}}

<x-card class="mb-8">

    <div class="flex flex-col md:flex-row
                md:items-center
                md:justify-between
                gap-3 mb-5">

        <div>

            <h2 class="text-lg font-bold text-slate-800">
                📒 Movimientos contables
            </h2>

            <p class="text-xs text-gray-500 mt-1">
                Registro de ingresos y gastos del club.
            </p>

        </div>


        <div class="text-sm text-gray-500">

            {{ $movimientos->count() }}
            movimiento{{ $movimientos->count() == 1 ? '' : 's' }}

        </div>

    </div>


    <x-table>

        <x-table-header>

            <x-table-header-cell>
                Fecha
            </x-table-header-cell>

            <x-table-header-cell>
                Tipo
            </x-table-header-cell>

            <x-table-header-cell>
                Concepto
            </x-table-header-cell>

            <x-table-header-cell>
                Jugador / Tercero
            </x-table-header-cell>

            <x-table-header-cell align="right">
                Valor
            </x-table-header-cell>

            <x-table-header-cell align="center">
                Método
            </x-table-header-cell>

            <x-table-header-cell align="center">
                Acciones
            </x-table-header-cell>

        </x-table-header>


        <tbody>

            @forelse($movimientos as $movimiento)

                <x-table-row>

                    {{-- FECHA --}}

                    <x-table-cell>

                        {{ $movimiento->fecha
                            ? $movimiento->fecha->format('d/m/Y')
                            : '-'
                        }}

                    </x-table-cell>


                    {{-- TIPO --}}

                    <x-table-cell>

                        @if($movimiento->tipo === 'Ingreso')

                            <span
                                class="inline-flex
                                       items-center
                                       rounded-full
                                       bg-green-100
                                       px-3 py-1
                                       text-xs
                                       font-semibold
                                       text-green-700"
                            >
                                💰 Ingreso
                            </span>

                        @else

                            <span
                                class="inline-flex
                                       items-center
                                       rounded-full
                                       bg-red-100
                                       px-3 py-1
                                       text-xs
                                       font-semibold
                                       text-red-700"
                            >
                                💸 Gasto
                            </span>

                        @endif

                    </x-table-cell>


                    {{-- CONCEPTO --}}

                    <x-table-cell>

                        <div class="font-semibold text-slate-800">

                            {{ $movimiento->concepto->nombre ?? '-' }}

                        </div>

                    </x-table-cell>


                    {{-- JUGADOR / TERCERO --}}

                    <x-table-cell>

                        @if($movimiento->jugador)

                            <div class="font-medium">

                                {{ $movimiento->jugador->apellidos }}
                                {{ $movimiento->jugador->nombres }}

                            </div>

                        @elseif($movimiento->tercero)

                            {{ $movimiento->tercero }}

                        @else

                            -

                        @endif

                    </x-table-cell>


                    {{-- VALOR --}}

                    <x-table-cell align="right">

                        <span class="font-bold">

                            ${{ number_format(
                                $movimiento->valor,
                                0,
                                ',',
                                '.'
                            ) }}

                        </span>

                    </x-table-cell>


                    {{-- MÉTODO --}}

                    <x-table-cell align="center">

                        {{ $movimiento->metodo_pago ?? '-' }}

                    </x-table-cell>


                    {{-- ACCIONES --}}

                    <x-table-cell align="center">

                        <div
                            class="flex
                                   justify-center
                                   items-center
                                   gap-2"
                        >

                            <a
                                href="{{ route(
                                    'contabilidad.edit',
                                    $movimiento
                                ) }}"
                                class="w-9 h-9
                                       flex items-center
                                       justify-center
                                       rounded-lg
                                       bg-yellow-500
                                       hover:bg-yellow-600
                                       text-white
                                       shadow-sm"
                                title="Editar"
                            >
                                ✏️
                            </a>


                           <form
    action="{{ route(
        'contabilidad.destroy',
        $movimiento
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

                    </x-table-cell>

                </x-table-row>

            @empty

                <tr>

                    <td
                        colspan="7"
                        class="text-center
                               py-10
                               text-gray-500"
                    >

                        No hay movimientos contables
                        para los filtros seleccionados.

                    </td>

                </tr>

            @endforelse

        </tbody>

    </x-table>

</x-card>


{{-- =========================================================
     PENDIENTES DE PAGO
========================================================= --}}

<x-card class="mb-8">

    <div class="flex flex-col md:flex-row
                md:items-center
                md:justify-between
                gap-4 mb-5">


        <div>

            <h2 class="text-lg font-bold text-slate-800">
                ⏳ Pendientes de pago
            </h2>

            <p class="text-xs text-gray-500 mt-1">
                Consulta las obligaciones pendientes de los jugadores.
            </p>

        </div>


        <div class="flex flex-wrap gap-2">

            <div
                class="rounded-xl
                       bg-yellow-50
                       border border-yellow-200
                       px-4 py-2
                       text-center"
            >

                <div
                    class="text-xs
                           text-yellow-700
                           font-semibold"
                >
                    Obligaciones
                </div>

                <div
                    class="text-lg
                           font-bold
                           text-yellow-800"
                >
                    {{ $cantidadPendientes }}
                </div>

            </div>


            <div
                class="rounded-xl
                       bg-blue-50
                       border border-blue-200
                       px-4 py-2
                       text-center"
            >

                <div
                    class="text-xs
                           text-blue-700
                           font-semibold"
                >
                    Jugadores
                </div>

                <div
                    class="text-lg
                           font-bold
                           text-blue-800"
                >
                    {{ $jugadoresConPendiente }}
                </div>

            </div>


            <div
                class="rounded-xl
                       bg-red-50
                       border border-red-200
                       px-4 py-2
                       text-center"
            >

                <div
                    class="text-xs
                           text-red-700
                           font-semibold"
                >
                    Total
                </div>

                <div
                    class="text-lg
                           font-bold
                           text-red-800"
                >

                    ${{ number_format(
                        $totalPendiente,
                        0,
                        ',',
                        '.'
                    ) }}

                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         FILTROS PENDIENTES
    ====================================================== --}}

    <x-filter
        :action="route('contabilidad.index')"
    >

        {{-- JUGADOR --}}

        <div class="w-full md:w-[250px]">

            <select
                name="jugador"
                class="w-full h-10 rounded-lg
                       border border-gray-300
                       bg-white px-3
                       text-sm text-slate-700
                       focus:ring-2
                       focus:ring-blue-500"
            >

                <option value="">
                    Todos los jugadores
                </option>

                @foreach($jugadores as $jugador)

                    <option
                        value="{{ $jugador->id }}"
                        @selected(
                            request('jugador') == $jugador->id
                        )
                    >

                        {{ $jugador->apellidos }}
                        {{ $jugador->nombres }}

                    </option>

                @endforeach

            </select>

        </div>


        {{-- CONCEPTO --}}

        <div class="w-full md:w-[230px]">

            <select
                name="concepto_pendiente"
                class="w-full h-10 rounded-lg
                       border border-gray-300
                       bg-white px-3
                       text-sm text-slate-700
                       focus:ring-2
                       focus:ring-blue-500"
            >

                <option value="">
                    Todos los conceptos
                </option>

                @foreach($conceptos as $concepto)

                    <option
                        value="{{ $concepto->id }}"
                        @selected(
                            request('concepto_pendiente')
                            == $concepto->id
                        )
                    >

                        {{ $concepto->nombre }}

                    </option>

                @endforeach

            </select>

        </div>


        {{-- PERIODO --}}

        <div class="w-full md:w-[240px]">

            <select
                name="periodo_pendiente"
                class="w-full h-10 rounded-lg
                       border border-gray-300
                       bg-white px-3
                       text-sm text-slate-700
                       focus:ring-2
                       focus:ring-blue-500"
            >

                <option
                    value="todos"
                    @selected(
                        $periodoPendientes === 'todos'
                    )
                >
                    📊 Todos los meses de {{ $anio }}
                </option>


                @php

                    $meses = [
                        '01' => 'Enero',
                        '02' => 'Febrero',
                        '03' => 'Marzo',
                        '04' => 'Abril',
                        '05' => 'Mayo',
                        '06' => 'Junio',
                        '07' => 'Julio',
                        '08' => 'Agosto',
                        '09' => 'Septiembre',
                        '10' => 'Octubre',
                        '11' => 'Noviembre',
                        '12' => 'Diciembre',
                    ];

                @endphp


                @foreach($meses as $numero => $nombre)

                    @php

                        $periodo =
                            $anio . '-' . $numero;

                    @endphp

                    <option
                        value="{{ $periodo }}"
                        @selected(
                            $periodoPendientes === $periodo
                        )
                    >

                        {{ $nombre }} {{ $anio }}

                    </option>

                @endforeach

            </select>

        </div>


        <x-button
            type="submit"
            color="blue"
        >
            🔍 Filtrar
        </x-button>


        <a
            href="{{ route('contabilidad.index') }}"
            class="inline-flex
                   items-center
                   justify-center
                   bg-gray-600
                   hover:bg-gray-700
                   text-white
                   px-5 py-2
                   rounded-xl
                   font-semibold
                   transition-all
                   duration-300
                   shadow-sm
                   hover:shadow-md"
        >
            Limpiar
        </a>

    </x-filter>


    {{-- =====================================================
         BOTONES EXPORTACIÓN PENDIENTES
    ====================================================== --}}

    <div
        class="flex
               justify-end
               gap-2
               mb-4"
    >

        <a
            href="{{ route(
                'contabilidad.exportExcel',
                array_merge(
                    request()->query(),
                    [
                        'solo_pendientes' => 1
                    ]
                )
            ) }}"
        >

            <x-button color="green">
                📊 Excel pendientes
            </x-button>

        </a>


        <a
            href="{{ route(
                'contabilidad.pdf',
                array_merge(
                    request()->query(),
                    [
                        'solo_pendientes' => 1
                    ]
                )
            ) }}"
        >

            <x-button color="red">
                📄 PDF pendientes
            </x-button>

        </a>

    </div>


    {{-- =====================================================
         TABLA PENDIENTES
    ====================================================== --}}

    <x-table>

        <x-table-header>

            <x-table-header-cell>
                Jugador
            </x-table-header-cell>

            <x-table-header-cell>
                Concepto
            </x-table-header-cell>

            <x-table-header-cell>
                Periodo
            </x-table-header-cell>

            <x-table-header-cell>
                Fecha
            </x-table-header-cell>

            <x-table-header-cell align="right">
                Valor
            </x-table-header-cell>

            <x-table-header-cell align="right">
                Pagado
            </x-table-header-cell>

            <x-table-header-cell align="right">
                Pendiente
            </x-table-header-cell>

            <x-table-header-cell align="center">
                Estado
            </x-table-header-cell>

        </x-table-header>


        <tbody>

            @forelse($cargosPendientes as $cargo)

                @php

                    $pendiente =
                        max(
                            0,
                            (float) $cargo->valor -
                            (float) $cargo->valor_pagado
                        );

                @endphp


                <x-table-row>

                    {{-- JUGADOR --}}

                    <x-table-cell>

                        <div class="font-semibold text-slate-800">

                            {{ $cargo->jugador->apellidos ?? '' }}
                            {{ $cargo->jugador->nombres ?? '' }}

                        </div>

                    </x-table-cell>


                    {{-- CONCEPTO --}}

                    <x-table-cell>

                        {{ $cargo->concepto->nombre ?? '-' }}

                    </x-table-cell>


                    {{-- PERIODO --}}

                    <x-table-cell>

                        @if($cargo->periodo)

                            @php

                                try {

                                    $fechaPeriodo =
                                        \Carbon\Carbon::createFromFormat(
                                            'Y-m',
                                            $cargo->periodo
                                        );

                                    $nombrePeriodo =
                                        ucfirst(
                                            $fechaPeriodo->translatedFormat(
                                                'F Y'
                                            )
                                        );

                                } catch (\Throwable $e) {

                                    $nombrePeriodo =
                                        $cargo->periodo;

                                }

                            @endphp

                            {{ $nombrePeriodo }}

                        @else

                            -

                        @endif

                    </x-table-cell>


                    {{-- FECHA --}}

                    <x-table-cell>

                        {{ $cargo->fecha
                            ? $cargo->fecha->format('d/m/Y')
                            : '-'
                        }}

                    </x-table-cell>


                    {{-- VALOR --}}

                    <x-table-cell align="right">

                        ${{ number_format(
                            $cargo->valor,
                            0,
                            ',',
                            '.'
                        ) }}

                    </x-table-cell>


                    {{-- PAGADO --}}

                    <x-table-cell align="right">

                        ${{ number_format(
                            $cargo->valor_pagado,
                            0,
                            ',',
                            '.'
                        ) }}

                    </x-table-cell>


                    {{-- PENDIENTE --}}

                    <x-table-cell align="right">

                        <span
                            class="font-bold text-red-600"
                        >

                            ${{ number_format(
                                $pendiente,
                                0,
                                ',',
                                '.'
                            ) }}

                        </span>

                    </x-table-cell>


                    {{-- ESTADO --}}

                    <x-table-cell align="center">

                        @if($cargo->estado === 'Parcial')

                            <span
                                class="inline-flex
                                       items-center
                                       rounded-full
                                       bg-yellow-100
                                       px-3 py-1
                                       text-xs
                                       font-semibold
                                       text-yellow-700"
                            >
                                Parcial
                            </span>

                        @else

                            <span
                                class="inline-flex
                                       items-center
                                       rounded-full
                                       bg-red-100
                                       px-3 py-1
                                       text-xs
                                       font-semibold
                                       text-red-700"
                            >
                                Pendiente
                            </span>

                        @endif

                    </x-table-cell>

                </x-table-row>

            @empty

                <tr>

                    <td
                        colspan="8"
                        class="text-center
                               py-10
                               text-gray-500"
                    >

                        <div class="text-3xl mb-2">
                            🎉
                        </div>

                        <div class="font-semibold">
                            No hay pendientes de pago
                        </div>

                        <div class="text-xs mt-1">
                            No existen obligaciones pendientes
                            para los filtros seleccionados.
                        </div>

                    </td>

                </tr>

            @endforelse

        </tbody>

    </x-table>


    {{-- =====================================================
         TOTAL
    ====================================================== --}}

    @if($cargosPendientes->count())

        <div
            class="mt-4
                   flex
                   justify-end"
        >

            <div
                class="rounded-xl
                       bg-red-50
                       border border-red-200
                       px-5 py-3"
            >

                <span
                    class="text-sm
                           font-semibold
                           text-red-700"
                >
                    Total pendiente:
                </span>

                <span
                    class="ml-2
                           text-lg
                           font-bold
                           text-red-800"
                >

                    ${{ number_format(
                        $totalPendiente,
                        0,
                        ',',
                        '.'
                    ) }}

                </span>

            </div>

        </div>

    @endif

</x-card>


@endsection
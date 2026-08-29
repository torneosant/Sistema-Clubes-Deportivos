@extends('layouts.app')

@section('titulo', 'Contabilidad')

@section('contenido')

{{-- =========================================================
     ENCABEZADO
========================================================= --}}

<x-page-header
    title="💰 Contabilidad"
    subtitle="Administra los ingresos, gastos y cuentas pendientes del club."
/>


{{-- =========================================================
     MENSAJE DE ÉXITO
========================================================= --}}

@if(session('success'))

    <div class="mb-5 rounded-xl bg-green-50 border border-green-200
                text-green-700 px-4 py-3 text-sm">

        {{ session('success') }}

    </div>

@endif


{{-- =========================================================
     ERRORES
========================================================= --}}

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
     RESUMEN FINANCIERO
========================================================= --}}

<div class="bg-white rounded-xl shadow-sm
            border border-gray-200 mb-6">

    <div class="px-5 py-3 border-b bg-gray-50">

        <h3 class="text-sm font-semibold text-slate-700">
            💰 Resumen financiero
        </h3>

    </div>


    <div class="grid grid-cols-2 md:grid-cols-4
                divide-x divide-gray-200">


        {{-- INGRESOS --}}

        <div class="px-5 py-4">

            <div class="text-xs text-gray-500">
                Ingresos
            </div>

            <div class="text-xl font-bold text-green-700 mt-1">

                ${{ number_format(
                    $ingresos ?? 0,
                    0,
                    ',',
                    '.'
                ) }}

            </div>

        </div>


        {{-- GASTOS --}}

        <div class="px-5 py-4">

            <div class="text-xs text-gray-500">
                Gastos
            </div>

            <div class="text-xl font-bold text-red-700 mt-1">

                ${{ number_format(
                    $gastos ?? 0,
                    0,
                    ',',
                    '.'
                ) }}

            </div>

        </div>


        {{-- SALDO --}}

        <div class="px-5 py-4">

            <div class="text-xs text-gray-500">
                Saldo
            </div>

            <div class="text-xl font-bold text-blue-700 mt-1">

                ${{ number_format(
                    $saldo ?? 0,
                    0,
                    ',',
                    '.'
                ) }}

            </div>

        </div>


        {{-- PENDIENTE --}}

        <div class="px-5 py-4">

            <div class="text-xs text-gray-500">
                Por cobrar
            </div>

            <div class="text-xl font-bold text-red-600 mt-1">

                ${{ number_format(
                    $totalPendiente ?? 0,
                    0,
                    ',',
                    '.'
                ) }}

            </div>

        </div>

    </div>

</div>


{{-- =========================================================
     ACCIÓN PRINCIPAL
========================================================= --}}

<div class="flex justify-end mb-5">

    <a
        href="{{ route('contabilidad.create') }}"
        class="inline-flex items-center gap-2
               bg-green-600 hover:bg-green-700
               text-white px-5 py-2.5
               rounded-lg text-sm font-semibold
               shadow-sm transition"
    >

        ➕ Nuevo movimiento

    </a>

</div>


{{-- =========================================================
     PENDIENTES DE PAGO
========================================================= --}}

<div class="bg-white rounded-xl shadow-lg
            overflow-hidden mb-6">


    {{-- CABECERA --}}

    <div class="bg-red-50 border-b border-red-100
                px-5 py-4">

        <div class="flex items-center justify-between">

            <div>

                <h3 class="font-bold text-red-800 text-base">

                    🔴 Pendientes de pago

                </h3>

                <p class="text-xs text-red-600 mt-1">

                    Obligaciones de jugadores que todavía
                    tienen saldo pendiente.

                </p>

            </div>


            <span
                class="text-xs font-semibold
                       bg-red-100 text-red-700
                       px-3 py-1 rounded-full"
            >

                {{ $cargosPendientes->count() }}

                {{ $cargosPendientes->count() == 1
                    ? 'pendiente'
                    : 'pendientes' }}

            </span>

        </div>

    </div>


    <div class="p-4">

        @if($cargosPendientes->count())


            <div class="overflow-x-auto
                        border rounded-xl">

                <table class="min-w-full text-sm">

                    <thead class="bg-gray-100">

                        <tr>

                            <th class="px-4 py-3 text-left">
                                Jugador
                            </th>

                            <th class="px-4 py-3 text-left">
                                Concepto
                            </th>

                            <th class="px-4 py-3 text-center">
                                Periodo
                            </th>

                            <th class="px-4 py-3 text-right">
                                Valor
                            </th>

                            <th class="px-4 py-3 text-right">
                                Pagado
                            </th>

                            <th class="px-4 py-3 text-right">
                                Pendiente
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach($cargosPendientes as $cargo)

                            @php

                                $valorCargo =
                                    (float) $cargo->valor;

                                $valorPagado =
                                    (float) $cargo->valor_pagado;

                                $pendiente =
                                    max(
                                        0,
                                        $valorCargo -
                                        $valorPagado
                                    );

                            @endphp


                            <tr
                                class="border-t
                                       hover:bg-red-50/40
                                       transition"
                            >


                                {{-- JUGADOR --}}

                                <td class="px-4 py-3">

                                    @if($cargo->jugador)

                                        <div
                                            class="font-semibold
                                                   text-gray-800"
                                        >

                                            {{ $cargo->jugador->apellidos }}
                                            {{ $cargo->jugador->nombres }}

                                        </div>

                                    @else

                                        <span class="text-gray-400">
                                            Sin jugador
                                        </span>

                                    @endif

                                </td>


                                {{-- CONCEPTO --}}

                                <td class="px-4 py-3">

                                    <span
                                        class="font-medium
                                               text-gray-700"
                                    >

                                        {{ $cargo->concepto?->nombre
                                            ?? 'Sin concepto' }}

                                    </span>

                                </td>


                                {{-- PERIODO --}}

                                <td
                                    class="px-4 py-3
                                           text-center
                                           whitespace-nowrap"
                                >

                                    {{ $cargo->periodo ?? '—' }}

                                </td>


                                {{-- VALOR --}}

                                <td
                                    class="px-4 py-3
                                           text-right
                                           whitespace-nowrap"
                                >

                                    ${{ number_format(
                                        $valorCargo,
                                        0,
                                        ',',
                                        '.'
                                    ) }}

                                </td>


                                {{-- PAGADO --}}

                                <td
                                    class="px-4 py-3
                                           text-right
                                           text-green-700
                                           font-semibold
                                           whitespace-nowrap"
                                >

                                    ${{ number_format(
                                        $valorPagado,
                                        0,
                                        ',',
                                        '.'
                                    ) }}

                                </td>


                                {{-- PENDIENTE --}}

                                <td
                                    class="px-4 py-3
                                           text-right
                                           text-red-600
                                           font-bold
                                           whitespace-nowrap"
                                >

                                    ${{ number_format(
                                        $pendiente,
                                        0,
                                        ',',
                                        '.'
                                    ) }}

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


        @else

            {{-- SIN PENDIENTES --}}

            <div
                class="rounded-xl
                       border border-green-200
                       bg-green-50
                       px-5 py-8
                       text-center"
            >

                <div class="text-3xl mb-2">
                    ✅
                </div>

                <div
                    class="font-semibold
                           text-green-800"
                >

                    No hay pagos pendientes

                </div>

                <div
                    class="text-xs
                           text-green-700 mt-1"
                >

                    Todas las obligaciones registradas
                    están al día.

                </div>

            </div>

        @endif

    </div>

</div>


{{-- =========================================================
     MOVIMIENTOS CONTABLES
========================================================= --}}

<div class="bg-white rounded-xl shadow-lg
            overflow-hidden">


    {{-- CABECERA --}}

    <div class="bg-slate-800 text-white
                px-5 py-4">

        <div class="flex items-center justify-between">

            <div>

                <h3 class="font-bold text-base">

                    💳 Movimientos contables

                </h3>

                <p class="text-xs text-slate-300 mt-1">

                    Historial de ingresos y egresos
                    registrados en el club.

                </p>

            </div>


            <span
                class="text-xs bg-slate-700
                       px-3 py-1 rounded-full"
            >

                {{ $movimientos->count() }}

                {{ $movimientos->count() == 1
                    ? 'movimiento'
                    : 'movimientos' }}

            </span>

        </div>

    </div>


    <div class="p-4">


        {{-- FILTROS --}}

        <form
            method="GET"
            action="{{ route('contabilidad.index') }}"
            class="mb-5"
        >

            <div
                class="grid grid-cols-1
                       md:grid-cols-2
                       lg:grid-cols-5
                       gap-3"
            >


                {{-- DESDE --}}

                <div>

                    <label
                        class="block text-xs
                               font-semibold
                               text-gray-600 mb-1"
                    >

                        Desde

                    </label>

                    <input
                        type="date"
                        name="desde"
                        value="{{ request('desde') }}"
                        class="w-full border
                               border-gray-300
                               rounded-lg px-3 py-2
                               text-sm"
                    >

                </div>


                {{-- HASTA --}}

                <div>

                    <label
                        class="block text-xs
                               font-semibold
                               text-gray-600 mb-1"
                    >

                        Hasta

                    </label>

                    <input
                        type="date"
                        name="hasta"
                        value="{{ request('hasta') }}"
                        class="w-full border
                               border-gray-300
                               rounded-lg px-3 py-2
                               text-sm"
                    >

                </div>


                {{-- TIPO --}}

                <div>

                    <label
                        class="block text-xs
                               font-semibold
                               text-gray-600 mb-1"
                    >

                        Tipo

                    </label>

                    <select
                        name="tipo"
                        class="w-full border
                               border-gray-300
                               rounded-lg px-3 py-2
                               text-sm bg-white"
                    >

                        <option value="">
                            Todos
                        </option>

                        <option
                            value="Ingreso"
                            @selected(
                                request('tipo') === 'Ingreso'
                            )
                        >
                            Ingresos
                        </option>

                        <option
                            value="Egreso"
                            @selected(
                                request('tipo') === 'Egreso'
                            )
                        >
                            Gastos
                        </option>

                    </select>

                </div>


                {{-- CONCEPTO --}}

                <div>

                    <label
                        class="block text-xs
                               font-semibold
                               text-gray-600 mb-1"
                    >

                        Concepto

                    </label>

                    <select
                        name="concepto"
                        class="w-full border
                               border-gray-300
                               rounded-lg px-3 py-2
                               text-sm bg-white"
                    >

                        <option value="">
                            Todos
                        </option>


                        @foreach($conceptos as $concepto)

                            <option
                                value="{{ $concepto->id }}"
                                @selected(
                                    (string)request('concepto')
                                    ===
                                    (string)$concepto->id
                                )
                            >

                                {{ $concepto->nombre }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- BOTONES --}}

                <div class="flex items-end gap-2">

                    <button
                        type="submit"
                        class="flex-1
                               bg-blue-600
                               hover:bg-blue-700
                               text-white
                               px-4 py-2
                               rounded-lg
                               text-sm
                               font-semibold"
                    >

                        🔍 Filtrar

                    </button>


                    <a
                        href="{{ route('contabilidad.index') }}"
                        class="bg-gray-200
                               hover:bg-gray-300
                               text-gray-700
                               px-4 py-2
                               rounded-lg
                               text-sm
                               font-semibold"
                    >

                        Limpiar

                    </a>

                </div>

            </div>

        </form>


        {{-- TABLA --}}

        <div
            class="overflow-x-auto
                   border rounded-xl"
        >

            <table class="min-w-full text-sm">

                <thead class="bg-gray-100">

                    <tr>

                        <th class="px-4 py-3 text-center">
                            Fecha
                        </th>

                        <th class="px-4 py-3 text-center">
                            Tipo
                        </th>

                        <th class="px-4 py-3 text-left">
                            Concepto
                        </th>

                        <th class="px-4 py-3 text-left">
                            Jugador
                        </th>

                        <th class="px-4 py-3 text-left">
                            Pagador / Beneficiario
                        </th>

                        <th class="px-4 py-3 text-right">
                            Valor
                        </th>

                        <th class="px-4 py-3 text-left">
                            Método
                        </th>

                        <th class="px-4 py-3 text-left">
                            Periodo
                        </th>

                        <th class="px-4 py-3 text-left">
                            Observaciones
                        </th>

                        <th class="px-4 py-3 text-center">
                            Acciones
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($movimientos as $movimiento)

                        <tr
                            class="border-t
                                   hover:bg-gray-50
                                   transition"
                        >


                            {{-- FECHA --}}

                            <td
                                class="px-4 py-3
                                       text-center
                                       whitespace-nowrap"
                            >

                                {{ $movimiento->fecha?->format(
                                    'd/m/Y'
                                ) ?? '—' }}

                            </td>


                            {{-- TIPO --}}

                            <td class="px-4 py-3 text-center">

                                @if(
                                    $movimiento->tipo === 'Ingreso'
                                )

                                    <span
                                        class="inline-flex
                                               px-2 py-1
                                               rounded-full
                                               bg-green-100
                                               text-green-700
                                               text-xs
                                               font-semibold"
                                    >

                                        Ingreso

                                    </span>

                                @else

                                    <span
                                        class="inline-flex
                                               px-2 py-1
                                               rounded-full
                                               bg-red-100
                                               text-red-700
                                               text-xs
                                               font-semibold"
                                    >

                                        Egreso

                                    </span>

                                @endif

                            </td>


                            {{-- CONCEPTO --}}

                            <td class="px-4 py-3">

                                {{ $movimiento->concepto?->nombre
                                    ?? '—' }}

                            </td>


                            {{-- JUGADOR --}}

                            <td class="px-4 py-3">

                                @if($movimiento->jugador)

                                    <span class="font-medium">

                                        {{ $movimiento->jugador->apellidos }}
                                        {{ $movimiento->jugador->nombres }}

                                    </span>

                                @else

                                    —

                                @endif

                            </td>


                            {{-- TERCERO --}}

                            <td class="px-4 py-3">

                                {{ $movimiento->tercero ?? '—' }}

                            </td>


                            {{-- VALOR --}}

                            <td
                                class="px-4 py-3
                                       text-right
                                       font-semibold
                                       whitespace-nowrap
                                       {{ $movimiento->tipo === 'Ingreso'
                                            ? 'text-green-700'
                                            : 'text-red-700' }}"
                            >

                                ${{ number_format(
                                    $movimiento->valor,
                                    0,
                                    ',',
                                    '.'
                                ) }}

                            </td>


                            {{-- MÉTODO --}}

                            <td class="px-4 py-3">

                                {{ $movimiento->metodo_pago ?? '—' }}

                            </td>


                            {{-- PERIODO --}}

                            <td class="px-4 py-3">

                                {{ $movimiento->periodo ?? '—' }}

                            </td>


                            {{-- OBSERVACIONES --}}

                            <td class="px-4 py-3">

                                {{ $movimiento->observaciones ?? '—' }}

                            </td>


                            {{-- ACCIONES --}}

                            <td class="px-4 py-3">

                                <div
                                    class="flex justify-center
                                           items-center gap-2"
                                >

                                    {{-- EDITAR --}}

                                    <a
                                        href="{{ route(
                                            'contabilidad.edit',
                                            $movimiento
                                        ) }}"
                                        title="Editar movimiento"
                                        class="inline-flex
                                               items-center
                                               justify-center
                                               w-8 h-8
                                               rounded-lg
                                               bg-blue-50
                                               text-blue-600
                                               hover:bg-blue-100
                                               transition"
                                    >

                                        ✏️

                                    </a>


                                    {{-- ELIMINAR --}}

                                    <form
                                        action="{{ route(
                                            'contabilidad.destroy',
                                            $movimiento
                                        ) }}"
                                        method="POST"
                                        class="inline"
                                        onsubmit="return confirm(
                                            '¿Deseas eliminar este movimiento?'
                                        );"
                                    >

                                        @csrf

                                        @method('DELETE')


                                        <button
                                            type="submit"
                                            title="Eliminar movimiento"
                                            class="inline-flex
                                                   items-center
                                                   justify-center
                                                   w-8 h-8
                                                   rounded-lg
                                                   bg-red-50
                                                   text-red-600
                                                   hover:bg-red-100
                                                   transition"
                                        >

                                            🗑️

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="10"
                                class="px-4 py-10
                                       text-center
                                       text-gray-500"
                            >

                                <div class="text-3xl mb-2">
                                    💳
                                </div>

                                <div class="font-semibold">
                                    No hay movimientos registrados.
                                </div>

                                <div class="text-xs mt-1">
                                    Los ingresos y egresos del club
                                    aparecerán aquí.
                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection
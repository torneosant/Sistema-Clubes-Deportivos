@extends('layouts.app')

@section('titulo','Ficha del Jugador')

@section('contenido')

<div class="max-w-7xl mx-auto px-4 sm:px-6 py-5">

    {{-- =========================================================
         CABECERA DEL JUGADOR
    ========================================================== --}}

    <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-6">

        <div class="h-28 bg-gradient-to-r from-blue-700 via-slate-800 to-slate-900"></div>

        <div class="px-6 pb-5">

            <div class="-mt-14 flex flex-col sm:flex-row sm:items-end gap-4">

                {{-- FOTO --}}

                @if($jugador->foto)

                    <img
                        src="{{ asset('storage/'.$jugador->foto) }}"
                        class="w-28 h-28 rounded-2xl border-4 border-white object-cover shadow-lg"
                    >

                @else

                    <div class="w-28 h-28 rounded-2xl border-4 border-white bg-slate-200 flex items-center justify-center shadow-lg">

                        <span class="text-4xl font-bold text-slate-600">
                            {{ strtoupper(substr($jugador->nombres,0,1)) }}
                        </span>

                    </div>

                @endif


                {{-- NOMBRE --}}

                <div class="flex-1 pb-1">

                    <h1 class="text-2xl font-bold text-slate-800">
                        {{ $jugador->nombres }} {{ $jugador->apellidos }}
                    </h1>

                    <div class="flex flex-wrap gap-2 mt-2">

                        @if($jugador->categoria)

                            <span class="text-xs bg-blue-100 text-blue-700 px-3 py-1 rounded-full">
                                {{ $jugador->categoria->nombre }}
                            </span>

                        @endif

                        @if($jugador->equipo)

                            <span class="text-xs bg-purple-100 text-purple-700 px-3 py-1 rounded-full">
                                {{ $jugador->equipo->nombre }}
                            </span>

                        @endif

                        @if($jugador->posicion)

                            <span class="text-xs bg-slate-100 text-slate-700 px-3 py-1 rounded-full">
                                {{ $jugador->posicion }}
                            </span>

                        @endif

                        @if($jugador->activo)

                            <span class="text-xs bg-green-100 text-green-700 px-3 py-1 rounded-full">
                                Activo
                            </span>

                        @else

                            <span class="text-xs bg-red-100 text-red-700 px-3 py-1 rounded-full">
                                Inactivo
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         ESTRUCTURA PRINCIPAL
    ========================================================== --}}

    <div class="grid grid-cols-12 gap-5">


        {{-- =====================================================
             MENU
        ====================================================== --}}

        <aside class="col-span-12 lg:col-span-3">

            <div class="bg-white rounded-2xl shadow-lg overflow-hidden lg:sticky lg:top-5">

                <div class="bg-slate-800 text-white px-4 py-3 font-bold">
                    👤 Gestión del jugador
                </div>

                <div class="p-2">

                    <button
                        type="button"
                        onclick="mostrarPanel('info')"
                        class="menu-btn w-full text-left px-4 py-2.5 rounded-lg hover:bg-blue-50 transition">
                        👤 Información
                    </button>

                    <button
                        type="button"
                        onclick="mostrarPanel('medico')"
                        class="menu-btn w-full text-left px-4 py-2.5 rounded-lg hover:bg-red-50 transition">
                        ❤️ Médico
                    </button>

                    <button
                        type="button"
                        onclick="mostrarPanel('contabilidad')"
                        class="menu-btn w-full text-left px-4 py-2.5 rounded-lg hover:bg-green-50 transition">
                        💰 Contabilidad
                    </button>

                    <button
                        type="button"
                        onclick="mostrarPanel('estadisticas')"
                        class="menu-btn w-full text-left px-4 py-2.5 rounded-lg hover:bg-indigo-50 transition">
                        📊 Estadísticas
                    </button>

                    <button
                        type="button"
                        onclick="mostrarPanel('asistencia')"
                        class="menu-btn w-full text-left px-4 py-2.5 rounded-lg hover:bg-blue-50 transition">
                        📅 Asistencia
                    </button>

                    <button
                        type="button"
                        onclick="mostrarPanel('documentos')"
                        class="menu-btn w-full text-left px-4 py-2.5 rounded-lg hover:bg-gray-100 transition">
                        📄 Documentos
                    </button>

                    <button
                        type="button"
                        onclick="mostrarPanel('observaciones')"
                        class="menu-btn w-full text-left px-4 py-2.5 rounded-lg hover:bg-orange-50 transition">
                        📝 Observaciones
                    </button>

                </div>

            </div>

        </aside>


        {{-- =====================================================
             CONTENIDO
        ====================================================== --}}

        <main class="col-span-12 lg:col-span-9">


            {{-- =================================================
                 INFORMACIÓN
            ================================================== --}}

            <div id="panel-info" class="panel-jugador space-y-5">


                {{-- DATOS PERSONALES --}}

                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">

                    <div class="bg-slate-800 text-white px-5 py-3 font-bold">
                        👤 Datos personales
                    </div>

                    <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">

                        <div>
                            <small class="text-gray-500">Documento</small>
                            <div class="font-semibold">
                                {{ $jugador->numero_documento ?: '-' }}
                            </div>
                        </div>

                        <div>
                            <small class="text-gray-500">Fecha de nacimiento</small>
                            <div class="font-semibold">
                                {{ optional($jugador->fecha_nacimiento)->format('d/m/Y') ?: '-' }}
                            </div>
                        </div>

                        <div>
                            <small class="text-gray-500">Edad</small>
                            <div class="font-semibold">
                                {{ $jugador->fecha_nacimiento ? $jugador->fecha_nacimiento->age.' años' : '-' }}
                            </div>
                        </div>

                        <div>
                            <small class="text-gray-500">Teléfono</small>
                            <div class="font-semibold">
                                {{ $jugador->telefono ?: '-' }}
                            </div>
                        </div>

                        <div>
                            <small class="text-gray-500">Correo</small>
                            <div class="font-semibold break-all">
                                {{ $jugador->email ?: '-' }}
                            </div>
                        </div>

                        <div>
                            <small class="text-gray-500">Ciudad</small>
                            <div class="font-semibold">
                                {{ $jugador->ciudad ?: '-' }}
                            </div>
                        </div>

                    </div>

                </div>


                {{-- INFORMACIÓN DEPORTIVA --}}

                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">

                    <div class="bg-green-700 text-white px-5 py-3 font-bold">
                        ⚽ Información deportiva
                    </div>

                    <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">

                        <div>
                            <small class="text-gray-500">Equipo</small>
                            <div class="font-semibold">
                                {{ optional($jugador->equipo)->nombre ?: '-' }}
                            </div>
                        </div>

                        <div>
                            <small class="text-gray-500">Categoría</small>
                            <div class="font-semibold">
                                {{ optional($jugador->categoria)->nombre ?: '-' }}
                            </div>
                        </div>

                        <div>
                            <small class="text-gray-500">Posición</small>
                            <div class="font-semibold">
                                {{ $jugador->posicion ?: '-' }}
                            </div>
                        </div>

                        <div>
                            <small class="text-gray-500">Pierna hábil</small>
                            <div class="font-semibold">
                                {{ $jugador->pierna_habil ?: '-' }}
                            </div>
                        </div>

                    </div>

                </div>


                {{-- ACUDIENTE --}}

                @php
                    $tieneAcudiente =
                        filled($jugador->acudiente_nombre ?? null) ||
                        filled($jugador->nombre_acudiente ?? null) ||
                        filled($jugador->acudiente ?? null) ||
                        filled($jugador->acudiente_telefono ?? null) ||
                        filled($jugador->telefono_acudiente ?? null);
                @endphp

                @if($tieneAcudiente)

                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">

                        <div class="bg-blue-700 text-white px-5 py-3 font-bold">
                            👨‍👩‍👧 Acudiente
                        </div>

                        <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">

                            <div>
                                <small class="text-gray-500">Nombre</small>

                                <div class="font-semibold">
                                    {{ $jugador->acudiente_nombre
                                        ?? $jugador->nombre_acudiente
                                        ?? $jugador->acudiente
                                        ?? '-' }}
                                </div>
                            </div>

                            <div>
                                <small class="text-gray-500">Parentesco</small>

                                <div class="font-semibold">
                                    {{ $jugador->acudiente_parentesco
                                        ?? $jugador->parentesco_acudiente
                                        ?? '-' }}
                                </div>
                            </div>

                            <div>
                                <small class="text-gray-500">Teléfono</small>

                                <div class="font-semibold">
                                    {{ $jugador->acudiente_telefono
                                        ?? $jugador->telefono_acudiente
                                        ?? '-' }}
                                </div>
                            </div>

                            <div>
                                <small class="text-gray-500">Correo</small>

                                <div class="font-semibold break-all">
                                    {{ $jugador->acudiente_email
                                        ?? $jugador->email_acudiente
                                        ?? '-' }}
                                </div>
                            </div>

                        </div>

                    </div>

                @endif


                {{-- INFORMACIÓN MÉDICA BÁSICA --}}

                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">

                    <div class="bg-red-700 text-white px-5 py-3 font-bold">
                        ❤️ Información médica
                    </div>

                    <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">

                        <div>
                            <small class="text-gray-500">EPS</small>

                            <div class="font-semibold">
                                {{ $jugador->eps ?: '-' }}
                            </div>
                        </div>

                        <div>
                            <small class="text-gray-500">Tipo de sangre</small>

                            <div class="font-semibold">
                                {{ $jugador->tipo_sangre ?: '-' }}
                            </div>
                        </div>

                        <div class="sm:col-span-2">

                            <small class="text-gray-500">
                                Alergias
                            </small>

                            <div class="bg-gray-50 rounded-lg p-3 mt-1">
                                {{ $jugador->alergias ?: 'Sin información' }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 MÉDICO
            ================================================== --}}

            <div id="panel-medico" class="panel-jugador hidden">

                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">

                    <div class="flex justify-between items-center p-5 border-b">

                        <div>
                            <h2 class="font-bold text-lg">
                                ❤️ Historial médico
                            </h2>

                            <p class="text-xs text-gray-500 mt-1">
                                Registros médicos del jugador.
                            </p>
                        </div>

                        @if(auth()->user()->tienePermiso('historial_medico.crear'))

                            <a
                                href="{{ route('historial-medico.create',$jugador) }}"
                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm">
                                + Nuevo registro
                            </a>

                        @endif

                    </div>

                    <div class="overflow-x-auto">

                        <table class="w-full text-sm">

                            <thead class="bg-gray-100">

                                <tr>
                                    <th class="p-3 text-left">Fecha</th>
                                    <th class="p-3 text-left">Tipo</th>
                                    <th class="p-3 text-left">Diagnóstico</th>
                                    <th class="p-3 text-left">Estado</th>
                                </tr>

                            </thead>

                            <tbody>

                            @forelse($historiales as $h)

                                <tr class="border-b">

                                    <td class="p-3">
                                        {{ \Carbon\Carbon::parse($h->fecha)->format('d/m/Y') }}
                                    </td>

                                    <td class="p-3">
                                        {{ $h->tipo }}
                                    </td>

                                    <td class="p-3">
                                        {{ $h->diagnostico }}
                                    </td>

                                    <td class="p-3">
                                        {{ $h->estado }}
                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="4"
                                        class="text-center p-8 text-gray-500">
                                        No hay registros médicos.
                                    </td>
                                </tr>

                            @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

{{-- =====================================================
     CONTABILIDAD DEL JUGADOR
     SOLO CONSULTA
====================================================== --}}

<div id="panel-contabilidad" class="panel-jugador hidden">

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">

        {{-- ENCABEZADO --}}
        <div class="bg-emerald-700 text-white px-5 py-3">

            <h3 class="font-bold text-base">
                💰 Estado financiero
            </h3>

            <p class="text-xs text-emerald-100 mt-1">
                Consulta de cargos, pagos y saldos del jugador.
            </p>

        </div>


        <div class="p-5">

            {{-- =================================================
                 RESUMEN FINANCIERO
            ================================================== --}}

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">

                {{-- TOTAL CARGOS --}}
                <div class="rounded-xl border border-blue-100 bg-blue-50 p-4">

                    <div class="text-xs text-blue-600">
                        Total cargos
                    </div>

                    <div class="text-xl font-bold text-blue-700 mt-1">
                        $
                        {{ number_format($totalCargos ?? 0, 0, ',', '.') }}
                    </div>

                </div>


                {{-- TOTAL PAGADO --}}
                <div class="rounded-xl border border-green-100 bg-green-50 p-4">

                    <div class="text-xs text-green-600">
                        Total pagado
                    </div>

                    <div class="text-xl font-bold text-green-700 mt-1">
                        $
                        {{ number_format($totalPagadoCargos ?? 0, 0, ',', '.') }}
                    </div>

                </div>


                {{-- TOTAL PENDIENTE --}}
                <div class="rounded-xl border border-red-100 bg-red-50 p-4">

                    <div class="text-xs text-red-600">
                        Total pendiente
                    </div>

                    <div class="text-xl font-bold text-red-600 mt-1">
                        $
                        {{ number_format($totalPendiente ?? 0, 0, ',', '.') }}
                    </div>

                </div>


                {{-- TOTAL EXONERADO --}}
                <div class="rounded-xl border border-purple-100 bg-purple-50 p-4">

                    <div class="text-xs text-purple-600">
                        Total exonerado
                    </div>

                    <div class="text-xl font-bold text-purple-700 mt-1">
                        $
                        {{ number_format($totalExonerado ?? 0, 0, ',', '.') }}
                    </div>

                </div>

            </div>


            {{-- =================================================
                 ESTADO GENERAL
            ================================================== --}}

            <div class="mt-4">

                @if(($totalPendiente ?? 0) > 0)

                    <div class="rounded-xl border border-red-200 bg-red-50
                                px-4 py-3">

                        <div class="flex items-center gap-3">

                            <div class="text-xl">
                                🔴
                            </div>

                            <div>

                                <div class="font-semibold text-red-700">
                                    Tiene saldo pendiente
                                </div>

                                <div class="text-xs text-red-600">
                                    Pendiente por pagar:
                                    <strong>
                                        $
                                        {{ number_format($totalPendiente, 0, ',', '.') }}
                                    </strong>
                                </div>

                            </div>

                        </div>

                    </div>

                @else

                    <div class="rounded-xl border border-green-200 bg-green-50
                                px-4 py-3">

                        <div class="flex items-center gap-3">

                            <div class="text-xl">
                                ✅
                            </div>

                            <div>

                                <div class="font-semibold text-green-700">
                                    Cuenta al día
                                </div>

                                <div class="text-xs text-green-600">
                                    No tiene saldos pendientes.
                                </div>

                            </div>

                        </div>

                    </div>

                @endif

            </div>


            {{-- =================================================
                 CARGOS
            ================================================== --}}

            <div class="mt-6">

                <div class="mb-3">

                    <h4 class="font-bold text-gray-800">
                        📋 Cargos del jugador
                    </h4>

                    <p class="text-xs text-gray-500">
                        Conceptos que han sido cobrados al jugador.
                    </p>

                </div>


                @if(isset($cargos) && $cargos->count())

                    <div class="overflow-x-auto border rounded-xl">

                        <table class="min-w-full text-sm">

                            <thead class="bg-gray-100">

                                <tr>

                                    <th class="px-4 py-3 text-left">
                                        Fecha
                                    </th>

                                    <th class="px-4 py-3 text-left">
                                        Periodo
                                    </th>

                                    <th class="px-4 py-3 text-left">
                                        Concepto
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

                                    <th class="px-4 py-3 text-center">
                                        Estado
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach($cargos as $cargo)

                                    @php

                                        $pendiente = max(
                                            0,
                                            (float) $cargo->valor -
                                            (float) $cargo->valor_pagado
                                        );

                                    @endphp

                                    <tr class="border-t hover:bg-gray-50">

                                        {{-- FECHA --}}
                                        <td class="px-4 py-3 whitespace-nowrap">

                                            {{ $cargo->fecha?->format('d/m/Y') ?? '—' }}

                                        </td>


                                        {{-- PERIODO --}}
                                        <td class="px-4 py-3 font-medium">

                                            {{ $cargo->periodo ?: '—' }}

                                        </td>


                                        {{-- CONCEPTO --}}
                                        <td class="px-4 py-3">

                                            {{ $cargo->concepto?->nombre ?? 'Sin concepto' }}

                                        </td>


                                        {{-- VALOR --}}
                                        <td class="px-4 py-3 text-right font-semibold">

                                            $
                                            {{ number_format($cargo->valor, 0, ',', '.') }}

                                        </td>


                                        {{-- PAGADO --}}
                                        <td class="px-4 py-3 text-right
                                                   text-green-700 font-semibold">

                                            $
                                            {{ number_format($cargo->valor_pagado, 0, ',', '.') }}

                                        </td>


                                        {{-- PENDIENTE --}}
                                        <td class="px-4 py-3 text-right font-semibold
                                            {{ $pendiente > 0
                                                ? 'text-red-600'
                                                : 'text-green-600' }}">

                                            $
                                            {{ number_format($pendiente, 0, ',', '.') }}

                                        </td>


                                        {{-- ESTADO --}}
                                        <td class="px-4 py-3 text-center">

                                            @if($cargo->estado === 'Pagado')

                                                <span class="inline-flex px-2 py-1
                                                             rounded-full
                                                             bg-green-100
                                                             text-green-700
                                                             text-xs
                                                             font-semibold">

                                                    ✅ Pagado

                                                </span>

                                            @elseif($cargo->estado === 'Exonerado')

                                                <span class="inline-flex px-2 py-1
                                                             rounded-full
                                                             bg-purple-100
                                                             text-purple-700
                                                             text-xs
                                                             font-semibold">

                                                    🎁 Exonerado

                                                </span>

                                            @elseif($cargo->estado === 'Anulado')

                                                <span class="inline-flex px-2 py-1
                                                             rounded-full
                                                             bg-gray-100
                                                             text-gray-600
                                                             text-xs
                                                             font-semibold">

                                                    🚫 Anulado

                                                </span>

                                            @elseif($cargo->valor_pagado > 0)

                                                <span class="inline-flex px-2 py-1
                                                             rounded-full
                                                             bg-yellow-100
                                                             text-yellow-700
                                                             text-xs
                                                             font-semibold">

                                                    🟡 Parcial

                                                </span>

                                            @else

                                                <span class="inline-flex px-2 py-1
                                                             rounded-full
                                                             bg-red-100
                                                             text-red-700
                                                             text-xs
                                                             font-semibold">

                                                    🔴 Pendiente

                                                </span>

                                            @endif

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                @else

                    <div class="border rounded-xl bg-gray-50 p-6 text-center">

                        <div class="text-3xl mb-2">
                            📋
                        </div>

                        <p class="font-semibold text-gray-700">
                            No hay cargos registrados
                        </p>

                        <p class="text-xs text-gray-500 mt-1">
                            Los cargos se administran desde el módulo de Contabilidad.
                        </p>

                    </div>

                @endif

            </div>


            {{-- =================================================
                 PAGOS REGISTRADOS
            ================================================== --}}

            <div class="mt-6">

                <div class="mb-3">

                    <h4 class="font-bold text-gray-800">
                        💵 Pagos registrados
                    </h4>

                    <p class="text-xs text-gray-500">
                        Historial de pagos realizados por el jugador.
                    </p>

                </div>


                @php

                    $pagosJugador = $movimientos
                        ->where('tipo', 'Ingreso');

                @endphp


                @if($pagosJugador->count())

                    <div class="overflow-x-auto border rounded-xl">

                        <table class="min-w-full text-sm">

                            <thead class="bg-gray-100">

                                <tr>

                                    <th class="px-4 py-3 text-left">
                                        Fecha
                                    </th>

                                    <th class="px-4 py-3 text-left">
                                        Periodo
                                    </th>

                                    <th class="px-4 py-3 text-left">
                                        Concepto
                                    </th>

                                    <th class="px-4 py-3 text-left">
                                        Método
                                    </th>

                                    <th class="px-4 py-3 text-right">
                                        Valor
                                    </th>

                                    <th class="px-4 py-3 text-center">
                                        Estado
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach($pagosJugador as $pago)

                                    <tr class="border-t hover:bg-gray-50">

                                        {{-- FECHA --}}
                                        <td class="px-4 py-3 whitespace-nowrap">

                                            {{ $pago->fecha?->format('d/m/Y') ?? '—' }}

                                        </td>


                                        {{-- PERIODO --}}
                                        <td class="px-4 py-3">

                                            {{ $pago->periodo ?: '—' }}

                                        </td>


                                        {{-- CONCEPTO --}}
                                        <td class="px-4 py-3">

                                            {{ $pago->concepto?->nombre ?? 'Sin concepto' }}

                                        </td>


                                        {{-- METODO --}}
                                        <td class="px-4 py-3">

                                            {{ $pago->metodo_pago ?: '—' }}

                                        </td>


                                        {{-- VALOR --}}
                                        <td class="px-4 py-3 text-right
                                                   font-semibold text-green-700">

                                            $
                                            {{ number_format($pago->valor, 0, ',', '.') }}

                                        </td>


                                        {{-- ESTADO --}}
                                        <td class="px-4 py-3 text-center">

                                            <span class="inline-flex px-2 py-1
                                                         rounded-full
                                                         bg-green-100
                                                         text-green-700
                                                         text-xs
                                                         font-semibold">

                                                {{ $pago->estado ?? 'Pagado' }}

                                            </span>

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                @else

                    <div class="border rounded-xl bg-gray-50 p-5 text-center">

                        <p class="text-sm text-gray-500">
                            No hay pagos registrados.
                        </p>

                    </div>

                @endif

            </div>


            {{-- NOTA --}}
            <div class="mt-5 rounded-lg bg-blue-50 border border-blue-100
                        px-4 py-3 text-xs text-blue-700">

                ℹ️ Los cargos, pagos, exoneraciones y demás movimientos
                se administran desde el módulo de <strong>Contabilidad</strong>.

            </div>

        </div>

    </div>

</div>


            {{-- =================================================
                 ESTADÍSTICAS
            ================================================== --}}

            <div id="panel-estadisticas" class="panel-jugador hidden">

                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">

                    <div class="bg-indigo-700 text-white px-5 py-3 font-bold">
                        📊 Estadísticas deportivas
                    </div>


                    @php

                        $titulares = $partidosTitular ?? (
                            isset($detallePartidos)
                                ? $detallePartidos->where('titular', true)->count()
                                : 0
                        );

                        $suplentes = $partidosSuplente ?? (
                            isset($detallePartidos)
                                ? $detallePartidos->where('participacion', 'Suplente')->count()
                                : 0
                        );

                    @endphp


                    <div class="p-5">


                        {{-- TARJETAS DE ESTADÍSTICAS --}}

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-3">

                            <div class="bg-blue-50 border border-blue-100 rounded-xl p-3 text-center">

                                <div class="text-xs text-gray-500">
                                    Partidos
                                </div>

                                <div class="text-2xl font-bold text-blue-700">
                                    {{ $partidosJugados }}
                                </div>

                            </div>


                            <div class="bg-green-50 border border-green-100 rounded-xl p-3 text-center">

                                <div class="text-xs text-gray-500">
                                    11 inicialista
                                </div>

                                <div class="text-2xl font-bold text-green-700">
                                    {{ $titulares }}
                                </div>

                            </div>


                            <div class="bg-purple-50 border border-purple-100 rounded-xl p-3 text-center">

                                <div class="text-xs text-gray-500">
                                    Suplente
                                </div>

                                <div class="text-2xl font-bold text-purple-700">
                                    {{ $suplentes }}
                                </div>

                            </div>


                            <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-3 text-center">

                                <div class="text-xs text-gray-500">
                                    Minutos
                                </div>

                                <div class="text-2xl font-bold text-emerald-700">
                                    {{ $minutosJugados }}
                                </div>

                            </div>

                        </div>


                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-3">

                            <div class="bg-yellow-50 border border-yellow-100 rounded-xl p-3 text-center">

                                <div class="text-xs text-gray-500">
                                    Goles
                                </div>

                                <div class="text-2xl font-bold text-yellow-600">
                                    ⚽ {{ $goles }}
                                </div>

                            </div>


                            <div class="bg-cyan-50 border border-cyan-100 rounded-xl p-3 text-center">

                                <div class="text-xs text-gray-500">
                                    Asistencias
                                </div>

                                <div class="text-2xl font-bold text-cyan-700">
                                    🎯 {{ $asistenciasDeGol }}
                                </div>

                            </div>


                            <div class="bg-gray-50 border border-gray-200 rounded-xl p-3 text-center">

                                <div class="text-xs text-gray-500">
                                    Amarillas
                                </div>

                                <div class="text-2xl font-bold text-yellow-500">
                                    🟨 {{ $amarillas }}
                                </div>

                            </div>


                            <div class="bg-red-50 border border-red-100 rounded-xl p-3 text-center">

                                <div class="text-xs text-gray-500">
                                    Rojas
                                </div>

                                <div class="text-2xl font-bold text-red-600">
                                    🟥 {{ $rojas }}
                                </div>

                            </div>

                        </div>


                        {{-- FIGURAS --}}

                        <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-3 text-center mb-6">

                            <div class="text-xs text-gray-500">
                                Figura del partido
                            </div>

                            <div class="text-2xl font-bold text-indigo-700">
                                ⭐ {{ $figuras }}
                            </div>

                        </div>


                        {{-- HISTORIAL --}}

                        <div>

                            <div class="mb-3">

                                <h3 class="font-bold text-lg">
                                    📅 Historial de partidos
                                </h3>

                                <p class="text-xs text-gray-500">
                                    Registro de participación y estadísticas de cada partido.
                                </p>

                            </div>

                            <div class="border rounded-xl overflow-hidden">

                                <div class="overflow-x-auto">

                                    <table class="w-full text-xs">

                                        <thead class="bg-gray-100">

                                            <tr>

                                                <th class="p-2 text-left">
                                                    Fecha
                                                </th>

                                                <th class="p-2 text-left">
                                                    Competencia
                                                </th>

                                                <th class="p-2 text-left">
                                                    Rival
                                                </th>

                                                <th class="p-2 text-center">
                                                    Condición
                                                </th>

                                                <th class="p-2 text-center">
                                                    Inicialista
                                                </th>

                                                <th class="p-2 text-center">
                                                    Participación
                                                </th>

                                                <th class="p-2 text-center">
                                                    Min
                                                </th>

                                                <th class="p-2 text-center">
                                                    ⚽
                                                </th>

                                                <th class="p-2 text-center">
                                                    🎯
                                                </th>

                                                <th class="p-2 text-center">
                                                    🟨
                                                </th>

                                                <th class="p-2 text-center">
                                                    🟥
                                                </th>

                                                <th class="p-2 text-center">
                                                    ⭐
                                                </th>

                                            </tr>

                                        </thead>

                                        <tbody>

                                        @forelse($detallePartidos as $p)

                                            <tr class="border-t hover:bg-gray-50">

                                                <td class="p-2">
                                                    {{ \Carbon\Carbon::parse($p->partido->fecha)->format('d/m/Y') }}
                                                </td>

                                                <td class="p-2 font-medium">
                                                    {{ $p->partido->competencia ?: 'Amistoso' }}
                                                </td>

                                                <td class="p-2 font-medium">
                                                    {{ $p->partido->rival ?: '-' }}
                                                </td>

                                                <td class="p-2 text-center">
                                                    {{ $p->partido->condicion ?: '-' }}
                                                </td>

                                                <td class="p-2 text-center">

                                                    @if($p->titular)

                                                        <span class="text-green-600 font-bold">
                                                            ✓
                                                        </span>

                                                    @else

                                                        <span class="text-gray-400">
                                                            —
                                                        </span>

                                                    @endif

                                                </td>

                                                <td class="p-2 text-center">

                                                    @if($p->participacion === 'Titular')

                                                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full">
                                                            Titular
                                                        </span>

                                                    @elseif($p->participacion === 'Suplente')

                                                        <span class="bg-purple-100 text-purple-700 px-2 py-1 rounded-full">
                                                            Suplente
                                                        </span>

                                                    @else

                                                        <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded-full">
                                                            No jugó
                                                        </span>

                                                    @endif

                                                </td>

                                                <td class="p-2 text-center font-semibold">
                                                    {{ $p->minutos }}
                                                </td>

                                                <td class="p-2 text-center">
                                                    {{ $p->goles }}
                                                </td>

                                                <td class="p-2 text-center">
                                                    {{ $p->asistencias }}
                                                </td>

                                                <td class="p-2 text-center">
                                                    {{ $p->amarillas }}
                                                </td>

                                                <td class="p-2 text-center">
                                                    {{ $p->rojas }}
                                                </td>

                                                <td class="p-2 text-center">
                                                    {{ $p->figura ? '⭐' : '—' }}
                                                </td>

                                            </tr>

                                        @empty

                                            <tr>

                                                <td colspan="12"
                                                    class="text-center p-8 text-gray-500">

                                                    No existen estadísticas de partidos.

                                                </td>

                                            </tr>

                                        @endforelse

                                        </tbody>

                                    </table>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 ASISTENCIA
            ================================================== --}}

            <div id="panel-asistencia" class="panel-jugador hidden">

                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">

                    <div class="bg-blue-700 text-white px-5 py-3 font-bold">
                        📅 Historial de asistencia
                    </div>


                    <div class="p-5 grid grid-cols-2 md:grid-cols-5 gap-3">

                        <div class="bg-gray-50 rounded-xl p-3 text-center">
                            <div class="text-xs text-gray-500">
                                Registros
                            </div>
                            <div class="text-xl font-bold">
                                {{ $totalAsistencias }}
                            </div>
                        </div>

                        <div class="bg-green-50 rounded-xl p-3 text-center">
                            <div class="text-xs text-gray-500">
                                Presentes
                            </div>
                            <div class="text-xl font-bold text-green-600">
                                {{ $presentes }}
                            </div>
                        </div>

                        <div class="bg-red-50 rounded-xl p-3 text-center">
                            <div class="text-xs text-gray-500">
                                Ausentes
                            </div>
                            <div class="text-xl font-bold text-red-600">
                                {{ $ausentes }}
                            </div>
                        </div>

                        <div class="bg-yellow-50 rounded-xl p-3 text-center">
                            <div class="text-xs text-gray-500">
                                Permisos
                            </div>
                            <div class="text-xl font-bold text-yellow-600">
                                {{ $permisos }}
                            </div>
                        </div>

                        <div class="bg-cyan-50 rounded-xl p-3 text-center">
                            <div class="text-xs text-gray-500">
                                % asistencia
                            </div>
                            <div class="text-xl font-bold text-blue-700">
                                {{ $porcentajeAsistencia }}%
                            </div>
                        </div>

                    </div>


                    <div class="overflow-x-auto">

                        <table class="w-full text-sm">

                            <thead class="bg-gray-100">

                                <tr>

                                    <th class="p-3 text-left">
                                        Fecha
                                    </th>

                                    <th class="p-3 text-left">
                                        Entrenamiento
                                    </th>

                                    <th class="p-3 text-left">
                                        Estado
                                    </th>

                                    <th class="p-3 text-left">
                                        Observación
                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                            @forelse($asistencias as $a)

                                <tr class="border-b hover:bg-gray-50">

                                    <td class="p-3">
                                        {{ \Carbon\Carbon::parse($a->entrenamiento->fecha)->format('d/m/Y') }}
                                    </td>

                                    <td class="p-3">

                                        <div class="font-semibold">
                                            {{ $a->entrenamiento->tipo }}
                                        </div>

                                        <div class="text-xs text-gray-500">
                                            {{ $a->entrenamiento->lugar }}
                                        </div>

                                    </td>

                                    <td class="p-3">

                                        @if($a->estado == 'Presente')

                                            <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs">
                                                Presente
                                            </span>

                                        @elseif($a->estado == 'Ausente')

                                            <span class="bg-red-100 text-red-700 px-2 py-1 rounded-full text-xs">
                                                Ausente
                                            </span>

                                        @elseif($a->estado == 'Permiso')

                                            <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full text-xs">
                                                Permiso
                                            </span>

                                        @else

                                            <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded-full text-xs">
                                                {{ $a->estado }}
                                            </span>

                                        @endif

                                    </td>

                                    <td class="p-3">
                                        {{ $a->observacion ?: '-' }}
                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="4"
                                        class="text-center p-8 text-gray-500">

                                        No existen registros de asistencia.

                                    </td>

                                </tr>

                            @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 DOCUMENTOS
            ================================================== --}}

            <div id="panel-documentos" class="panel-jugador hidden">

                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">


                    <div class="bg-slate-700 text-white px-5 py-3 font-bold">
                        📄 Documentos del jugador
                    </div>


                    {{-- CARGAR DOCUMENTO --}}

                    <div class="p-5 border-b">

                        <form
                            action="{{ route('jugadores.documentos.store',$jugador) }}"
                            method="POST"
                            enctype="multipart/form-data">

                            @csrf

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                <div>

                                    <label class="block text-sm font-semibold mb-1">
                                        Tipo documento
                                    </label>

                                    <select
                                        name="tipo_documento_id"
                                        class="w-full border rounded-lg p-2"
                                        required>

                                        <option value="">
                                            Seleccione...
                                        </option>

                                        @foreach($tipos as $tipo)

                                            <option value="{{ $tipo->id }}">
                                                {{ $tipo->nombre }}
                                            </option>

                                        @endforeach

                                    </select>

                                </div>


                                <div>

                                    <label class="block text-sm font-semibold mb-1">
                                        Título
                                    </label>

                                    <input
                                        type="text"
                                        name="titulo"
                                        class="w-full border rounded-lg p-2"
                                        required>

                                </div>


                                <div class="md:col-span-2">

                                    <label class="block text-sm font-semibold mb-1">
                                        Descripción
                                    </label>

                                    <textarea
                                        name="descripcion"
                                        rows="2"
                                        class="w-full border rounded-lg p-2"></textarea>

                                </div>


                                <div class="md:col-span-2">

                                    <label class="block text-sm font-semibold mb-1">
                                        Archivo
                                    </label>

                                    <input
                                        type="file"
                                        name="archivo"
                                        class="w-full border rounded-lg p-2"
                                        required>

                                </div>

                            </div>


                            <button
                                type="submit"
                                class="mt-4 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg">

                                📎 Guardar documento

                            </button>

                        </form>

                    </div>


                    {{-- LISTADO --}}

                    <div class="overflow-x-auto">

                        <table class="w-full text-sm">

                            <thead class="bg-gray-100">

                                <tr>

                                    <th class="p-3 text-left">
                                        Tipo
                                    </th>

                                    <th class="p-3 text-left">
                                        Título
                                    </th>

                                    <th class="p-3 text-left">
                                        Fecha
                                    </th>

                                    <th class="p-3 text-center">
                                        Archivo
                                    </th>

                                    <th class="p-3 text-center">
                                        Acciones
                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                            @forelse($documentos as $doc)

                                <tr class="border-t hover:bg-gray-50">

                                    <td class="p-3">
                                        {{ optional($doc->tipoDocumento)->nombre ?? '-' }}
                                    </td>

                                    <td class="p-3 font-medium">
                                        {{ $doc->titulo }}
                                    </td>

                                    <td class="p-3">
                                        {{ $doc->fecha }}
                                    </td>

                                    <td class="p-3 text-center">

                                        <a
                                            href="{{ asset('storage/'.$doc->archivo) }}"
                                            target="_blank"
                                            class="inline-flex items-center bg-blue-50 text-blue-700 px-3 py-1 rounded-lg hover:bg-blue-100">

                                            📎 Ver

                                        </a>

                                    </td>

                                    <td class="p-3 text-center">

                                        <form
                                            action="{{ route('jugadores.documentos.destroy',$doc) }}"
                                            method="POST">

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                onclick="return confirm('¿Eliminar documento?')"
                                                class="text-red-600 hover:text-red-800">

                                                🗑️ Eliminar

                                            </button>

                                        </form>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="5"
                                        class="text-center p-8 text-gray-500">

                                        No hay documentos registrados.

                                    </td>

                                </tr>

                            @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 OBSERVACIONES
            ================================================== --}}

            <div id="panel-observaciones" class="panel-jugador hidden">

                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">

                    <div class="bg-orange-600 text-white px-5 py-3 font-bold">
                        📝 Observaciones
                    </div>

                    <div class="p-5">

                        @if(filled($jugador->observaciones ?? null))

                            <div class="bg-orange-50 border border-orange-100 rounded-xl p-4 text-gray-700 whitespace-pre-line">

                                {{ $jugador->observaciones }}

                            </div>

                        @else

                            <div class="text-center py-8 text-gray-500">

                                No hay observaciones registradas para este jugador.

                            </div>

                        @endif

                    </div>

                </div>

            </div>


        </main>

    </div>

</div>


{{-- =============================================================
     JAVASCRIPT
============================================================= --}}

<script>

function mostrarPanel(panel) {

    document.querySelectorAll('.panel-jugador').forEach(function(div) {
        div.classList.add('hidden');
    });

    const elemento = document.getElementById('panel-' + panel);

    if (elemento) {
        elemento.classList.remove('hidden');
    }

    document.querySelectorAll('.menu-btn').forEach(function(btn) {

        btn.classList.remove(
            'bg-blue-100',
            'text-blue-700',
            'font-semibold'
        );

    });

    const botones = document.querySelectorAll('.menu-btn');

    const nombres = [
        'info',
        'medico',
        'contabilidad',
        'estadisticas',
        'asistencia',
        'documentos',
        'observaciones'
    ];

    const posicion = nombres.indexOf(panel);

    if (posicion >= 0 && botones[posicion]) {

        botones[posicion].classList.add(
            'bg-blue-100',
            'text-blue-700',
            'font-semibold'
        );

    }

}

document.addEventListener('DOMContentLoaded', function() {

    mostrarPanel('info');

});

</script>

@endsection
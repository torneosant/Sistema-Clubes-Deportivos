@extends('layouts.app')

@section('titulo', 'Conceptos Contables')

@section('contenido')

<x-page-header
    title="📚 Conceptos contables"
    subtitle="Configura los conceptos utilizados en ingresos, gastos y cargos de jugadores."
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

<div class="flex justify-end mb-5">

    <a
        href="{{ route('conceptos-contables.create') }}"
        class="inline-flex items-center gap-2
               bg-blue-600 hover:bg-blue-700
               text-white px-4 py-2 rounded-lg
               text-sm font-semibold
               shadow-sm transition"
    >

        ➕ Nuevo concepto

    </a>

</div>


{{-- =========================================================
     TABLA
========================================================= --}}

<div class="bg-white rounded-xl shadow-lg overflow-hidden">

    <div class="bg-slate-800 text-white px-5 py-4">

        <div class="flex items-center justify-between">

            <div>

                <h3 class="font-bold text-base">
                    📚 Conceptos registrados
                </h3>

                <p class="text-xs text-slate-300 mt-1">
                    Catálogo de conceptos utilizados por el club.
                </p>

            </div>

            <span class="text-xs bg-slate-700
                         px-3 py-1 rounded-full">

                {{ $conceptos->count() }}

                {{ $conceptos->count() == 1
                    ? 'concepto'
                    : 'conceptos' }}

            </span>

        </div>

    </div>


    <div class="p-4">

        <div class="overflow-x-auto border rounded-xl">

            <table class="min-w-full text-sm">

                <thead class="bg-gray-100">

                    <tr>

                        <th class="px-4 py-3 text-left">
                            Concepto
                        </th>

                        <th class="px-4 py-3 text-center">
                            Tipo
                        </th>

                        <th class="px-4 py-3 text-right">
                            Valor predeterminado
                        </th>

                        <th class="px-4 py-3 text-left">
                            Descripción
                        </th>

                        <th class="px-4 py-3 text-center">
                            Estado
                        </th>

                        <th class="px-4 py-3 text-center">
                            Acciones
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($conceptos as $concepto)

                        <tr class="border-t hover:bg-gray-50">


                            {{-- CONCEPTO --}}

                            <td class="px-4 py-3">

                                <div class="font-semibold text-gray-800">

                                    {{ $concepto->nombre }}

                                </div>

                            </td>


                            {{-- TIPO --}}

                            <td class="px-4 py-3 text-center">

                                @if($concepto->tipo === 'Ingreso')

                                    <span
                                        class="inline-flex px-2 py-1
                                               rounded-full
                                               bg-green-100
                                               text-green-700
                                               text-xs font-semibold"
                                    >

                                        💰 Ingreso

                                    </span>

                                @else

                                    <span
                                        class="inline-flex px-2 py-1
                                               rounded-full
                                               bg-red-100
                                               text-red-700
                                               text-xs font-semibold"
                                    >

                                        💸 Egreso

                                    </span>

                                @endif

                            </td>


                            {{-- VALOR --}}

                            <td class="px-4 py-3 text-right">

                                @if(
                                    $concepto->valor_predeterminado !== null
                                    && $concepto->valor_predeterminado > 0
                                )

                                    <span class="font-semibold text-slate-700">

                                        ${{ number_format(
                                            $concepto->valor_predeterminado,
                                            0,
                                            ',',
                                            '.'
                                        ) }}

                                    </span>

                                @else

                                    <span class="text-gray-400">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- DESCRIPCIÓN --}}

                            <td class="px-4 py-3">

                                <span class="text-gray-600">

                                    {{ $concepto->descripcion ?: '—' }}

                                </span>

                            </td>


                            {{-- ESTADO --}}

                            <td class="px-4 py-3 text-center">

                                @if($concepto->activo)

                                    <span
                                        class="inline-flex px-2 py-1
                                               rounded-full
                                               bg-green-100
                                               text-green-700
                                               text-xs font-semibold"
                                    >

                                        ✓ Activo

                                    </span>

                                @else

                                    <span
                                        class="inline-flex px-2 py-1
                                               rounded-full
                                               bg-gray-100
                                               text-gray-600
                                               text-xs font-semibold"
                                    >

                                        Inactivo

                                    </span>

                                @endif

                            </td>


                            {{-- ACCIONES --}}

                            <td class="px-4 py-3">

                                <div class="flex justify-center
                                            items-center gap-2">

                                    <a
                                        href="{{ route(
                                            'conceptos-contables.edit',
                                            $concepto
                                        ) }}"
                                        class="inline-flex
                                               items-center
                                               justify-center
                                               w-8 h-8
                                               rounded-lg
                                               bg-blue-50
                                               text-blue-600
                                               hover:bg-blue-100
                                               transition"
                                        title="Editar concepto"
                                    >

                                        ✏️

                                    </a>


                                    <form
                                        action="{{ route(
                                            'conceptos-contables.destroy',
                                            $concepto
                                        ) }}"
                                        method="POST"
                                        class="inline"
                                        onsubmit="return confirm(
                                            '¿Deseas eliminar este concepto?'
                                        );"
                                    >

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="inline-flex
                                                   items-center
                                                   justify-center
                                                   w-8 h-8
                                                   rounded-lg
                                                   bg-red-50
                                                   text-red-600
                                                   hover:bg-red-100
                                                   transition"
                                            title="Eliminar concepto"
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
                                colspan="6"
                                class="px-4 py-10 text-center
                                       text-gray-500"
                            >

                                <div class="text-3xl mb-2">
                                    📚
                                </div>

                                <div class="font-semibold">
                                    No hay conceptos registrados.
                                </div>

                                <div class="text-xs mt-1">
                                    Crea el primer concepto contable para comenzar.
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
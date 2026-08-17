@extends('layouts.app')

@section('titulo', 'Contabilidad')

@section('contenido')


{{-- ENCABEZADO + CONTADORES --}}

<x-page-header
    title="💰 Contabilidad"
    subtitle="Administra los ingresos, gastos y movimientos financieros del club."
>

    <div class="flex items-center gap-2">

        <x-stat
            label="Ingresos"
            :value="'$'.number_format($ingresos, 0, ',', '.')"
            icon="💰"
            color="green"
        />

        <x-stat
            label="Gastos"
            :value="'$'.number_format($gastos, 0, ',', '.')"
            icon="🔴"
            color="red"
        />

        <x-stat
            label="Saldo"
            :value="'$'.number_format($saldo, 0, ',', '.')"
            icon="💵"
            color="blue"
        />

        <x-stat
            label="Movimientos"
            :value="$movimientos->count()"
            icon="📊"
            color="purple"
        />

    </div>

</x-page-header>


@if(session('success'))

    <div class="mb-6 rounded-lg bg-green-100 border border-green-300 text-green-700 px-4 py-3">

        {{ session('success') }}

    </div>

@endif


{{-- ACCIONES --}}

<x-actions>

    <a href="{{ route('contabilidad.create') }}">

        <x-button color="green">

            ➕ Nuevo Movimiento

        </x-button>

    </a>

</x-actions>


{{-- FILTROS --}}

{{-- FILTROS --}}

<x-filter
    :action="route('contabilidad.index')"
>

    <x-input
        type="date"
        name="desde"
        value="{{ request('desde') }}"
        label="Desde"
    />

    <x-input
        type="date"
        name="hasta"
        value="{{ request('hasta') }}"
        label="Hasta"
    />

    <select
        name="tipo"
        class="w-full h-10 rounded-lg border border-gray-300 bg-white px-3 text-sm text-slate-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
    >

        <option value="">Todos</option>

        <option
            value="Ingreso"
            @selected(request('tipo') === 'Ingreso')
        >
            Ingreso
        </option>

        <option
            value="Egreso"
            @selected(request('tipo') === 'Egreso')
        >
            Egreso
        </option>

    </select>


    <select
        name="concepto"
        class="w-full h-10 rounded-lg border border-gray-300 bg-white px-3 text-sm text-slate-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
    >

        <option value="">Todos</option>

        @foreach($conceptos as $concepto)

            <option
                value="{{ $concepto->id }}"
                @selected((string)request('concepto') === (string)$concepto->id)
            >
                {{ $concepto->nombre }}
            </option>

        @endforeach

    </select>


    <x-button
        type="submit"
        color="blue"
    >
        🔍 Filtrar
    </x-button>


    <a
        href="{{ route('contabilidad.index') }}"
        class="inline-flex items-center justify-center bg-gray-600 hover:bg-gray-700 text-white px-5 py-2 rounded-xl font-semibold transition-all duration-300 shadow-sm hover:shadow-md"
    >
        Limpiar
    </a>

</x-filter>


{{-- TABLA --}}

<x-table>

    <x-table-header>

        <x-table-header-cell align="center">
            Fecha
        </x-table-header-cell>

        <x-table-header-cell align="center">
            Tipo
        </x-table-header-cell>

        <x-table-header-cell>
            Concepto
        </x-table-header-cell>

        <x-table-header-cell>
            Jugador
        </x-table-header-cell>

        <x-table-header-cell>
            Pagador
        </x-table-header-cell>

        <x-table-header-cell align="center">
            Valor
        </x-table-header-cell>

        <x-table-header-cell>
            Método
        </x-table-header-cell>

        <x-table-header-cell>
            Observaciones
        </x-table-header-cell>

        <x-table-header-cell align="center">
            Acciones
        </x-table-header-cell>

    </x-table-header>


    <tbody>

        @forelse($movimientos as $movimiento)

            <x-table-row>


                {{-- FECHA --}}

                <x-table-cell align="center">

                    {{ \Carbon\Carbon::parse($movimiento->fecha)->format('d/m/Y') }}

                </x-table-cell>


                {{-- TIPO --}}

                <x-table-cell align="center">

                    @if($movimiento->tipo == 'Ingreso')

                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-sm">

                            Ingreso

                        </span>

                    @else

                        <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-sm">

                            Egreso

                        </span>

                    @endif

                </x-table-cell>


                {{-- CONCEPTO --}}

                <x-table-cell>

                    {{ $movimiento->concepto?->nombre ?? '-' }}

                </x-table-cell>


                {{-- JUGADOR --}}

                <x-table-cell>

                    @if($movimiento->jugador)

                        {{ $movimiento->jugador->apellidos }}
                        {{ $movimiento->jugador->nombres }}

                    @else

                        -

                    @endif

                </x-table-cell>


                {{-- PAGADOR --}}

                <x-table-cell>

                    {{ $movimiento->tercero ?? '-' }}

                </x-table-cell>


                {{-- VALOR --}}

                <x-table-cell align="center">

                    <span class="font-semibold">

                        ${{ number_format($movimiento->valor, 0, ',', '.') }}

                    </span>

                </x-table-cell>


                {{-- MÉTODO --}}

                <x-table-cell>

                    {{ $movimiento->metodo_pago ?? '-' }}

                </x-table-cell>


                {{-- OBSERVACIONES --}}

                <x-table-cell>

                    {{ $movimiento->observaciones ?? '-' }}

                </x-table-cell>


                {{-- ACCIONES --}}

                <x-table-cell align="center">

                    <div class="flex justify-center items-center gap-2">


                        {{-- EDITAR --}}

                        <a href="{{ route('contabilidad.edit', $movimiento) }}">

                            <x-button
                                color="yellow"
                                icon
                                title="Editar movimiento"
                            >

                                ✏️

                            </x-button>

                        </a>


                        {{-- ELIMINAR --}}

                        <form
                            action="{{ route('contabilidad.destroy', $movimiento) }}"
                            method="POST"
                            class="inline formulario-eliminar"
                        >

                            @csrf
                            @method('DELETE')

                            <x-button
                                color="red"
                                icon
                                type="submit"
                                title="Eliminar movimiento"
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
                    colspan="9"
                    class="px-4 py-10 text-center text-gray-500"
                >

                    No existen movimientos registrados.

                </td>

            </tr>

        @endforelse

    </tbody>

</x-table>


@endsection
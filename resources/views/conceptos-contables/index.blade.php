@extends('layouts.app')

@section('titulo', 'Conceptos Contables')

@section('contenido')


{{-- ENCABEZADO --}}

<x-page-header
    title="📂 Conceptos Contables"
    subtitle="Administra los conceptos de ingresos y gastos del club."
>


    <div class="flex items-center gap-2">

        <x-stat
            label="Total"
            :value="$conceptos->count()"
            icon="📂"
            color="blue"
        />

        <x-stat
            label="Ingresos"
            :value="$conceptos->where('tipo', 'Ingreso')->count()"
            icon="💰"
            color="green"
        />

        <x-stat
            label="Gastos"
            :value="$conceptos->where('tipo', 'Egreso')->count()"
            icon="💸"
            color="red"
        />

        <x-stat
            label="Activos"
            :value="$conceptos->where('activo', true)->count()"
            icon="🟢"
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

    <a href="{{ route('conceptos-contables.create') }}">

        <x-button color="green">

            ➕ Nuevo Concepto

        </x-button>

    </a>

</x-actions>


{{-- TABLA --}}

<x-table>

    <x-table-header>

        <x-table-header-cell>
            Nombre
        </x-table-header-cell>

        <x-table-header-cell align="center">
            Tipo
        </x-table-header-cell>

        <x-table-header-cell>
            Descripción
        </x-table-header-cell>

        <x-table-header-cell align="center">
            Estado
        </x-table-header-cell>

        <x-table-header-cell align="center">
            Acciones
        </x-table-header-cell>

    </x-table-header>


    <tbody>

        @forelse($conceptos as $concepto)

            <x-table-row>


                {{-- NOMBRE --}}

                <x-table-cell>

                    <span class="font-semibold">

                        {{ $concepto->nombre }}

                    </span>

                </x-table-cell>


                {{-- TIPO --}}

                <x-table-cell align="center">

                    @if($concepto->tipo == 'Ingreso')

                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">

                            💰 Ingreso

                        </span>

                    @else

                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm">

                            💸 Gasto

                        </span>

                    @endif

                </x-table-cell>


                {{-- DESCRIPCIÓN --}}

                <x-table-cell>

                    {{ $concepto->descripcion ?? '-' }}

                </x-table-cell>


                {{-- ESTADO --}}

                <x-table-cell align="center">

                    @if($concepto->activo)

                        <span class="bg-green-500 text-white px-3 py-1 rounded-full text-sm">

                            Activo

                        </span>

                    @else

                        <span class="bg-gray-500 text-white px-3 py-1 rounded-full text-sm">

                            Inactivo

                        </span>

                    @endif

                </x-table-cell>


                {{-- ACCIONES --}}

                <x-table-cell align="center">

                    <div class="flex justify-center items-center gap-2">

                        <a href="{{ route('conceptos-contables.edit', $concepto) }}">

                            <x-button
                                color="yellow"
                                icon
                                title="Editar concepto"
                            >

                                ✏️

                            </x-button>

                        </a>

                    </div>

                </x-table-cell>


            </x-table-row>

        @empty

            <tr>

                <td
                    colspan="5"
                    class="px-4 py-10 text-center text-gray-500"
                >

                    No existen conceptos registrados.

                </td>

            </tr>

        @endforelse

    </tbody>

</x-table>


@endsection
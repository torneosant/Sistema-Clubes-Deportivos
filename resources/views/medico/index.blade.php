@extends('layouts.app')

@section('titulo', 'Historial Médico')

@section('contenido')


{{-- ENCABEZADO + CONTADORES --}}

<x-page-header
    title="❤️ Historial Médico"
    subtitle="Administra los registros médicos, lesiones y estados de recuperación de los jugadores."
>

    <div class="flex items-center gap-2">

        <x-stat
            label="Registros"
            :value="$historial->count()"
            icon="📋"
            color="red"
        />

        <x-stat
            label="Lesiones activas"
            :value="$historial->where('estado', 'Activo')->count()"
            icon="🔴"
            color="red"
        />

        <x-stat
            label="En recuperación"
            :value="$historial->where('estado', 'En recuperación')->count()"
            icon="🟡"
            color="yellow"
        />

        <x-stat
            label="Altas médicas"
            :value="$historial->where('estado', 'Alta médica')->count()"
            icon="🟢"
            color="green"
        />

    </div>

</x-page-header>


@if(session('success'))

    <div class="mb-6 rounded-lg bg-green-100 border border-green-300 text-green-700 px-4 py-3">

        {{ session('success') }}

    </div>

@endif


{{-- INFORMACIÓN DEL JUGADOR --}}

@if($jugador)

    <div class="mb-6">

        <p class="text-slate-500">

            {{ $jugador->nombres }}
            {{ $jugador->apellidos }}

        </p>

    </div>

@endif


{{-- ACCIONES --}}

<x-actions>

    @if($jugador)

        <a href="{{ route('historial-medico.create') }}">

            <x-button color="red">

                ➕ Nuevo Registro

            </x-button>

        </a>

        <a href="{{ route('historial-medico.index') }}">

            <x-button color="slate">

                📋 Todos los registros

            </x-button>

        </a>

    @else

        <a href="{{ route('historial-medico.create') }}">

            <x-button color="red">

                ➕ Nuevo Registro

            </x-button>

        </a>

    @endif

</x-actions>


{{-- FILTROS --}}

<x-filter
    :action="route('historial-medico.index')"
>

    <x-input
        name="buscar"
        value="{{ request('buscar') }}"
        placeholder="🔍 Buscar jugador..."
    />


    <select
        name="estado"
        class="w-full h-10 rounded-lg border border-gray-300 bg-white px-3 text-sm text-slate-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
    >

        <option value="">
            Todos los estados
        </option>

        <option
            value="Activo"
            @selected(request('estado') === 'Activo')
        >
            Activo
        </option>

        <option
            value="En recuperación"
            @selected(request('estado') === 'En recuperación')
        >
            En recuperación
        </option>

        <option
            value="Alta médica"
            @selected(request('estado') === 'Alta médica')
        >
            Alta médica
        </option>

    </select>


    <x-button
        type="submit"
        color="blue"
    >

        🔍 Buscar

    </x-button>


    <a
        href="{{ route('historial-medico.index') }}"
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

        <x-table-header-cell>
            Jugador
        </x-table-header-cell>

        <x-table-header-cell>
            Tipo
        </x-table-header-cell>

        <x-table-header-cell>
            Zona
        </x-table-header-cell>

        <x-table-header-cell align="center">
            Días
        </x-table-header-cell>

        <x-table-header-cell align="center">
            Estado
        </x-table-header-cell>

        <x-table-header-cell align="center">
            Acciones
        </x-table-header-cell>

    </x-table-header>


    <tbody>

        @forelse($historial as $item)

            <x-table-row>


                {{-- FECHA --}}

                <x-table-cell align="center">

                    {{ \Carbon\Carbon::parse($item->fecha)->format('d/m/Y') }}

                </x-table-cell>


                {{-- JUGADOR --}}

                <x-table-cell>

                    {{ $item->jugador->nombres }}
                    {{ $item->jugador->apellidos }}

                </x-table-cell>


                {{-- TIPO --}}

                <x-table-cell>

                    {{ $item->tipo ?? '-' }}

                </x-table-cell>


                {{-- ZONA --}}

                <x-table-cell>

                    {{ $item->zona ?? '-' }}

                </x-table-cell>


                {{-- DÍAS --}}

                <x-table-cell align="center">

                    {{ $item->dias_incapacidad ?? 0 }}

                </x-table-cell>


                {{-- ESTADO --}}

                <x-table-cell align="center">

                    @if($item->estado === 'Activo')

                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm">

                            🔴 Activo

                        </span>

                    @elseif($item->estado === 'En recuperación')

                        <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm">

                            🟡 En recuperación

                        </span>

                    @else

                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">

                            🟢 Alta médica

                        </span>

                    @endif

                </x-table-cell>


                {{-- ACCIONES --}}

                <x-table-cell align="center">

                    <div class="flex justify-center items-center gap-2">


                        {{-- EDITAR --}}

                        <a href="{{ route('historial-medico.edit', $item) }}">

                            <x-button
                                color="yellow"
                                icon
                                title="Editar registro médico"
                            >

                                ✏️

                            </x-button>

                        </a>


                        {{-- VER JUGADOR --}}

                        <a href="{{ route('jugadores.show', $item->jugador_id) }}">

                            <x-button
                                color="blue"
                                icon
                                title="Ver jugador"
                            >

                                👤

                            </x-button>

                        </a>


                        {{-- ELIMINAR --}}

                        <form
                            action="{{ route('historial-medico.destroy', $item) }}"
                            method="POST"
                            class="inline formulario-eliminar"
                        >

                            @csrf
                            @method('DELETE')

                            <x-button
                                color="red"
                                icon
                                type="submit"
                                title="Eliminar registro médico"
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
                    class="px-4 py-10 text-center text-gray-500"
                >

                    No existen registros médicos.

                </td>

            </tr>

        @endforelse

    </tbody>

</x-table>


@endsection
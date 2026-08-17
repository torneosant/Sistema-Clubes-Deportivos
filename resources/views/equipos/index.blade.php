@extends('layouts.app')

@section('titulo', 'Equipos')

@section('contenido')

<x-page-header
    title="⚽ Listado de Equipos"
    subtitle="Administra los equipos de tu club."
>
    <div class="flex items-center gap-2">

        <x-stat
            label="Total"
            :value="$totalEquipos"
            icon="⚽"
            color="blue"
        />

        <x-stat
            label="Activos"
            :value="$totalActivos"
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


{{-- BOTONES --}}

<x-actions>

    <a
        href="{{ route('equipos.create') }}"
         <x-button color="blue">
            ➕ Nuevo Equipo
        </x-button>
    </a>

</x-actions>


{{-- FILTROS --}}

<x-filter
    :action="route('equipos.index')"
>

    <x-input
        name="buscar"
        value="{{ $buscar }}"
        placeholder="🔍 Buscar equipo..."
    />

    <x-button type="submit" color="blue">
        🔍 Buscar
    </x-button>

    <a
        href="{{ route('equipos.index') }}"
        class="inline-flex items-center justify-center bg-gray-600 hover:bg-gray-700 text-white px-5 py-2 rounded-xl font-semibold transition-all duration-300 shadow-sm hover:shadow-md"
    >
        Limpiar
    </a>

</x-filter>


{{-- TABLA --}}

<x-table>

    <x-table-header>

        <x-table-header-cell align="center">
            Escudo
        </x-table-header-cell>

        <x-table-header-cell>
            Equipo
        </x-table-header-cell>

        <x-table-header-cell>
            Categoría
        </x-table-header-cell>

        <x-table-header-cell>
            Colores
        </x-table-header-cell>

        <x-table-header-cell align="center">
            Acciones
        </x-table-header-cell>

    </x-table-header>


    <tbody>

        @forelse($equipos as $equipo)

            <x-table-row>

                {{-- ESCUDO --}}

                <x-table-cell align="center">

                    @if($equipo->escudo)

                        <img
                            src="{{ asset('storage/'.$equipo->escudo) }}"
                            class="w-10 h-10 rounded-full object-cover border border-gray-200 shadow-sm"
                        >

                    @else

                        <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center">
                            ⚽
                        </div>

                    @endif

                </x-table-cell>


                {{-- EQUIPO --}}

                <x-table-cell>

                    <div class="font-semibold text-slate-800">
                        {{ $equipo->nombre }}
                    </div>

                </x-table-cell>


                {{-- CATEGORÍAS --}}

                <x-table-cell>

                    @forelse($equipo->categorias as $categoria)

                        <span class="inline-flex items-center bg-blue-50 text-blue-700 px-2 py-1 rounded-lg text-xs font-medium mr-1 mb-1">

                            {{ $categoria->nombre }}

                        </span>

                    @empty

                        <span class="text-gray-400">
                            -
                        </span>

                    @endforelse

                </x-table-cell>


                {{-- COLORES --}}

                <x-table-cell>

                    <span class="text-slate-700">

                        {{ $equipo->color_principal }}

                        @if($equipo->color_secundario)
                            / {{ $equipo->color_secundario }}
                        @endif

                    </span>

                </x-table-cell>


                {{-- ACCIONES --}}

                <x-table-cell align="center">

                    <div class="flex justify-center items-center gap-2">

                        {{-- VER / EDITAR --}}

                        <a
                            href="{{ route('equipos.edit', $equipo) }}"
                            class="w-9 h-9 flex items-center justify-center rounded-lg transition-all duration-300 shadow-sm hover:shadow-md bg-yellow-500 hover:bg-yellow-600 text-white"
                            title="Editar"
                        >
                            ✏️
                        </a>


                        {{-- ESTADO --}}

                        <form
                            action="{{ route('equipos.estado', $equipo) }}"
                            method="POST"
                            class="inline"
                        >

                            @csrf
                            @method('PATCH')

                            @if($equipo->activo)

                                <x-button
                                    type="button"
                                    color="green"
                                    icon
                                    onclick="return confirmarEstado(this, true)"
                                    title="Desactivar"
                                >
                                    🟢
                                </x-button>

                            @else

                                <x-button
                                    type="button"
                                    color="gray"
                                    icon
                                    onclick="return confirmarEstado(this, false)"
                                    title="Activar"
                                >
                                    ⚪
                                </x-button>

                            @endif

                        </form>


                        {{-- ELIMINAR --}}

                        <form
                            action="{{ route('equipos.destroy', $equipo) }}"
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
                    colspan="5"
                    class="text-center py-10 text-gray-500"
                >
                    No hay equipos registrados.
                </td>

            </tr>

        @endforelse

    </tbody>

</x-table>


{{-- PAGINACIÓN --}}

<div class="mt-6">

    {{ $equipos->links() }}

</div>

@endsection
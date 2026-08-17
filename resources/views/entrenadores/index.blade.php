@extends('layouts.app')

@section('titulo', 'Entrenadores')

@section('contenido')

<x-page-header
    title="🧑‍🏫 Gestión de Entrenadores"
    subtitle="Administra todos los entrenadores registrados en tu club."
>
    <div class="flex items-center gap-2">

        <x-stat
            label="Total"
            :value="$totalEntrenadores"
            icon="🧑‍🏫"
            color="blue"
        />

        <x-stat
            label="Activos"
            :value="$totalActivos"
            icon="🟢"
            color="green"
        />

        <x-stat
            label="Inactivos"
            :value="$totalEntrenadores - $totalActivos"
            icon="🔴"
            color="red"
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
        href="{{ route('entrenadores.create') }}"
        <x-button color="blue">
            ➕ Nuevo Entrenador
        </x-button>
    </a>


    <a
        href="{{ route('entrenadores.exportExcel') }}"
      <x-button color="green">
            📊 Excel
        </x-button>
    </a>


    <a
        href="{{ route('entrenadores.pdf') }}"
       <x-button color="red">
            📄 PDF
        </x-button>
    </a>

</x-actions>


{{-- FILTROS --}}

<x-filter
    :action="route('entrenadores.index')"
>

    <div class="w-full md:w-[320px]">
        <x-input
            name="buscar"
            value="{{ $buscar }}"
            placeholder="🔍 Buscar por nombre, documento o teléfono..."
        />
    </div>

    <div class="w-full md:w-[220px]">
        <select
            name="estado"
            class="w-full h-10 rounded-lg border border-gray-300 bg-white px-3 text-sm text-slate-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
        >

            <option value="">
                Todos
            </option>

            <option
                value="1"
                @selected((string)$estado === '1')
            >
                Activos
            </option>

            <option
                value="0"
                @selected((string)$estado === '0')
            >
                Inactivos
            </option>

        </select>
    </div>

    <x-button
        type="submit"
        color="blue"
    >
        🔍 Buscar
    </x-button>

    <a
        href="{{ route('entrenadores.index') }}"
        class="inline-flex items-center justify-center bg-gray-600 hover:bg-gray-700 text-white px-5 py-2 rounded-xl font-semibold transition-all duration-300 shadow-sm hover:shadow-md"
    >
        Limpiar
    </a>

</x-filter>


{{-- TABLA --}}

<x-table>

    <x-table-header>

        <x-table-header-cell>
            Foto
        </x-table-header-cell>

        <x-table-header-cell>
            Entrenador
        </x-table-header-cell>

        <x-table-header-cell>
            Cargo
        </x-table-header-cell>

        <x-table-header-cell align="center">
            Documento
        </x-table-header-cell>

        <x-table-header-cell align="center">
            Edad
        </x-table-header-cell>

        <x-table-header-cell align="center">
            Categoría
        </x-table-header-cell>

        <x-table-header-cell align="center">
            Equipo
        </x-table-header-cell>

        <x-table-header-cell align="center">
            Estado
        </x-table-header-cell>

        <x-table-header-cell align="center">
            Acciones
        </x-table-header-cell>

    </x-table-header>


    <tbody>

        @forelse($entrenadores as $entrenador)

            <x-table-row>

                {{-- FOTO --}}

                <x-table-cell>

                    @if($entrenador->foto)

                        <img
                            src="{{ asset('storage/'.$entrenador->foto) }}"
                            class="w-12 h-12 rounded-full object-cover"
                            alt="{{ $entrenador->nombres }}"
                        >

                    @else

                        <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center text-xl">
                            👤
                        </div>

                    @endif

                </x-table-cell>


                {{-- ENTRENADOR --}}

                <x-table-cell>

                    <div class="font-semibold text-slate-800">

                        {{ $entrenador->nombres }}
                        {{ $entrenador->apellidos }}

                    </div>

                </x-table-cell>


                {{-- CARGO --}}

                <x-table-cell>

                    {{ $entrenador->cargo ?? '-' }}

                </x-table-cell>


                {{-- DOCUMENTO --}}

                <x-table-cell align="center">

                    {{ $entrenador->numero_documento }}

                </x-table-cell>


                {{-- EDAD --}}

                <x-table-cell align="center">

                    {{
                        $entrenador->fecha_nacimiento
                            ? \Carbon\Carbon::parse($entrenador->fecha_nacimiento)->age . ' años'
                            : '-'
                    }}

                </x-table-cell>


                {{-- CATEGORÍA --}}

                <x-table-cell align="center">

                    @forelse($entrenador->equipos as $equipo)

                        <div class="mb-1">

                            {{ $equipo->categoria->nombre ?? '-' }}

                        </div>

                    @empty

                        -

                    @endforelse

                </x-table-cell>


                {{-- EQUIPO --}}

                <x-table-cell align="center">

                    @forelse($entrenador->equipos as $equipo)

                        <div class="mb-1 font-medium">

                            {{ $equipo->nombre }}

                        </div>

                    @empty

                        -

                    @endforelse

                </x-table-cell>


                {{-- ESTADO --}}

                <x-table-cell align="center">

                    <form
                        action="{{ route('entrenadores.estado', $entrenador) }}"
                        method="POST"
                        class="inline"
                    >

                        @csrf
                        @method('PATCH')

                        @if($entrenador->activo)

                            <x-button
                                type="button"
                                color="green"
                                icon
                                onclick="confirmarEstado(this)"
                                title="Desactivar"
                            >
                                🟢
                            </x-button>

                        @else

                            <x-button
                                type="button"
                                color="gray"
                                icon
                                onclick="confirmarEstado(this)"
                                title="Activar"
                            >
                                ⚪
                            </x-button>

                        @endif

                    </form>

                </x-table-cell>


                {{-- ACCIONES --}}

                <x-table-cell align="center">

                    <div class="flex justify-center items-center gap-2">


                        {{-- VER --}}

                        <a
                            href="{{ route('entrenadores.show', $entrenador) }}"
                            class="w-9 h-9 flex items-center justify-center rounded-lg transition-all duration-300 shadow-sm hover:shadow-md bg-blue-600 hover:bg-blue-700 text-white"
                            title="Ver"
                        >
                            👁️
                        </a>


                        {{-- EDITAR --}}

                        <a
                            href="{{ route('entrenadores.edit', $entrenador) }}"
                            class="w-9 h-9 flex items-center justify-center rounded-lg transition-all duration-300 shadow-sm hover:shadow-md bg-yellow-500 hover:bg-yellow-600 text-white"
                            title="Editar"
                        >
                            ✏️
                        </a>


                        {{-- ELIMINAR --}}

                        <form
                            action="{{ route('entrenadores.destroy', $entrenador) }}"
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
                    colspan="9"
                    class="text-center py-10 text-gray-500"
                >
                    No hay entrenadores registrados.
                </td>

            </tr>

        @endforelse

    </tbody>

</x-table>


{{-- PAGINACIÓN --}}

<div class="mt-6">

    {{ $entrenadores->links() }}

</div>

@endsection
@extends('layouts.app')

@section('titulo', 'Gestión de Jugadores')

@section('contenido')

<x-page-header
    title="👥 Gestión de Jugadores"
    subtitle="Administra todos los jugadores registrados en tu club."
>
    <div class="flex items-center gap-2">

        <x-stat
            label="Total"
            :value="$totalJugadores"
            icon="👥"
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
            :value="$totalJugadores - $totalActivos"
            icon="🔴"
            color="red"
        />

        <x-stat
            label="Categorías"
            :value="$totalCategorias"
            icon="📂"
            color="purple"
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

    <a href="{{ route('jugadores.create') }}">

        <x-button color="blue">
            ➕ Nuevo Jugador
        </x-button>

    </a>

    <a href="{{ route('jugadores.exportExcel') }}">

        <x-button color="green">
            📊 Excel
        </x-button>

    </a>

    <a href="{{ route('jugadores.pdf') }}">

        <x-button color="red">
            📄 PDF
        </x-button>

    </a>

    <a
        href="{{ route('jugadores.print') }}"
        target="_blank"
    >

        <x-button color="gray">
            🖨️ Imprimir
        </x-button>

    </a>

</x-actions>


{{-- FILTROS --}}

<div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 mb-6">

    <form
        method="GET"
        action="{{ route('jugadores.index') }}"
    >

        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">

            <div class="md:col-span-2">

                <x-input
                    name="buscar"
                    :value="$buscar"
                    placeholder="🔍 Buscar por nombre, documento o teléfono..."
                />

            </div>


            <div>

                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Categoría
                </label>

                <select
                    name="categoria"
                    class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                >

                    <option value="">
                        Todas las categorías
                    </option>

                    @foreach($categorias as $cat)

                        <option
                            value="{{ $cat->id }}"
                            @selected($categoria == $cat->id)
                        >
                            {{ $cat->nombre }}
                        </option>

                    @endforeach

                </select>

            </div>


            <div>

                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Equipo
                </label>

                <select
                    name="equipo"
                    class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                >

                    <option value="">
                        Todos los equipos
                    </option>

                    @foreach($equipos as $eq)

                        <option
                            value="{{ $eq->id }}"
                            @selected($equipo == $eq->id)
                        >
                            {{ $eq->nombre }}
                        </option>

                    @endforeach

                </select>

            </div>


            <div>

                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Estado
                </label>

                <select
                    name="estado"
                    class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                >

                    <option value="">
                        Todos
                    </option>

                    <option
                        value="1"
                        @selected($estado == '1')
                    >
                        Activos
                    </option>

                    <option
                        value="0"
                        @selected($estado == '0')
                    >
                        Inactivos
                    </option>

                </select>

            </div>

        </div>


        <div class="flex gap-2 mt-4">

            <button type="submit">

                <x-button color="blue">
                    🔍 Buscar
                </x-button>

            </button>

            <a href="{{ route('jugadores.index') }}">

                <x-button color="gray">
                    Limpiar
                </x-button>

            </a>

        </div>

    </form>

</div>


{{-- TABLA --}}

<x-table>

 <x-table-header>

        <x-table-header-cell>
            Foto
        </x-table-header-cell>

        <x-table-header-cell>
            Jugador
        </x-table-header-cell>

        <x-table-header-cell align="center">
            Documento
        </x-table-header-cell>

        <x-table-header-cell>
            Edad
        </x-table-header-cell>

        <x-table-header-cell>
            Categoría
        </x-table-header-cell>

        <x-table-header-cell>
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

        @forelse($jugadores as $jugador)

            <x-table-row>

                {{-- FOTO --}}

                <x-table-cell>

                    @if($jugador->foto)

                        <img
                            src="{{ asset('storage/'.$jugador->foto) }}"
                            class="w-10 h-10 rounded-full object-cover"
                            alt="Foto"
                        >

                    @else

                        <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
                            👤
                        </div>

                    @endif

                </x-table-cell>


                {{-- JUGADOR --}}

                <x-table-cell>

                    <div class="font-semibold text-slate-800">
                        {{ $jugador->nombres }} {{ $jugador->apellidos }}
                    </div>

                    <div class="text-xs text-gray-500">
                        📞 {{ $jugador->telefono ?? 'Sin teléfono' }}
                    </div>

                </x-table-cell>


                {{-- DOCUMENTO --}}

                <x-table-cell align="center">
                    {{ $jugador->numero_documento }}
                </x-table-cell>


                {{-- EDAD --}}

                <x-table-cell>

                    {{ $jugador->fecha_nacimiento
                        ? \Carbon\Carbon::parse($jugador->fecha_nacimiento)->age . ' años'
                        : '-'
                    }}

                </x-table-cell>


                {{-- CATEGORÍA --}}

                <x-table-cell>
                    {{ $jugador->categoria->nombre ?? '-' }}
                </x-table-cell>


                {{-- EQUIPO --}}

                <x-table-cell>
                    {{ $jugador->equipo->nombre ?? '-' }}
                </x-table-cell>


                {{-- ESTADO --}}

                <x-table-cell align="center">

                    <form
                        action="{{ route('jugadores.estado', $jugador) }}"
                        method="POST"
                        class="inline"
                    >

                        @csrf
                        @method('PATCH')

                        @if($jugador->activo)

                            <x-button
                                color="green"
                                type="submit"
                            >
                                🟢 Activo
                            </x-button>

                        @else

                            <x-button
                                color="red"
                                type="submit"
                            >
                                🔴 Inactivo
                            </x-button>

                        @endif

                    </form>

                </x-table-cell>


                {{-- ACCIONES --}}

                <x-table-cell align="center">

                    <div class="flex justify-center items-center gap-2">

                        <a href="{{ route('jugadores.show', $jugador) }}">

                            <x-button
                                color="blue"
                                icon
                                title="Ver jugador"
                            >
                                👁️
                            </x-button>

                        </a>


                        <a href="{{ route('jugadores.edit', $jugador) }}">

                            <x-button
                                color="yellow"
                                icon
                                title="Editar jugador"
                            >
                                ✏️
                            </x-button>

                        </a>


                        <form
                            action="{{ route('jugadores.destroy', $jugador) }}"
                            method="POST"
                            class="inline formulario-eliminar"
                        >

                            @csrf
                            @method('DELETE')

                            <x-button
                                color="red"
                                icon
                                type="submit"
                                title="Eliminar jugador"
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
                    colspan="8"
                    class="px-4 py-10 text-center text-gray-500"
                >
                    No hay jugadores registrados.
                </td>

            </tr>

        @endforelse

    </tbody>

</x-table>


{{-- PAGINACIÓN --}}

<div class="mt-6">

    {{ $jugadores->links() }}

</div>

@endsection
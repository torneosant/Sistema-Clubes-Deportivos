@extends('layouts.app')

@section('titulo', 'Entrenamientos')

@section('contenido')

<x-page-header
    title="🏃 Listado de Entrenamientos"
    subtitle="Programa y administra los entrenamientos del club."
/>


{{-- ACCIONES --}}

<x-actions>

    <div>
        <x-button
            type="button"
            color="blue"
            onclick="window.location='{{ route('entrenamientos.create') }}'"
        >
            ➕ Nuevo Entrenamiento
        </x-button>
    </div>

</x-actions>


{{-- TABLA --}}

<x-table>

    <x-table-header>

        <x-table-header-cell align="center">
            Fecha
        </x-table-header-cell>

        <x-table-header-cell align="center">
            Equipo
        </x-table-header-cell>

        <x-table-header-cell align="center">
            Categorías
        </x-table-header-cell>

        <x-table-header-cell align="center">
            Entrenador
        </x-table-header-cell>

        <x-table-header-cell align="center">
            Horario
        </x-table-header-cell>

        <x-table-header-cell align="center">
            Lugar
        </x-table-header-cell>

        <x-table-header-cell align="center">
            Estado
        </x-table-header-cell>

        <x-table-header-cell align="center">
            Acciones
        </x-table-header-cell>

    </x-table-header>


    <tbody>

        @forelse($entrenamientos as $entrenamiento)

            <x-table-row>

                {{-- FECHA --}}

                <x-table-cell align="center">

                    {{ \Carbon\Carbon::parse($entrenamiento->fecha)->format('d/m/Y') }}

                </x-table-cell>


                {{-- EQUIPO --}}

                <x-table-cell align="center">

                    {{ $entrenamiento->equipo->nombre ?? '-' }}

                </x-table-cell>


                {{-- CATEGORÍAS --}}

                <x-table-cell align="center">

                    @forelse($entrenamiento->categorias as $categoria)

                        <span class="inline-block bg-indigo-100 text-indigo-700 text-xs px-2 py-1 rounded-full m-1">

                            {{ $categoria->nombre }}

                        </span>

                    @empty

                        -

                    @endforelse

                </x-table-cell>


                {{-- ENTRENADOR --}}

                <x-table-cell align="center">

                    {{ $entrenamiento->entrenador->nombres ?? '' }}
                    {{ $entrenamiento->entrenador->apellidos ?? '' }}

                    @if(!$entrenamiento->entrenador)

                        -

                    @endif

                </x-table-cell>


                {{-- HORARIO --}}

                <x-table-cell align="center">

                    {{ substr($entrenamiento->hora_inicio, 0, 5) }}
                    -
                    {{ substr($entrenamiento->hora_fin, 0, 5) }}

                </x-table-cell>


                {{-- LUGAR --}}

                <x-table-cell align="center">

                    {{ $entrenamiento->lugar ?? '-' }}

                </x-table-cell>


                {{-- ESTADO --}}

                <x-table-cell align="center">

                    <form
                        action="{{ route('entrenamientos.estado', $entrenamiento) }}"
                        method="POST"
                        class="inline"
                    >

                        @csrf
                        @method('PATCH')

                        <select
                            name="estado"
                            onchange="this.form.submit()"
                            class="rounded-full px-4 py-2 text-sm font-semibold border"
                        >

                            <option
                                value="Programado"
                                @selected($entrenamiento->estado == 'Programado')
                            >
                                🟡 Programado
                            </option>

                            <option
                                value="Realizado"
                                @selected($entrenamiento->estado == 'Realizado')
                            >
                                🟢 Realizado
                            </option>

                            <option
                                value="Cancelado"
                                @selected($entrenamiento->estado == 'Cancelado')
                            >
                                🔴 Cancelado
                            </option>

                        </select>

                    </form>

                </x-table-cell>


                {{-- ACCIONES --}}

                <x-table-cell align="center">

                    <div class="flex justify-center gap-2">

                        {{-- VER --}}

                        <div>

                            <a
                                href="{{ route('entrenamientos.show', $entrenamiento) }}"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg inline-flex items-center justify-center"
                                title="Ver"
                            >
                                👁️
                            </a>

                        </div>


                        {{-- EDITAR --}}

                        <div>

                            <a
                                href="{{ route('entrenamientos.edit', $entrenamiento) }}"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded-lg inline-flex items-center justify-center"
                                title="Editar"
                            >
                                ✏️
                            </a>

                        </div>


                        {{-- ELIMINAR --}}

                        <div>

                            <form
                                action="{{ route('entrenamientos.destroy', $entrenamiento) }}"
                                method="POST"
                                class="formulario-eliminar"
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


                        {{-- ASISTENCIA --}}

                        <div>

                            <a
                                href="{{ route('asistencias.create', $entrenamiento) }}"
                                class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-lg inline-flex items-center justify-center"
                                title="Tomar asistencia"
                            >
                                📋
                            </a>

                        </div>

                    </div>

                </x-table-cell>

            </x-table-row>

        @empty

            <tr>

                <td
                    colspan="8"
                    class="text-center py-10 text-gray-500"
                >

                    No hay entrenamientos registrados.

                </td>

            </tr>

        @endforelse

    </tbody>

</x-table>


{{-- PAGINACIÓN --}}

@if(method_exists($entrenamientos, 'links'))

    <div class="mt-6">

        {{ $entrenamientos->links() }}

    </div>

@endif

@endsection
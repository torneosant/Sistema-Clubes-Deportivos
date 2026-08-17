@extends('layouts.app')

@section('titulo', 'Partidos')

@section('contenido')

<x-page-header
    title="⚽ Partidos"
    subtitle="Administración de partidos"
/>


{{-- ACCIONES --}}

<x-actions>

    <div>
        <x-button
            type="button"
            color="green"
            onclick="window.location='{{ route('partidos.create') }}'"
        >
            ➕ Nuevo Partido
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
            Hora
        </x-table-header-cell>

        <x-table-header-cell align="center">
            Equipo
        </x-table-header-cell>

        <x-table-header-cell align="center">
            Rival
        </x-table-header-cell>

        <x-table-header-cell align="center">
            Categoría
        </x-table-header-cell>

        <x-table-header-cell align="center">
            Competencia
        </x-table-header-cell>

        <x-table-header-cell align="center">
            Condición
        </x-table-header-cell>

        <x-table-header-cell align="center">
            Estado
        </x-table-header-cell>

        <x-table-header-cell align="center">
            Acciones
        </x-table-header-cell>

    </x-table-header>


    <tbody>

        @forelse($partidos as $partido)

            <x-table-row>

                <x-table-cell align="center">
                    {{ \Carbon\Carbon::parse($partido->fecha)->format('d/m/Y') }}
                </x-table-cell>

                <x-table-cell align="center">
                    {{ \Carbon\Carbon::parse($partido->hora)->format('H:i') }}
                </x-table-cell>

                <x-table-cell align="center">
                    {{ $partido->equipo->nombre ?? '-' }}
                </x-table-cell>

                <x-table-cell align="center">
                    {{ $partido->rival ?? '-' }}
                </x-table-cell>

                <x-table-cell align="center">
                    {{ $partido->categoria->nombre ?? '-' }}
                </x-table-cell>

                <x-table-cell align="center">
                    {{ $partido->competencia ?? '-' }}
                </x-table-cell>

                <x-table-cell align="center">
                    {{ $partido->condicion ?? '-' }}
                </x-table-cell>

                <x-table-cell align="center">
                    {{ $partido->estado ?? '-' }}
                </x-table-cell>


                {{-- ACCIONES --}}

                <x-table-cell align="center">

                    <div class="flex justify-center items-center gap-2">

                        {{-- EDITAR PARTIDO --}}

                        <div>

                            <a
                                href="{{ route('partidos.edit', $partido) }}"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg inline-flex items-center justify-center"
                                title="Editar partido"
                            >
                                ✏️
                            </a>

                        </div>


                        @if($partido->estado != 'Jugado')

                            {{-- REGISTRAR RESULTADO --}}

                            <div>

                                <a
                                    href="{{ route('partidos.resultado', $partido) }}"
                                    class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-lg inline-flex items-center justify-center"
                                    title="Registrar resultado"
                                >
                                    ⚽
                                </a>

                            </div>

                        @else

                            {{-- EDITAR RESULTADO --}}

                            <div>

                                <a
                                    href="{{ route('partidos.resultado', $partido) }}"
                                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded-lg inline-flex items-center justify-center"
                                    title="Editar resultado"
                                >
                                    ✏️
                                </a>

                            </div>


                            {{-- ELIMINAR --}}

                            <div>

                                <form
                                    action="{{ route('partidos.destroy', $partido) }}"
                                    method="POST"
                                    class="formulario-eliminar"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg inline-flex items-center justify-center"
                                        title="Eliminar partido"
                                    >
                                        🗑️
                                    </button>

                                </form>

                            </div>


                            {{-- ESTADÍSTICAS --}}

                            <div>

                                <a
                                    href="{{ route('partidos.estadisticas', $partido) }}"
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-2 rounded-lg inline-flex items-center justify-center"
                                    title="Estadísticas"
                                >
                                    📊
                                </a>

                            </div>

                        @endif

                    </div>

                </x-table-cell>

            </x-table-row>

        @empty

            <tr>

                <td
                    colspan="9"
                    class="text-center py-10 text-gray-500"
                >
                    No hay partidos registrados.
                </td>

            </tr>

        @endforelse

    </tbody>

</x-table>

@endsection
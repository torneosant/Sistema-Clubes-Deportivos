@extends('layouts.app')

@section('titulo', 'Inscripciones')

@section('contenido')

<x-page-header
    title="📝 Inscripciones"
    subtitle="Gestiona las solicitudes de inscripción de nuevos jugadores."
>

    <div class="flex items-center gap-2 flex-wrap">

        <x-stat
            label="Total"
            :value="$inscripciones->count()"
            icon="📝"
            color="blue"
        />

        <x-stat
            label="Pendientes"
            :value="$pendientes"
            icon="🟡"
            color="yellow"
        />

        <x-stat
            label="Aceptadas"
            :value="$aceptadas"
            icon="🟢"
            color="green"
        />

        <x-stat
            label="Denegadas"
            :value="$denegadas"
            icon="🔴"
            color="red"
        />

    </div>

</x-page-header>


{{-- MENSAJE DE ÉXITO --}}
@if(session('success'))

    <div class="mb-6 rounded-lg bg-green-100 border border-green-300 text-green-700 px-4 py-3">
        {{ session('success') }}
    </div>

@endif


{{-- BOTONES --}}
<x-actions>

    @if(auth()->user()->tienePermiso('inscripciones.crear'))

        <a href="{{ route('inscripciones.create') }}">

            <x-button color="blue">

                ➕ Generar enlace de inscripción

            </x-button>

        </a>

    @endif

</x-actions>


{{-- FILTROS --}}
<x-filter :action="route('inscripciones.index')">

    <x-input
        name="buscar"
        value="{{ request('buscar') }}"
        placeholder="🔍 Buscar nombre, documento o teléfono..."
    />

    <select
        name="estado"
        class="border rounded-xl px-4 py-2"
    >

        <option value="">
            Todos los estados
        </option>

        <option
            value="Pendiente"
            @selected(request('estado') === 'Pendiente')
        >
            Pendientes
        </option>

        <option
            value="Aceptada"
            @selected(request('estado') === 'Aceptada')
        >
            Aceptadas
        </option>

        <option
            value="Denegada"
            @selected(request('estado') === 'Denegada')
        >
            Denegadas
        </option>

        <option
            value="Disponible"
            @selected(request('estado') === 'Disponible')
        >
            Disponibles
        </option>

    </select>

    <x-button
        type="submit"
        color="blue"
    >
        🔍 Buscar
    </x-button>

    <a
        href="{{ route('inscripciones.index') }}"
        class="inline-flex items-center justify-center bg-gray-600 hover:bg-gray-700 text-white px-5 py-2 rounded-xl font-semibold transition-all duration-300 shadow-sm hover:shadow-md"
    >
        Limpiar
    </a>

</x-filter>


{{-- TABLA --}}
<x-table>

    <x-table-header>

        <x-table-header-cell>
            Solicitud
        </x-table-header-cell>

        <x-table-header-cell>
            Categoría
        </x-table-header-cell>

        <x-table-header-cell align="center">
            Estado
        </x-table-header-cell>

        <x-table-header-cell align="center">
            Fecha
        </x-table-header-cell>

        <x-table-header-cell align="center">
            Acciones
        </x-table-header-cell>

    </x-table-header>


    <tbody>

        @forelse($inscripciones as $inscripcion)

            <x-table-row>

                {{-- SOLICITANTE --}}
                <x-table-cell>

                    <div class="font-semibold text-slate-800">

                        👤

                        @if($inscripcion->nombres)

                            {{ $inscripcion->nombres }}
                            {{ $inscripcion->apellidos }}

                        @else

                            Inscripción disponible

                        @endif

                    </div>

                    @if($inscripcion->documento)

                        <div class="text-sm text-gray-500">

                            Documento:
                            {{ $inscripcion->documento }}

                        </div>

                    @endif

                    @if($inscripcion->telefono)

                        <div class="text-sm text-gray-500">

                            📱 {{ $inscripcion->telefono }}

                        </div>

                    @endif

                </x-table-cell>


                {{-- CATEGORÍA --}}
                <x-table-cell>

                    <div class="font-medium">

                        {{ $inscripcion->categoria->nombre ?? 'Sin categoría' }}

                    </div>

                </x-table-cell>


                {{-- ESTADO --}}
                <x-table-cell align="center">

                    @if($inscripcion->estado === 'Pendiente')

                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-700">

                            🟡 Pendiente

                        </span>

                    @elseif($inscripcion->estado === 'Aceptada')

                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-700">

                            🟢 Aceptada

                        </span>

                    @elseif($inscripcion->estado === 'Denegada')

                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-700">

                            🔴 Denegada

                        </span>

                    @elseif($inscripcion->estado === 'Disponible')

                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-700">

                            🔵 Disponible

                        </span>

                    @else

                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-gray-100 text-gray-700">

                            {{ $inscripcion->estado }}

                        </span>

                    @endif

                </x-table-cell>


                {{-- FECHA --}}
                <x-table-cell align="center">

                    <div class="text-sm text-gray-600">

                        {{ $inscripcion->created_at?->format('d/m/Y') }}

                    </div>

                </x-table-cell>


                {{-- ACCIONES --}}
                <x-table-cell align="center">

                    <div class="flex justify-center items-center gap-2">

                        {{-- VER --}}
                        @if(auth()->user()->tienePermiso('inscripciones.ver'))

                            <a
                                href="{{ route('inscripciones.show', ['inscripcion' => $inscripcion->id]) }}"
                            >

                                <x-secondary-button
                                    type="button"
                                    title="Ver inscripción"
                                >

                                    👁️

                                </x-secondary-button>

                            </a>

                        @endif


                        {{-- ACEPTAR --}}
@if(
    $inscripcion->estado === 'Pendiente' &&
    auth()->user()->tienePermiso('inscripciones.aprobar')
)

    <form
        action="{{ route('inscripciones.aceptar', $inscripcion) }}"
        method="POST"
        class="inline"
    >

        @csrf

        <x-button
            type="submit"
            color="green"
            icon
            title="Aceptar inscripción"
        >
            ✅
        </x-button>

    </form>

@endif

                        {{-- DENEGAR --}}
                        @if(
                            $inscripcion->estado === 'Pendiente' &&
                            auth()->user()->tienePermiso('inscripciones.denegar')
                        )

                            <form
                                action="{{ route('inscripciones.denegar', $inscripcion) }}"
                                method="POST"
                                class="inline formulario-eliminar"
                            >

                                @csrf
                                @method('PATCH')

                                <x-button
                                    type="submit"
                                    color="red"
                                    icon
                                    title="Denegar inscripción"
                                >

                                    ❌

                                </x-button>

                            </form>

                        @endif


                        {{-- ELIMINAR --}}
                        @if(auth()->user()->tienePermiso('inscripciones.eliminar'))

                            <form
                                action="{{ route('inscripciones.destroy', $inscripcion) }}"
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

                        @endif

                    </div>

                </x-table-cell>

            </x-table-row>

        @empty

            <tr>

                <td
                    colspan="5"
                    class="text-center py-10 text-gray-500"
                >

                    📝 No hay inscripciones registradas.

                </td>

            </tr>

        @endforelse

    </tbody>

</x-table>


@endsection
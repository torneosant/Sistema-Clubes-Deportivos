@extends('layouts.app')

@section('titulo', 'Asignaciones de Inventario')

@section('contenido')

{{-- ========================================================= --}}
{{-- ENCABEZADO --}}
{{-- ========================================================= --}}

<x-page-header
    title="📤 Asignaciones de Inventario"
    subtitle="Controla dónde se encuentra cada implemento."
/>


{{-- ========================================================= --}}
{{-- ACCIONES --}}
{{-- ========================================================= --}}

<x-actions>

    <a href="{{ route('asignaciones-inventario.create') }}">
        <x-button color="blue">
            ➕ Nueva Asignación
        </x-button>
    </a>

    <a href="{{ route('asignaciones-inventario.excel') }}">
        <x-button color="green">
            📤 Excel
        </x-button>
    </a>

</x-actions>


{{-- ========================================================= --}}
{{-- TABLA --}}
{{-- ========================================================= --}}

<x-table>

    <x-table-header>

        <x-table-header-cell>
            Artículo
        </x-table-header-cell>

        <x-table-header-cell>
            Responsable
        </x-table-header-cell>

        <x-table-header-cell align="center">
            Cantidad
        </x-table-header-cell>

        <x-table-header-cell align="center">
            Fecha
        </x-table-header-cell>

        <x-table-header-cell align="center">
            Acciones
        </x-table-header-cell>

    </x-table-header>


    <tbody>

    @forelse($asignaciones as $asignacion)

        <x-table-row>

            {{-- ================================================= --}}
            {{-- ARTÍCULO --}}
            {{-- ================================================= --}}

            <x-table-cell>

                <span class="font-semibold">
                    {{ $asignacion->inventario->nombre }}
                </span>

            </x-table-cell>


            {{-- ================================================= --}}
            {{-- RESPONSABLE --}}
            {{-- ================================================= --}}

            <x-table-cell>

                @if($asignacion->tipo_destino == 'Entrenador')

                    👨‍🏫
                    {{ $asignacion->entrenador?->nombres }}
                    {{ $asignacion->entrenador?->apellidos }}

                @elseif($asignacion->tipo_destino == 'Bodega')

                    📦 Bodega

                @else

                    ✍ {{ $asignacion->destino_otro }}

                @endif

            </x-table-cell>


            {{-- ================================================= --}}
            {{-- CANTIDAD --}}
            {{-- ================================================= --}}

            <x-table-cell align="center">

                @php
                    $pendiente =
                        $asignacion->cantidad
                        - $asignacion->cantidad_devuelta;
                @endphp

                @if($pendiente > 0)

                    <span class="font-semibold">
                        {{ $pendiente }}
                    </span>

                @else

                    <span
                        class="bg-green-100 text-green-700
                               px-3 py-1 rounded-full
                               text-sm font-semibold"
                    >
                        Devuelto
                    </span>

                @endif

            </x-table-cell>


            {{-- ================================================= --}}
            {{-- FECHA --}}
            {{-- ================================================= --}}

            <x-table-cell align="center">

                {{ $asignacion->fecha }}

            </x-table-cell>


            {{-- ================================================= --}}
            {{-- ACCIONES --}}
            {{-- ================================================= --}}

            <x-table-cell align="center">

                <div class="flex justify-center items-center gap-2">

                    {{-- ================================================= --}}
                    {{-- NUEVA ASIGNACIÓN DEL MISMO ARTÍCULO --}}
                    {{-- ================================================= --}}

                    <a
                        href="{{ route('asignaciones-inventario.create') }}?inventario={{ $asignacion->inventario_id }}"
                    >

                        <x-button
                            color="green"
                            icon
                            title="Nueva asignación"
                        >
                            ➕
                        </x-button>

                    </a>


                    {{-- ================================================= --}}
                    {{-- DEVOLVER --}}
                    {{-- ================================================= --}}

                    @if(
                        ($asignacion->cantidad - $asignacion->cantidad_devuelta) > 0
                    )

                        <x-button
                            type="button"
                            color="blue"
                            icon
                            title="Devolver"
                            onclick="devolver({{ $asignacion->id }})"
                        >
                            ↩️
                        </x-button>

                    @endif


                    {{-- ================================================= --}}
                    {{-- FORMULARIO OCULTO PARA DEVOLUCIÓN --}}
                    {{-- ================================================= --}}

                    <form
                        id="devolver-{{ $asignacion->id }}"
                        action="{{ route(
                            'asignaciones-inventario.devolver',
                            $asignacion
                        ) }}"
                        method="POST"
                        class="hidden"
                    >

                        @csrf

                        <input
                            type="hidden"
                            name="cantidad"
                            id="cantidad-{{ $asignacion->id }}"
                        >

                    </form>


                    {{-- ================================================= --}}
                    {{-- ELIMINAR --}}
                    {{-- ================================================= --}}

                    <form
                        action="{{ route(
                            'asignaciones-inventario.destroy',
                            $asignacion
                        ) }}"
                        method="POST"
                        class="inline"
                    >

                        @csrf

                        @method('DELETE')

                        <x-button
                            type="button"
                            color="red"
                            icon
                            title="Eliminar asignación"
                            onclick="confirmarEliminar(this)"
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
                class="px-4 py-10 text-center text-gray-500"
            >
                No existen asignaciones registradas.
            </td>

        </tr>

    @endforelse

    </tbody>

</x-table>

@endsection


{{-- ========================================================= --}}
{{-- SCRIPTS --}}
{{-- ========================================================= --}}

@section('scripts')

<script>

function devolver(id) {

    Swal.fire({

        title: 'Cantidad a devolver',

        text: 'Indique cuántas unidades está devolviendo el entrenador.',

        input: 'number',

        inputAttributes: {
            min: 1,
            step: 1
        },

        inputPlaceholder: 'Cantidad',

        showCancelButton: true,

        confirmButtonText: 'Devolver',

        cancelButtonText: 'Cancelar',

        confirmButtonColor: '#16a34a',

        cancelButtonColor: '#64748b',

        inputValidator: (value) => {

            if (!value || value < 1) {
                return 'Debe indicar una cantidad válida.';
            }

        }

    }).then((result) => {

        if (!result.isConfirmed) {
            return;
        }

        document.getElementById(
            'cantidad-' + id
        ).value = result.value;

        document.getElementById(
            'devolver-' + id
        ).submit();

    });

}

</script>

@endsection
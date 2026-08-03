@extends('layouts.app')

@section('titulo','Trazabilidad')

@section('contenido')

<x-page-header
    title="📦 Trazabilidad del artículo"
    :subtitle="$inventario->nombre" />

    <form method="GET" class="mb-5">

<div class="flex flex-wrap gap-4 items-end">

    <div>
        <label class="block text-sm font-medium">Desde</label>

        <input
            type="date"
            name="desde"
            value="{{ request('desde') }}"
            class="border rounded-lg px-3 py-2">
    </div>

    <div>
        <label class="block text-sm font-medium">Hasta</label>

        <input
            type="date"
            name="hasta"
            value="{{ request('hasta') }}"
            class="border rounded-lg px-3 py-2">
    </div>

    <div>
        <label class="block text-sm font-medium">Responsable</label>

        <select
            name="responsable"
            class="border rounded-lg px-3 py-2">

            <option value="">Todos</option>

            @foreach($responsables as $responsable)

                <option
                    value="{{ $responsable }}"
                    @selected(request('responsable')==$responsable)>

                    {{ $responsable }}

                </option>

            @endforeach

        </select>

    </div>

    <button
        class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg">

        🔍 Filtrar

    </button>

    <a
        href="{{ route('inventario.trazabilidad',$inventario) }}"
        class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg">

        🔄 Limpiar

    </a>

</div>

</form>



<x-card>

<div class="flex justify-between items-center mb-6">

    <div>
        <strong>Stock actual:</strong>
        {{ $inventario->cantidad }}
    </div>

    <div class="flex gap-2">

        <x-button color="green">
            📊 Excel
        </x-button>

        <x-button color="red">
            🖨 PDF
        </x-button>

    </div>

</div>

<table class="w-full">

    <thead>

        <tr class="border-b">

            <th class="text-left py-3">Fecha</th>

            <th>Movimiento</th>

            <th>Responsable</th>

            <th>Cantidad</th>

            <th>Observaciones</th>

        </tr>

    </thead>

    <tbody>

    @forelse($movimientos as $movimiento)

        <tr class="border-b hover:bg-gray-50">

            <td>

                {{ $movimiento->fecha }}

            </td>

            <td>

                @if($movimiento->tipo=='Entrega')

                    <span class="text-blue-600 font-semibold">

                        📤 Entrega

                    </span>

                @else

                    <span class="text-green-600 font-semibold">

                        📥 Devolución

                    </span>

                @endif

            </td>

            <td>

                {{ $movimiento->responsable }}

            </td>

            <td class="text-center">

                {{ $movimiento->cantidad }}

            </td>

            <td>

                {{ $movimiento->observaciones }}

            </td>

        </tr>

    @empty

        <tr>

            <td colspan="5" class="text-center py-6">

                No existen movimientos registrados.

            </td>

        </tr>

    @endforelse

    </tbody>

</table>

</x-card>

@endsection
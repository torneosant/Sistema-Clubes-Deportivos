@extends('layouts.app')

@section('titulo','Inscripciones')

@section('contenido')

<x-page-header
    title="📝 Inscripciones"
    subtitle="Gestiona las solicitudes de inscripción al club."
/>

{{-- CONTADORES --}}

<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">

    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-5">
        <div class="text-sm text-yellow-700">
            Pendientes
        </div>

        <div class="text-3xl font-bold text-yellow-700">
            {{ $pendientes }}
        </div>
    </div>

    <div class="bg-green-50 border border-green-200 rounded-xl p-5">
        <div class="text-sm text-green-700">
            Aceptadas
        </div>

        <div class="text-3xl font-bold text-green-700">
            {{ $aceptadas }}
        </div>
    </div>

    <div class="bg-red-50 border border-red-200 rounded-xl p-5">
        <div class="text-sm text-red-700">
            Denegadas
        </div>

        <div class="text-3xl font-bold text-red-700">
            {{ $denegadas }}
        </div>
    </div>

</div>


<x-card>

    <div class="flex flex-col md:flex-row justify-between gap-4 mb-6">

        <form
            method="GET"
            class="flex flex-col md:flex-row gap-3 flex-1">

            <input
                type="text"
                name="buscar"
                value="{{ request('buscar') }}"
                placeholder="Buscar por nombre, documento o teléfono..."
                class="border rounded-lg px-4 py-2 flex-1">

            <select
                name="estado"
                class="border rounded-lg px-4 py-2">

                <option value="">
                    Todos los estados
                </option>

                <option
                    value="Pendiente"
                    @selected(request('estado') == 'Pendiente')>
                    Pendientes
                </option>

                <option
                    value="Aceptada"
                    @selected(request('estado') == 'Aceptada')>
                    Aceptadas
                </option>

                <option
                    value="Denegada"
                    @selected(request('estado') == 'Denegada')>
                    Denegadas
                </option>

            </select>

            <button
                class="bg-gray-700 hover:bg-gray-800 text-white px-5 py-2 rounded-lg">

                Buscar

            </button>

        </form>


        <a
            href="{{ route('inscripciones.create') }}"
            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-center">

            ➕ Generar inscripción

        </a>

    </div>


    <div class="overflow-x-auto">

        <table class="w-full">

            <thead>

                <tr class="border-b text-left">

                    <th class="p-3">
                        Nombre
                    </th>

                    <th class="p-3">
                        Categoría
                    </th>

                    <th class="p-3">
                        Fecha
                    </th>

                    <th class="p-3">
                        Estado
                    </th>

                    <th class="p-3 text-right">
                        Acción
                    </th>

                </tr>

            </thead>

            <tbody>

            @forelse($inscripciones as $inscripcion)

                <tr class="border-b hover:bg-gray-50">

                    <td class="p-3">

                        <div class="font-semibold">

                            {{ $inscripcion->nombres }}
                            {{ $inscripcion->apellidos }}

                        </div>

                        @if($inscripcion->documento)

                            <div class="text-sm text-gray-500">

                                CC: {{ $inscripcion->documento }}

                            </div>

                        @endif

                    </td>


                    <td class="p-3">

                        {{ $inscripcion->categoria->nombre ?? 'General' }}

                    </td>


                    <td class="p-3">

                        {{ $inscripcion->created_at->format('d/m/Y') }}

                    </td>


                    <td class="p-3">

                        @if($inscripcion->estado == 'Pendiente')

                            <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm">
                                🟡 Pendiente
                            </span>

                        @elseif($inscripcion->estado == 'Aceptada')

                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">
                                🟢 Aceptada
                            </span>

                        @else

                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm">
                                🔴 Denegada
                            </span>

                        @endif

                    </td>


                    <td class="p-3 text-right">

                        <a
                            href="{{ route('inscripciones.show', $inscripcion) }}"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">

                            Ver

                        </a>

                    </td>

                </tr>

            @empty

                <tr>

                    <td
                        colspan="5"
                        class="p-8 text-center text-gray-500">

                        No hay solicitudes de inscripción.

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</x-card>

@endsection
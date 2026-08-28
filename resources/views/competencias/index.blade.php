@extends('layouts.app')

@section('titulo','Competencias')

@section('contenido')

<x-page-header
    title="🏆 Competencias"
    subtitle="Administra campeonatos, festivales y eventos deportivos."
/>

<div class="flex justify-between items-center mb-5">

    <div>
        <h2 class="text-xl font-semibold text-slate-800">
            Competencias del club
        </h2>
    </div>

    <a href="{{ route('competencias.create') }}"
       class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg">

        + Nueva competencia

    </a>

</div>

<x-card>

@if($competencias->count())

<div class="overflow-x-auto">

<table class="w-full text-sm">

<thead>
<tr class="border-b text-left">

    <th class="py-3">Competencia</th>
    <th class="py-3">Tipo</th>
    <th class="py-3">Categoría</th>
    <th class="py-3">Inicio</th>
    <th class="py-3">Estado</th>
    <th class="py-3 text-right">Acciones</th>

</tr>
</thead>

<tbody>

@foreach($competencias as $competencia)

<tr class="border-b hover:bg-slate-50">

    <td class="py-3 font-medium">
        {{ $competencia->nombre }}
    </td>

    <td class="py-3">

        @switch($competencia->tipo)

            @case('campeonato')

                🏆 Campeonato

                @break

            @case('festival')

                🎪 Festival

                @break

            @case('evento')

                🎯 Evento

                @break

        @endswitch

    </td>

    <td class="py-3">

        {{ $competencia->categoria?->nombre ?? 'Todas' }}

    </td>

    <td class="py-3">

        {{ $competencia->fecha_inicio?->format('d/m/Y') ?? '—' }}

    </td>

    <td class="py-3">

        @switch($competencia->estado)

            @case('proximo')

                <span class="px-2 py-1 rounded bg-blue-100 text-blue-700">
                    Próximo
                </span>

                @break

            @case('en_curso')

                <span class="px-2 py-1 rounded bg-green-100 text-green-700">
                    En curso
                </span>

                @break

            @case('finalizado')

                <span class="px-2 py-1 rounded bg-gray-100 text-gray-700">
                    Finalizado
                </span>

                @break

            @case('cancelado')

                <span class="px-2 py-1 rounded bg-red-100 text-red-700">
                    Cancelado
                </span>

                @break

        @endswitch

    </td>

    <td class="py-3">

        <div class="flex justify-end gap-2">

            {{-- VER --}}

            <a href="{{ route('competencias.show', $competencia) }}"
               class="px-3 py-1 bg-slate-100 rounded hover:bg-slate-200">

                Ver

            </a>


            {{-- EDITAR --}}

            <a href="{{ route('competencias.edit', $competencia) }}"
               class="px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200">

                Editar

            </a>


           

            {{-- ELIMINAR --}}

            <form method="POST"
                  action="{{ route('competencias.destroy', $competencia) }}"
                  onsubmit="return confirm('¿Eliminar esta competencia?')">

                @csrf

                @method('DELETE')

                <button
                    type="submit"
                    class="px-3 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200">

                    Eliminar

                </button>

            </form>

        </div>

    </td>

</tr>

@endforeach

</tbody>

</table>

</div>

@else

<div class="text-center py-12 text-slate-500">

    <div class="text-5xl mb-4">
        🏆
    </div>

    <p class="text-lg font-medium">
        No hay competencias registradas.
    </p>

    <p class="mt-1">
        Crea el primer campeonato, festival o evento del club.
    </p>

</div>

@endif

</x-card>

@endsection
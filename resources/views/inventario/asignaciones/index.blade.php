@extends('layouts.app')

@section('titulo','Asignaciones de Inventario')

@section('contenido')

<x-page-header
title="📤 Asignaciones de Inventario"
subtitle="Controla dónde se encuentra cada implemento."/>

<div class="flex justify-end mb-5">

    <a href="{{ route('asignaciones-inventario.create') }}"
       class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl">

        ➕ Nueva Asignación

    </a>

</div>

<a href="{{ route('asignaciones-inventario.excel') }}"
   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-xl">

    📤 Excel

</a>

<x-card>

<table class="w-full">

    <thead>

        <tr class="border-b">

            <th class="text-left py-3">Artículo</th>
            <th>Responsable</th>
            <th>Cantidad</th>
            <th>Fecha</th>
            <th width="140">Acciones</th>

        </tr>

    </thead>

    <tbody>

   @forelse($asignaciones as $asignacion)

<tr class="border-b hover:bg-gray-50">

    <td>
        {{ $asignacion->inventario->nombre }}
    </td>

    <td>

        @if($asignacion->tipo_destino=='Entrenador')

            👨‍🏫 {{ $asignacion->entrenador?->nombres }} {{ $asignacion->entrenador?->apellidos }}

        @elseif($asignacion->tipo_destino=='Bodega')

            📦 Bodega

        @else

            ✍ {{ $asignacion->destino_otro }}

        @endif

    </td>

    <td>

    @php
        $pendiente = $asignacion->cantidad - $asignacion->cantidad_devuelta;
    @endphp

    @if($pendiente > 0)

        {{ $pendiente }}

    @else

        <span class="text-green-600 font-semibold">
            Devuelto
        </span>

    @endif

</td>

    <td>
        {{ $asignacion->fecha }}
    </td>

    {{-- ACCIONES --}}
    <td class="text-center">

        <div class="flex items-center justify-center gap-2">

<x-button
    color="green"
    onclick="window.location='{{ route('asignaciones-inventario.create') }}?inventario={{ $asignacion->inventario_id }}'">

    ➕

</x-button>


            @if(($asignacion->cantidad - $asignacion->cantidad_devuelta) > 0)

                <x-button
                    color="green"
                    icon
                    onclick="devolver({{ $asignacion->id }})">

                    ↩️

                </x-button>

            @endif

            <form
    id="devolver-{{ $asignacion->id }}"
    action="{{ route('asignaciones-inventario.devolver',$asignacion) }}"
    method="POST"
    style="display:none;">

    @csrf

    <input
        type="hidden"
        name="cantidad"
        id="cantidad-{{ $asignacion->id }}">

</form>

            

            <form
                action="{{ route('asignaciones-inventario.destroy',$asignacion) }}"
                method="POST"
                class="inline">

                @csrf
                @method('DELETE')

                <x-button
                    color="red"
                    icon
                    onclick="confirmarEliminar(this)">

                    🗑️

                </x-button>

            </form>

        </div>

    </td>

</tr>

@empty

<tr>

    <td colspan="5" class="text-center py-6">

        No existen asignaciones registradas.

    </td>

</tr>

@endforelse

    </tbody>

</table>

</x-card>

<script>

function devolver(id){

    Swal.fire({

        title:'Cantidad a devolver',

        input:'number',

        inputAttributes:{
            min:1
        },

        showCancelButton:true,

        confirmButtonText:'Devolver',

    }).then((result)=>{

        if(result.isConfirmed){

            document.getElementById('cantidad-'+id).value=result.value;

            document.getElementById('devolver-'+id).submit();

        }

    });

}

</script>

@endsection
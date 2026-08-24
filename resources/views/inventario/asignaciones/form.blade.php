@extends('layouts.app')

@section('titulo','Nueva Asignación')

@section('contenido')

<x-page-header
title="📤 Nueva Asignación"
subtitle="Entrega implementos a un responsable."/>

<x-card>

@if($modo=='crear')

<form action="{{ route('asignaciones-inventario.store') }}" method="POST">

@else

<form action="{{ route('asignaciones-inventario.update',$asignacion) }}" method="POST">

    @method('PUT')

@endif

@csrf

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

        <div>

            <label class="font-semibold">Artículo</label>

            <select
                name="inventario_id"
                class="w-full border rounded-xl p-3 mt-2"
                required>

                <option value="">Seleccione...</option>

                @foreach($articulos as $articulo)

                   <option
    value="{{ $articulo->id }}"
    @selected(
        old(
            'inventario_id',
            $asignacion->inventario_id ?? $inventarioSeleccionado ?? ''
        ) == $articulo->id
    )>

    {{ $articulo->nombre }}

</option>

                @endforeach

            </select>

        </div>

       <div>

    <label class="font-semibold">

        Tipo destino

    </label>

    <select
        id="tipo_destino"
        name="tipo_destino"
        class="w-full border rounded-xl p-3 mt-2">

       <option value="Entrenador"
    @selected(old('tipo_destino', $asignacion->tipo_destino ?? '')=='Entrenador')>
    Entrenador
</option>

<option value="Bodega"
    @selected(old('tipo_destino', $asignacion->tipo_destino ?? '')=='Bodega')>
    Bodega
</option>

<option value="Otro"
    @selected(old('tipo_destino', $asignacion->tipo_destino ?? '')=='Otro')>
    Otro
</option>

    </select>

</div>

<div id="divEntrenador">

    <label class="font-semibold">

        Entrenador

    </label>

    <select
        name="entrenador_id"
        class="w-full border rounded-xl p-3 mt-2">

        <option value="">Seleccione...</option>

        @foreach($entrenadores as $entrenador)

          <option
    value="{{ $entrenador->id }}"
    @selected(old('entrenador_id', $asignacion->entrenador_id ?? '') == $entrenador->id)>

    {{ $entrenador->nombres }} {{ $entrenador->apellidos }}

</option>

        @endforeach

    </select>

</div>

<div
id="divOtro"
style="display:none;">

    <label class="font-semibold">

        Responsable

    </label>

   <input
    type="text"
    name="destino_otro"
    value="{{ old('destino_otro', $asignacion->destino_otro ?? '') }}"
    class="w-full border rounded-xl p-3 mt-2">

</div>

        <div>

            <label class="font-semibold">Cantidad</label>

          <input
    type="number"
    name="cantidad"
    value="{{ old('cantidad', $asignacion->cantidad ?? '') }}"
    class="w-full border rounded-xl p-3 mt-2"
    required>

        </div>

        <div>

            <label class="font-semibold">Fecha</label>

@php
    $anioTrabajo = session('anio_trabajo', date('Y'));

    $fechaPorDefecto = $asignacion->fecha
        ?? date($anioTrabajo . '-m-d');
@endphp



     <input
    type="date"
    name="fecha"
    value="{{ old('fecha', $fechaPorDefecto) }}"
    class="w-full border rounded-xl p-3 mt-2"
    required>

        </div>

    </div>

    <div class="mt-5">

        <label class="font-semibold">

            Observaciones

        </label>

        <textarea
    name="observaciones"
    rows="4"
    class="w-full border rounded-xl p-3 mt-2">{{ old('observaciones', $asignacion->observaciones ?? '') }}</textarea>

    </div>

    <div class="mt-8 flex gap-3">

        <button
            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl">

            Guardar

        </button>

        <a
            href="{{ route('asignaciones-inventario.index') }}"
            class="bg-gray-200 hover:bg-gray-300 px-6 py-3 rounded-xl">

            Cancelar

        </a>

    </div>

</form>

</x-card>

<script>

document.addEventListener('DOMContentLoaded',function(){

    const tipo=document.getElementById('tipo_destino');

    const entrenador=document.getElementById('divEntrenador');

    const otro=document.getElementById('divOtro');

    function actualizar(){

        entrenador.style.display='none';
        otro.style.display='none';

        if(tipo.value==='Entrenador'){
            entrenador.style.display='block';
        }

        if(tipo.value==='Otro'){
            otro.style.display='block';
        }

    }

    actualizar();

    tipo.addEventListener('change',actualizar);

});

</script>


@endsection
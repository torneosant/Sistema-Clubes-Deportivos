@extends('layouts.app')

@section('titulo','Centro de Documentación')

@section('contenido')

<x-page-header
title="📚 Centro de Documentación"
subtitle="Documentos oficiales del club">

<a href="{{ route('documentos.create') }}"
class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl">

+ Nuevo Documento

</a>

</x-page-header>

<div class="mb-5">

    <input
        type="text"
        id="buscarDocumento"
        placeholder="🔍 Buscar documentos..."
        class="w-full md:w-96 rounded-xl border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-blue-500">

</div>

<x-card>

<table class="w-full">

<thead>

<tr class="border-b">
<th>Título</th>

<th>Tipo</th>

<th>Archivo</th>
<th>Fecha</th>

<th width="220">Acciones</th>

</tr>

</thead>

<tbody>

@forelse($documentos as $doc)

<tr class="border-b hover:bg-gray-50">

<td>{{ $doc->titulo }}</td>

<td>{{ $doc->tipoDocumento->nombre }}</td>

<td>

    <span class="inline-flex items-center gap-2">

        📄

        {{ basename($doc->archivo) }}

    </span>

</td>

<td>{{ $doc->fecha }}</td>

<td>

<div class="flex items-center gap-2">

    <a href="{{ asset('storage/'.$doc->archivo) }}"
       target="_blank"
       class="inline-flex items-center px-3 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg text-sm transition">

        👁
    </a>

    <a href="{{ asset('storage/'.$doc->archivo) }}"
       download
       class="inline-flex items-center px-3 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg text-sm transition">

        ⬇

    </a>

    <form action="{{ route('documentos.destroy',$doc) }}"
          method="POST"
          class="inline">

        @csrf
        @method('DELETE')

        <button
            type="button"
            onclick="confirmarEliminar(this)"
            class="inline-flex items-center px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg text-sm transition">

            🗑

        </button>

    </form>

</div>

</td>
</tr>

@empty

<tr>

<td colspan="4" class="text-center py-6">

No existen documentos.

</td>

</tr>

@endforelse

</tbody>

</table>

</x-card>
@push('scripts')

<script>

document.getElementById('buscarDocumento').addEventListener('keyup',function(){

let texto=this.value.toLowerCase();

document.querySelectorAll("tbody tr").forEach(function(fila){

fila.style.display=fila.innerText.toLowerCase().includes(texto)
? ""
: "none";

});

});

</script>

@endpush

@endsection
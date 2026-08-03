@extends('layouts.app')

@section('titulo','Nuevo Tipo')

@section('contenido')

<x-page-header
title="➕ Nuevo Tipo de Artículo"
subtitle="Crea un nuevo tipo de implemento."/>

<x-card>

<form
method="POST"
action="{{ route('tipos-articulo.store') }}">

@csrf

<div class="mb-5">

<label class="font-semibold">

Nombre

</label>

<input
type="text"
name="nombre"
class="w-full border rounded-xl p-3 mt-2"
required>

</div>

<div class="mb-6">

<label>

<input
type="checkbox"
name="activo"
checked>

Activo

</label>

</div>

<button
class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl">

Guardar

</button>

<a
href="{{ route('tipos-articulo.index') }}"
class="ml-3">

Cancelar

</a>

</form>

</x-card>

@endsection
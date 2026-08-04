@extends('layouts.app')

@section('titulo','Módulo')

@section('contenido')

<x-page-header
title="{{ $modo=='crear' ? '➕ Nuevo módulo' : '✏️ Editar módulo' }}" />

<x-card>

<form method="POST"
action="{{ $modo=='crear'
? route('modulos.store')
: route('modulos.update',$modulo) }}">

@csrf

@if($modo=='editar')

@method('PUT')

@endif

<div class="grid grid-cols-2 gap-4">

<div>

<label>Nombre</label>

<input
type="text"
name="nombre"
class="form-control w-full"
value="{{ old('nombre',$modulo->nombre) }}">

</div>

<div>

<label>Slug</label>

<input
type="text"
name="slug"
class="form-control w-full"
value="{{ old('slug',$modulo->slug) }}">

</div>

</div>

<div class="mt-4">

<label>

<input
type="checkbox"
name="activo"
value="1"
@checked(old('activo',$modulo->activo ?? true))>

Activo

</label>

</div>

<div class="mt-5">

<x-button color="blue">

💾 Guardar

</x-button>

</div>

</form>

</x-card>

@endsection
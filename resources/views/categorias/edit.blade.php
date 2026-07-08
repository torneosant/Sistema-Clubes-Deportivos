@extends('layouts.app')

@section('titulo','Editar Categoría')

@section('contenido')

<div class="max-w-lg mx-auto bg-white shadow rounded-lg p-6">

<h2 class="text-2xl font-bold mb-6">

Editar Categoría

</h2>

<form method="POST"
      action="{{ route('categorias.update',$categoria) }}">

@csrf
@method('PUT')

<label class="block mb-2 font-semibold">

Nombre

</label>

<input
type="text"
name="nombre"
value="{{ old('nombre',$categoria->nombre) }}"
class="w-full border rounded-lg px-4 py-2 mb-6">

<button
class="bg-blue-600 text-white px-6 py-2 rounded">

Guardar Cambios

</button>

</form>

</div>

@endsection 
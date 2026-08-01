@extends('layouts.app')

@section('contenido')

<div class="max-w-4xl mx-auto">

<form action="{{ route('configuracion.updateDeportivo') }}" method="POST">

@csrf
@method('PUT')

<div class="bg-white rounded-xl shadow">

<div class="bg-slate-800 text-white px-6 py-4 rounded-t-xl">

<h2 class="text-2xl font-bold">
⚽ Configuración Deportiva
</h2>

</div>

<div class="p-6">

<div class="grid grid-cols-2 gap-6">

<div>

<label>Temporada Activa</label>

<input
type="text"
name="temporada"
value="{{ old('temporada',$configuracion->temporada) }}"
class="w-full border rounded-lg p-3 mt-2">

</div>

<div>

<label>Año Deportivo</label>

<input
type="text"
name="anio"
value="{{ old('anio',$configuracion->anio) }}"
class="w-full border rounded-lg p-3 mt-2">

</div>

</div>

<div class="mt-8 text-right">

<button class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg">

Guardar Cambios

</button>

</div>

</div>

</div>

</form>

</div>

@endsection
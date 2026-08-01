@extends('layouts.app')

@section('contenido')

<div class="max-w-4xl mx-auto">

<form action="{{ route('configuracion.updateSistema') }}" method="POST">

@csrf
@method('PUT')

<div class="bg-white rounded-xl shadow">

<div class="bg-slate-800 text-white px-6 py-4 rounded-t-xl">

<h2 class="text-2xl font-bold">
⚙️ Configuración del Sistema
</h2>

</div>

<div class="p-6">

<div class="grid grid-cols-3 gap-6">

<div>

<label>Moneda</label>

<select
name="moneda"
class="w-full border rounded-lg p-3 mt-2">

<option value="COP" {{ $configuracion->moneda=='COP' ? 'selected' : '' }}>
COP
</option>

<option value="USD" {{ $configuracion->moneda=='USD' ? 'selected' : '' }}>
USD
</option>

</select>

</div>

<div>

<label>Idioma</label>

<select
name="idioma"
class="w-full border rounded-lg p-3 mt-2">

<option value="Español" {{ $configuracion->idioma=='Español' ? 'selected' : '' }}>
Español
</option>

</select>

</div>

<div>

<label>Zona Horaria</label>

<select
name="zona_horaria"
class="w-full border rounded-lg p-3 mt-2">

<option value="America/Bogota"
{{ $configuracion->zona_horaria=='America/Bogota' ? 'selected' : '' }}>

América/Bogotá

</option>

</select>

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
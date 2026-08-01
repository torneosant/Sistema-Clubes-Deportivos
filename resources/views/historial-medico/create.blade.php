@extends('layouts.app')

@section('titulo','Nuevo Historial Médico')

@section('contenido')

<div class="max-w-5xl mx-auto">

<div class="bg-white rounded-2xl shadow-lg">

<div class="bg-red-700 text-white px-6 py-4 rounded-t-2xl">

<h2 class="text-2xl font-bold">
❤️ Nuevo Registro Médico
</h2>

<p class="text-red-100 mt-1">
{{ $jugador->nombres }} {{ $jugador->apellidos }}
</p>

</div>

<form method="POST" action="{{ route('historial-medico.store') }}" class="p-6">

@csrf

<input type="hidden" name="jugador_id" value="{{ $jugador->id }}">

<div class="grid grid-cols-2 gap-6">

<div>
<label>Fecha</label>

<input
type="date"
name="fecha"
class="w-full border rounded-lg p-2"
required>
</div>

<div>
<label>Tipo</label>

<select
name="tipo"
class="w-full border rounded-lg p-2">

<option>Lesión</option>
<option>Enfermedad</option>
<option>Control</option>
<option>Cirugía</option>

</select>

</div>

<div class="col-span-2">

<label>Diagnóstico</label>

<textarea
name="diagnostico"
rows="3"
class="w-full border rounded-lg p-2"></textarea>

</div>

<div class="col-span-2">

<label>Tratamiento</label>

<textarea
name="tratamiento"
rows="3"
class="w-full border rounded-lg p-2"></textarea>

</div>

<div>

<label>Estado</label>

<select
name="estado"
class="w-full border rounded-lg p-2">

<option>En tratamiento</option>
<option>Recuperado</option>

</select>

</div>

<div>

<label>Días incapacidad</label>

<input
type="number"
name="dias_incapacidad"
class="w-full border rounded-lg p-2">

</div>

<div class="col-span-2 flex justify-end gap-3">

@if($jugador)

<a href="{{ route('jugadores.show',$jugador) }}"
class="bg-gray-600 text-white px-4 py-2 rounded-lg">

Cancelar

</a>

@else

<a href="{{ route('historial-medico.index') }}"
class="bg-gray-600 text-white px-4 py-2 rounded-lg">

Cancelar

</a>

@endif

<button
class="bg-red-600 text-white px-5 py-2 rounded-lg">

Guardar

</button>

</div>

</div>

</form>

</div>

</div>

@endsection
@extends('layouts.app')

@section('titulo')
⚽ Registrar Resultado
@endsection

@section('contenido')

<form method="POST" action="{{ route('partidos.resultado.store',$partido) }}">
@csrf

<div class="bg-white rounded-xl shadow p-6 max-w-xl">

<h2 class="text-2xl font-bold mb-2">

{{ $partido->equipo->nombre }}

vs

{{ $partido->rival }}

</h2>

<p class="text-gray-500 mb-6">

{{ \Carbon\Carbon::parse($partido->fecha)->format('d/m/Y') }}

</p>

<div class="grid grid-cols-2 gap-6">

<div>

<label class="font-semibold">
Goles del Club
</label>

<input
type="number"
name="goles_favor"
min="0"
value="{{ $partido->goles_favor }}"
class="w-full border rounded-lg p-2">

</div>

<div>

<label class="font-semibold">
Goles Rival
</label>

<input
type="number"
name="goles_contra"
min="0"
value="{{ $partido->goles_contra }}"
class="w-full border rounded-lg p-2">

</div>

</div>

<div class="mt-5">

<label class="font-semibold">

Observaciones

</label>

<textarea
name="observaciones"
rows="3"
class="w-full border rounded-lg p-2">{{ $partido->observaciones }}</textarea>

</div>

<div class="flex gap-3 mt-6">

<button
class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded">

💾 Guardar Resultado

</button>

<a
href="{{ route('partidos.index') }}"
class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded">

Cancelar

</a>

</div>

</div>

</form>

@endsection
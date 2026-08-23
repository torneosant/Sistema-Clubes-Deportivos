@extends('layouts.app')

@section('titulo','Nuevo Registro Médico')

@section('contenido')

<div class="max-w-5xl mx-auto bg-white rounded-xl shadow p-8">

    <h1 class="text-3xl font-bold text-red-600 mb-8">

        ❤️ Nuevo Registro Médico

    </h1>

@if ($errors->any())
<div class="mb-5 bg-red-100 border border-red-300 text-red-700 rounded-lg p-4">
    <ul>
        @foreach($errors->all() as $error)
            <li>• {{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('historial-medico.store') }}" method="POST">

@csrf

<div class="col-span-2">

    <label class="font-semibold">
        Jugador
    </label>

    <select
        name="jugador_id"
        class="w-full border rounded-lg p-2"
        required>

        <option value="">Seleccione un jugador</option>

        @foreach($jugadores as $j)

            <option value="{{ $j->id }}">

                {{ $j->apellidos }}, {{ $j->nombres }}

            </option>

        @endforeach

    </select>

</div>

<div class="grid grid-cols-2 gap-6">

<div>



<label class="font-semibold">Fecha</label>

@php
    $clubId = auth()->user()->club_id;

    $configuracion = \App\Models\Configuracion::find($clubId);

    $anio = session(
        'anio_trabajo',
        $configuracion?->anio ?? date('Y')
    );

    $fechaInicial = $anio . '-01-01';
@endphp

<input
    type="date"
    name="fecha"
    value="{{ old('fecha', $fechaInicial) }}"
    class="w-full border rounded-lg p-2"
    required>

</div>

<div>

<label class="font-semibold">Estado</label>

<select
name="estado"
class="w-full border rounded-lg p-2">

<option>Activo</option>

<option>En recuperación</option>

<option>Alta médica</option>

</select>

</div>

<div>

<label class="font-semibold">Tipo</label>

<select
name="tipo"
class="w-full border rounded-lg p-2">

<option>Esguince</option>

<option>Contractura</option>

<option>Desgarro</option>

<option>Fractura</option>

<option>Luxación</option>

<option>Golpe</option>

<option>Enfermedad</option>

<option>Otro</option>

</select>

</div>

<div>

<label class="font-semibold">Zona afectada</label>

<input
type="text"
name="zona"
class="w-full border rounded-lg p-2">

</div>

<div>

<label class="font-semibold">

Días incapacidad

</label>

<input
type="number"
name="dias_incapacidad"
value="0"
class="w-full border rounded-lg p-2">

</div>

<div>

<label class="font-semibold">

Fecha Alta

</label>

<input
    type="date"
    name="fecha_alta"
    value="{{ old('fecha_alta', $fechaInicial) }}"
    class="w-full border rounded-lg p-2">

</div>

<div class="col-span-2">

<label class="font-semibold">

Diagnóstico

</label>

<textarea

name="diagnostico"

rows="3"

class="w-full border rounded-lg p-2">

</textarea>

</div>

<div class="col-span-2">

<label class="font-semibold">

Tratamiento

</label>

<textarea

name="tratamiento"

rows="3"

class="w-full border rounded-lg p-2">

</textarea>

</div>

<div class="col-span-2">

<label class="font-semibold">

Observaciones

</label>

<textarea

name="observaciones"

rows="3"

class="w-full border rounded-lg p-2">

</textarea>

</div>

</div>

<div class="flex justify-end gap-3 mt-8">

<a

href="{{ route('historial-medico.index') }}"

class="bg-gray-500 text-white px-5 py-2 rounded-lg">

Cancelar

</a>

<button
    type="submit"
    class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg">

    💾 Actualizar Registro

</button>

</div>

</form>

</div>

@endsection
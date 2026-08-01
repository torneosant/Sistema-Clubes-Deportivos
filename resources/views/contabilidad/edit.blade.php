@extends('layouts.app')

    @section('titulo')
    💰 Nuevo Movimiento
    @endsection

    @section('contenido')

    <div class="max-w-5xl mx-auto">

    <div class="bg-white rounded-xl shadow p-6">

    <h2 class="text-2xl font-bold mb-6">
    Editar Movimiento Contable
    </h2>

<form method="POST" action="{{ route('contabilidad.update',$contabilidad) }}">
    @csrf
    @method('PUT')
    <div class="grid grid-cols-2 gap-5">

    <div>
    <label class="font-semibold">Fecha</label>

   <input
type="date"
name="fecha"
value="{{ old('fecha',$contabilidad->fecha) }}"
class="w-full border rounded-lg p-2"
required>
    </div>

    <div>
    <label class="font-semibold">Tipo</label>

   <select
name="tipo"
class="w-full border rounded-lg p-2">

    <option value="Ingreso"
        {{ old('tipo',$contabilidad->tipo)=='Ingreso' ? 'selected' : '' }}>
        Ingreso
    </option>

    <option value="Egreso"
        {{ old('tipo',$contabilidad->tipo)=='Egreso' ? 'selected' : '' }}>
        Egreso
    </option>

</select>

    </div>

    <div>

    <label class="font-semibold">
    Concepto
    </label>

    <select
    name="concepto_contable_id"
    id="concepto"
    class="w-full border rounded-lg p-2">

    @foreach($conceptos as $concepto)

<option
value="{{ $concepto->id }}"
data-jugador="{{ $concepto->requiere_jugador }}"
{{ old('concepto_contable_id',$contabilidad->concepto_contable_id)==$concepto->id ? 'selected' : '' }}>

{{ $concepto->nombre }}

</option>

@endforeach

    </select>

    </div>

    <div id="bloqueJugador">

    <label class="font-semibold">
    Jugador
    </label>

    <select
    name="jugador_id"
    class="w-full border rounded-lg p-2">

    <option value="">
    Seleccione...
    </option>

    @foreach($jugadores as $jugador)

<option
value="{{ $jugador->id }}"
{{ old('jugador_id',$contabilidad->jugador_id)==$jugador->id ? 'selected' : '' }}>

{{ $jugador->apellidos }}
{{ $jugador->nombres }}

</option>

@endforeach

    </select>

    </div>

    <div>

    <label class="font-semibold">
       Pagador / Beneficiario
    </label>

    <input
type="number"
name="valor"
value="{{ old('valor',$contabilidad->valor) }}"
min="1"
step="1"
required
class="w-full border rounded-lg p-2">

</div>
    <div>

    <label class="font-semibold">
    Valor
    </label>

    <input
        type="number"
        name="valor"
        min="1"
        step="1"
        required
        class="w-full border rounded-lg p-2">

    </div>

    @error('valor')
    <div class="text-red-600 text-sm">
        {{ $message }}
    </div>
    @enderror

    <div>

    <label class="font-semibold">
    Método Pago
    </label>

    <select
name="metodo_pago"
class="w-full border rounded-lg p-2">

<option value="Efectivo"
{{ old('metodo_pago',$contabilidad->metodo_pago)=='Efectivo' ? 'selected' : '' }}>
Efectivo
</option>

<option value="Transferencia"
{{ old('metodo_pago',$contabilidad->metodo_pago)=='Transferencia' ? 'selected' : '' }}>
Transferencia
</option>

<option value="Nequi"
{{ old('metodo_pago',$contabilidad->metodo_pago)=='Nequi' ? 'selected' : '' }}>
Nequi
</option>

<option value="Daviplata"
{{ old('metodo_pago',$contabilidad->metodo_pago)=='Daviplata' ? 'selected' : '' }}>
Daviplata
</option>

</select>

    </div>

    <div class="col-span-2">

    <label class="font-semibold">
    Observaciones
    </label>

    <textarea
name="observaciones"
rows="3"
class="w-full border rounded-lg p-2">{{ old('observaciones',$contabilidad->observaciones) }}</textarea>

    </div>

    </div>

    <div class="mt-6 flex gap-3">

    <button
    class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg">

    💾 Actualizar Movimiento

    </button>

    <a
    href="{{ route('contabilidad.index') }}"
    class="bg-gray-500 text-white px-6 py-2 rounded-lg">

    Cancelar

    </a>

    </div>

    </form>

    </div>

    </div>


    @endsection 
    @extends('layouts.app')

    @section('titulo')
    💰 Nuevo Movimiento
    @endsection

    @section('contenido')

    <div class="max-w-5xl mx-auto">

    <div class="bg-white rounded-xl shadow p-6">

    <h2 class="text-2xl font-bold mb-6">
    Nuevo Movimiento Contable
    </h2>
{{ $contabilidad->id }}

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
    class="w-full border rounded-lg p-2">
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
    data-jugador="{{ $concepto->requiere_jugador }}">

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

    <option value="{{ $jugador->id }}">

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
        type="text"
        name="tercero"
        class="w-full border rounded-lg p-2"
        placeholder="Ej: Ej: Madre del jugador, Nike,">

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

    <option>Efectivo</option>

    <option>Transferencia</option>

    <option>Nequi</option>

    <option>Daviplata</option>

    </select>

    </div>

    <div class="col-span-2">

    <label class="font-semibold">
    Observaciones
    </label>

    <textarea
    name="observaciones"
    rows="3"
    class="w-full border rounded-lg p-2"></textarea>

    </div>

    </div>

    <div class="mt-6 flex gap-3">

    <button
    class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg">

    💾 Guardar Movimiento

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
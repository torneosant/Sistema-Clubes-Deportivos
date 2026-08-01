@extends('layouts.app')

@section('titulo')
💰 Contabilidad
@endsection

@section('contenido')

<div class="grid grid-cols-4 gap-6 mb-8">

    <div class="bg-green-600 text-white rounded-xl p-6 shadow">
        <h3 class="text-lg">Ingresos</h3>
        <p class="text-3xl font-bold">
           ${{ number_format($ingresos,0,',','.') }}    
        </p>
    </div>

    <div class="bg-red-600 text-white rounded-xl p-6 shadow">
        <h3 class="text-lg">Gastos</h3>
        <p class="text-3xl font-bold">
            ${{ number_format($gastos,0,',','.') }}
        </p>
    </div>

    <div class="bg-blue-600 text-white rounded-xl p-6 shadow">
        <h3 class="text-lg">Saldo</h3>
        <p class="text-3xl font-bold">
           ${{ number_format($saldo,0,',','.') }}
        </p>
    </div>

    <div class="bg-yellow-500 text-white rounded-xl p-6 shadow">
        <h3 class="text-lg">Movimientos</h3>
        <p class="text-3xl font-bold">
            {{ $movimientos->count() }}
        </p>
    </div>

</div>



<form method="GET" class="bg-white rounded-xl shadow p-4 mb-5">

<div class="grid grid-cols-5 gap-4">

<div>
    
<label>Desde</label>

<input
type="date"
name="desde"
value="{{ request('desde') }}"
class="w-full border rounded-lg p-2">
</div>

<div>
<label>Hasta</label>

<input
type="date"
name="hasta"
value="{{ request('hasta') }}"
class="w-full border rounded-lg p-2">
</div>

<div>

<label>Tipo</label>

<select
name="tipo"
class="w-full border rounded-lg p-2">

<option value="">Todos</option>

<option value="Ingreso"
{{ request('tipo')=='Ingreso' ? 'selected' : '' }}>

Ingreso

</option>

<option value="Egreso"
{{ request('tipo')=='Egreso' ? 'selected' : '' }}>

Egreso

</option>

</select>

</div>

<div>

<label>Concepto</label>

<select
name="concepto"
class="w-full border rounded-lg p-2">

<option value="">Todos</option>

@foreach($conceptos as $concepto)

<option
value="{{ $concepto->id }}"
{{ request('concepto')==$concepto->id ? 'selected' : '' }}>

{{ $concepto->nombre }}

</option>

@endforeach

</select>

</div>

<div class="flex items-end gap-2">

<button
class="bg-blue-600 text-white px-5 py-2 rounded-lg">

Filtrar

</button>

<a
href="{{ route('contabilidad.index') }}"
class="bg-gray-500 text-white px-5 py-2 rounded-lg">

Limpiar

</a>

</div>

</div>

</form>

<div class="flex justify-end mb-6">

    <a href="{{ route('contabilidad.create') }}"
   class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg">

    ➕ Nuevo Movimiento

</a>

</div>

<div class="bg-white rounded-xl shadow">

<table class="min-w-full table-auto">

<thead class="bg-slate-800 text-white">
<tr>

<th class="px-3 py-3 text-center">Fecha</th>
<th class="px-3 py-3 text-center">Tipo</th>
<th class="px-3 py-3 text-center">Concepto</th>
<th class="px-3 py-3 text-center">Jugador</th>
<th class="px-3 py-3 text-center">Pagador</th>
<th class="px-3 py-3 text-center">Valor</th>
<th class="px-3 py-3 text-center">Método</th>
<th class="px-3 py-3 text-center">Observaciones</th>
<th class="px-3 py-3 text-center">Acciones</th>
</tr>
</thead>

<tbody>

@forelse($movimientos as $movimiento)

<tr class="border-b hover:bg-gray-50">

   <td class="px-3 py-2">
        {{ \Carbon\Carbon::parse($movimiento->fecha)->format('d/m/Y') }}
    </td>

    <td class="px-3 py-2">
        @if($movimiento->tipo=='Ingreso')
            <span class="bg-green-100 text-green-700 px-2 py-1 rounded">
                Ingreso
            </span>
        @else
            <span class="bg-red-100 text-red-700 px-2 py-1 rounded">
                Egreso
            </span>
        @endif
    </td>

    <td class="px-3 py-2">
        {{ $movimiento->concepto?->nombre }}
    </td>

    <td class="px-3 py-2">
        {{ $movimiento->jugador?->apellidos }}
        {{ $movimiento->jugador?->nombres }}
    </td>

    <td class="px-3 py-2">
        {{ $movimiento->tercero }}
    </td>

    <td class="px-3 py-2 text-right font-semibold">
        $ {{ number_format($movimiento->valor,0,',','.') }}
    </td>

    <td class="px-3 py-2">
        {{ $movimiento->metodo_pago }}
    </td>

    <td class="px-3 py-2">
        {{ $movimiento->observaciones }}
    </td>

    <td class="px-3 py-2 text-center whitespace-nowrap">

        <a href="{{ route('contabilidad.edit',$movimiento) }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded">

            ✏️

        </a>

        <form
            action="{{ route('contabilidad.destroy',$movimiento) }}"
            method="POST"
            class="inline">

            @csrf
            @method('DELETE')

           <button
type="button"
onclick="eliminarMovimiento(this.form)"
class="bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded">

🗑️

</button>

        </form>

    </td>

</tr>

@empty

<tr>

<td colspan="9" class="text-center p-8">

No existen movimientos registrados.

</td>

</tr>

@endforelse
</tbody>

</table>

</div>

<script>
function eliminarMovimiento(form){

    Swal.fire({

        title: '¿Eliminar movimiento?',

        text: 'Esta acción no se puede deshacer.',

        icon: 'warning',

        showCancelButton: true,

        confirmButtonColor: '#dc2626',

        cancelButtonColor: '#6b7280',

        confirmButtonText: 'Sí, eliminar',

        cancelButtonText: 'Cancelar'

    }).then((result)=>{

        if(result.isConfirmed){

            form.submit();

        }

    });

}
</script>

@endsection
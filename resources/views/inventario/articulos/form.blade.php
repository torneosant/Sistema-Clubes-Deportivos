@extends('layouts.app')

@section('titulo', $modo=='crear' ? 'Nuevo Artículo' : 'Editar Artículo')

@section('contenido')

<x-page-header
:title="$modo=='crear' ? '📦 Nuevo Artículo' : '✏ Editar Artículo'"
subtitle="Administra los implementos del club."/>

<x-card>

<form method="POST"
action="{{ $modo=='crear'
? route('inventario.store')
: route('inventario.update',$articulo) }}">

    @csrf

    @if($modo=='editar')
        @method('PUT')
    @endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">

    <div>
        <label class="font-semibold">Nombre</label>

        <input
        type="text"
        name="nombre"
        value="{{ old('nombre',$articulo->nombre) }}"
        class="w-full border rounded-xl p-3 mt-2"
        required>
    </div>

    <div>
        <label class="font-semibold">Código</label>

        <input
        type="text"
        name="codigo"
        value="{{ old('codigo',$articulo->codigo) }}"
        class="w-full border rounded-xl p-3 mt-2">
    </div>

    <div>
        <label class="font-semibold">Tipo</label>

        <select
        name="tipo_articulo_id"
        class="w-full border rounded-xl p-3 mt-2"
        required>

            <option value="">Seleccione...</option>

            @foreach($tipos as $tipo)

                <option
                value="{{ $tipo->id }}"
                {{ old('tipo_articulo_id',$articulo->tipo_articulo_id)==$tipo->id?'selected':'' }}>

                    {{ $tipo->nombre }}

                </option>

            @endforeach

        </select>

    </div>

    <div>
        <label class="font-semibold">Marca</label>

        <input
        type="text"
        name="marca"
        value="{{ old('marca',$articulo->marca) }}"
        class="w-full border rounded-xl p-3 mt-2">
    </div>

    <div>
        <label class="font-semibold">Cantidad</label>

        <input
        type="number"
        name="cantidad"
        value="{{ old('cantidad',$articulo->cantidad ?? 0) }}"
        class="w-full border rounded-xl p-3 mt-2"
        required>
    </div>

    <div>
        <label class="font-semibold">Estado</label>

        <input
        type="text"
        name="estado"
        value="{{ old('estado',$articulo->estado ?? 'Bueno') }}"
        class="w-full border rounded-xl p-3 mt-2">
    </div>

    <div>
        <label class="font-semibold">Ubicación</label>

        <input
        type="text"
        name="ubicacion"
        value="{{ old('ubicacion',$articulo->ubicacion) }}"
        class="w-full border rounded-xl p-3 mt-2">
    </div>

</div>

<div class="mt-5">

<label class="font-semibold">

Observaciones

</label>

<textarea
name="observaciones"
rows="4"
class="w-full border rounded-xl p-3 mt-2">{{ old('observaciones',$articulo->observaciones) }}</textarea>

</div>

<div class="mt-5">

<label class="inline-flex items-center gap-2">

<input
type="checkbox"
name="activo"
{{ old('activo',$articulo->activo ?? true)?'checked':'' }}>

Activo

</label>

</div>

<div class="mt-8 flex gap-3">

<button
class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl">

{{ $modo=='crear' ? 'Guardar' : 'Actualizar' }}

</button>

<a
href="{{ route('inventario.index') }}"
class="bg-gray-200 hover:bg-gray-300 px-6 py-3 rounded-xl">

Cancelar

</a>

</div>

</form>

</x-card>

@endsection
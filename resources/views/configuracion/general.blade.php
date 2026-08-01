@extends('layouts.app')

@section('contenido')

<div class="max-w-6xl mx-auto">

<form action="{{ route('configuracion.updateGeneral') }}" method="POST" enctype="multipart/form-data">

@csrf
@method('PUT')

<div class="bg-white rounded-xl shadow">

<div class="bg-slate-800 text-white px-6 py-4 rounded-t-xl">

<h2 class="text-2xl font-bold">
🏢 Configuración General
</h2>

</div>

<div class="p-6">

<div class="grid grid-cols-2 gap-6">

<div>
<label>Logo del Club</label>

<input type="file"
name="logo"
class="w-full border rounded-lg p-2 mt-2">

@if($configuracion->logo)

<img src="{{ asset('storage/'.$configuracion->logo) }}"
class="h-24 mt-3 rounded">

@endif

</div>

<div>
<label>Nombre del Club</label>

<input type="text"
name="nombre_club"
value="{{ old('nombre_club',$configuracion->nombre_club) }}"
class="w-full border rounded-lg p-3 mt-2">
</div>

<div>
<label>NIT</label>

<input type="text"
name="nit"
value="{{ old('nit',$configuracion->nit) }}"
class="w-full border rounded-lg p-3 mt-2">
</div>

<div>
<label>Teléfono</label>

<input type="text"
name="telefono"
value="{{ old('telefono',$configuracion->telefono) }}"
class="w-full border rounded-lg p-3 mt-2">
</div>

<div>
<label>WhatsApp</label>

<input type="text"
name="whatsapp"
value="{{ old('whatsapp',$configuracion->whatsapp) }}"
class="w-full border rounded-lg p-3 mt-2">
</div>

<div>
<label>Correo</label>

<input type="email"
name="correo"
value="{{ old('correo',$configuracion->correo) }}"
class="w-full border rounded-lg p-3 mt-2">
</div>

<div class="col-span-2">
<label>Dirección</label>

<input type="text"
name="direccion"
value="{{ old('direccion',$configuracion->direccion) }}"
class="w-full border rounded-lg p-3 mt-2">
</div>

<div>
<label>Ciudad</label>

<input type="text"
name="ciudad"
value="{{ old('ciudad',$configuracion->ciudad) }}"
class="w-full border rounded-lg p-3 mt-2">
</div>

<div>
<label>Departamento</label>

<input type="text"
name="departamento"
value="{{ old('departamento',$configuracion->departamento) }}"
class="w-full border rounded-lg p-3 mt-2">
</div>

<div>
<label>País</label>

<input type="text"
name="pais"
value="{{ old('pais',$configuracion->pais) }}"
class="w-full border rounded-lg p-3 mt-2">
</div>

<div>
<label>Página Web</label>

<input type="text"
name="pagina_web"
value="{{ old('pagina_web',$configuracion->pagina_web) }}"
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
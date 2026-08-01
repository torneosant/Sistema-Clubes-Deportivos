@extends('layouts.app')

@section('contenido')

<div class="max-w-7xl mx-auto">

<form action="{{ route('configuracion.update') }}" method="POST" enctype="multipart/form-data">

@csrf
@method('PUT')

<div class="bg-white rounded-2xl shadow">

<div class="bg-slate-800 text-white px-6 py-4 rounded-t-2xl">

<h2 class="text-2xl font-bold">
⚙️ Configuración del Club
</h2>

</div>

<div class="p-6">

<h3 class="text-xl font-bold mb-6 border-b pb-2">
Información General
</h3>

<div class="grid grid-cols-2 gap-6">

<div>
<label>Nombre del Club</label>

<input
type="text"
name="nombre_club"
value="{{ old('nombre_club',$configuracion->nombre_club) }}"
class="w-full mt-2 border rounded-lg p-3">
</div>

<div>
<label>NIT</label>

<input
type="text"
name="nit"
value="{{ old('nit',$configuracion->nit) }}"
class="w-full mt-2 border rounded-lg p-3">
</div>

<div>
<label>Dirección</label>

<input
type="text"
name="direccion"
value="{{ old('direccion',$configuracion->direccion) }}"
class="w-full mt-2 border rounded-lg p-3">
</div>

<div>
<label>Ciudad</label>

<input
type="text"
name="ciudad"
value="{{ old('ciudad',$configuracion->ciudad) }}"
class="w-full mt-2 border rounded-lg p-3">
</div>

<div>
<label>Departamento</label>

<input
type="text"
name="departamento"
value="{{ old('departamento',$configuracion->departamento) }}"
class="w-full mt-2 border rounded-lg p-3">
</div>

<div>
<label>País</label>

<input
type="text"
name="pais"
value="{{ old('pais',$configuracion->pais) }}"
class="w-full mt-2 border rounded-lg p-3">
</div>

<div>
<label>Teléfono</label>

<input
type="text"
name="telefono"
value="{{ old('telefono',$configuracion->telefono) }}"
class="w-full mt-2 border rounded-lg p-3">
</div>

<div>
<label>WhatsApp</label>

<input
type="text"
name="whatsapp"
value="{{ old('whatsapp',$configuracion->whatsapp) }}"
class="w-full mt-2 border rounded-lg p-3">
</div>

<div>
<label>Correo</label>

<input
type="email"
name="correo"
value="{{ old('correo',$configuracion->correo) }}"
class="w-full mt-2 border rounded-lg p-3">
</div>

<div>
<label>Página Web</label>

<input
type="text"
name="pagina_web"
value="{{ old('pagina_web',$configuracion->pagina_web) }}"
class="w-full mt-2 border rounded-lg p-3">
</div>

</div>

<hr class="my-8">

<h3 class="text-xl font-bold mb-6 border-b pb-2">
🖼️ Imagen Institucional
</h3>

<div class="grid grid-cols-2 gap-6">

    <div>

        <label class="font-semibold">Logo del Club</label>

        <input
            type="file"
            name="logo"
            class="w-full mt-2 border rounded-lg p-2">

    </div>

    <div>

        @if($configuracion->logo)

            <img
                src="{{ asset('storage/'.$configuracion->logo) }}"
                class="h-32 rounded-lg border">

        @endif

    </div>

</div>




<hr class="my-8">

<h3 class="text-xl font-bold mb-6 border-b pb-2">
🌐 Redes Sociales
</h3>

<div class="grid grid-cols-2 gap-6">

    <div>
        <label>Facebook</label>

        <input
        type="text"
        name="facebook"
        value="{{ old('facebook',$configuracion->facebook) }}"
        class="w-full mt-2 border rounded-lg p-3">
    </div>

    <div>
        <label>Instagram</label>

        <input
        type="text"
        name="instagram"
        value="{{ old('instagram',$configuracion->instagram) }}"
        class="w-full mt-2 border rounded-lg p-3">
    </div>

    <div>
        <label>TikTok</label>

        <input
        type="text"
        name="tiktok"
        value="{{ old('tiktok',$configuracion->tiktok) }}"
        class="w-full mt-2 border rounded-lg p-3">
    </div>

    <div>
        <label>YouTube</label>

        <input
        type="text"
        name="youtube"
        value="{{ old('youtube',$configuracion->youtube) }}"
        class="w-full mt-2 border rounded-lg p-3">
    </div>

</div>


<hr class="my-8">

<h3 class="text-xl font-bold mb-6 border-b pb-2">
⚽ Configuración Deportiva
</h3>

<div class="grid grid-cols-2 gap-6">

    <div>

        <label class="font-semibold">Temporada Activa</label>

        <input
            type="text"
            name="temporada"
            value="{{ old('temporada',$configuracion->temporada) }}"
            class="w-full mt-2 border rounded-lg p-3">

    </div>

    <div>

        <label class="font-semibold">Año Deportivo</label>

        <input
            type="text"
            name="anio"
            value="{{ old('anio',$configuracion->anio) }}"
            class="w-full mt-2 border rounded-lg p-3">

    </div>

</div>

<hr class="my-8">

<h3 class="text-xl font-bold mb-6 border-b pb-2">
⚙️ Sistema
</h3>

<div class="grid grid-cols-3 gap-6">

    <div>

        <label class="font-semibold">Moneda</label>

        <select
            name="moneda"
            class="w-full mt-2 border rounded-lg p-3">

            <option value="COP" {{ $configuracion->moneda=='COP' ? 'selected' : '' }}>COP</option>
            <option value="USD" {{ $configuracion->moneda=='USD' ? 'selected' : '' }}>USD</option>

        </select>

    </div>

    <div>

        <label class="font-semibold">Idioma</label>

        <select
            name="idioma"
            class="w-full mt-2 border rounded-lg p-3">

            <option value="Español" {{ $configuracion->idioma=='Español' ? 'selected' : '' }}>Español</option>

        </select>

    </div>

    <div>

        <label class="font-semibold">Zona Horaria</label>

        <select
            name="zona_horaria"
            class="w-full mt-2 border rounded-lg p-3">

            <option value="America/Bogota"
            {{ $configuracion->zona_horaria=='America/Bogota' ? 'selected' : '' }}>
                América/Bogotá
            </option>

        </select>

    </div>

</div>


<div class="mt-10 flex justify-end">



<button
class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl">

💾 Guardar Cambios

</button>


</div>



</div>

</div>

</form>

</div>

@endsection
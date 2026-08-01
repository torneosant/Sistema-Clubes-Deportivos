@extends('layouts.app')

@section('contenido')

<div class="max-w-5xl mx-auto">

<form action="{{ route('configuracion.updateRedes') }}" method="POST">

@csrf
@method('PUT')

<div class="bg-white rounded-xl shadow">

<div class="bg-slate-800 text-white px-6 py-4 rounded-t-xl">

<h2 class="text-2xl font-bold">
🌐 Redes Sociales
</h2>

</div>

<div class="p-6">

<div class="grid grid-cols-2 gap-6">

<div>

<label>Facebook</label>

<input
type="text"
name="facebook"
value="{{ old('facebook',$configuracion->facebook) }}"
class="w-full border rounded-lg p-3 mt-2">

</div>

<div>

<label>Instagram</label>

<input
type="text"
name="instagram"
value="{{ old('instagram',$configuracion->instagram) }}"
class="w-full border rounded-lg p-3 mt-2">

</div>

<div>

<label>TikTok</label>

<input
type="text"
name="tiktok"
value="{{ old('tiktok',$configuracion->tiktok) }}"
class="w-full border rounded-lg p-3 mt-2">

</div>

<div>

<label>YouTube</label>

<input
type="text"
name="youtube"
value="{{ old('youtube',$configuracion->youtube) }}"
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
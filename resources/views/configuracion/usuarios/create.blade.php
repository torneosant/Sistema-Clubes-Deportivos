@extends('layouts.app')

@section('contenido')

<div class="max-w-3xl mx-auto">

<div class="bg-white rounded-xl shadow">

<div class="bg-slate-800 text-white px-6 py-4 rounded-t-xl">

<h2 class="text-2xl font-bold">
Nuevo Usuario
</h2>

</div>

<form action="{{ route('usuarios.store') }}" method="POST">

    @csrf

    @include('configuracion.usuarios._form')

</form>

</div>

</div>

@endsection
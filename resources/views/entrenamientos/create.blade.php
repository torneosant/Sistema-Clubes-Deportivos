@extends('layouts.app')

@section('titulo','Nuevo Entrenamiento')

@section('contenido')

<div class="bg-white rounded-xl shadow p-8">

    <h2 class="text-2xl font-bold mb-6">

        ➕ Nuevo Entrenamiento

    </h2>

    <form
        action="{{ route('entrenamientos.store') }}"
        method="POST">

        @csrf

        @include('entrenamientos._form')

    </form>

</div>

@endsection 
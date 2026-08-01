@extends('layouts.app')

@section('titulo','Editar Entrenamiento')

@section('contenido')

<div class="bg-white rounded-xl shadow p-8">

    <h2 class="text-2xl font-bold mb-6">

        ✏️ Editar Entrenamiento

    </h2>

    <form
        action="{{ route('entrenamientos.update',$entrenamiento) }}"
        method="POST">

        @csrf
        @method('PUT')

        @include('entrenamientos._form')

    </form>

</div>

@endsection
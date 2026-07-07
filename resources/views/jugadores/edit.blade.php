@extends('layouts.app')

@section('titulo','Editar Jugador')

@section('contenido')

<form method="POST"
      action="{{ route('jugadores.update',$jugador) }}"
      enctype="multipart/form-data">
    @csrf
    @method('PUT')

    @include('jugadores._form')

    <div class="mt-8 flex justify-end">

        <button
            class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg">

            Actualizar jugador

        </button>

    </div>

</form>

@endsection
@extends('layouts.app')

@section('titulo','Nuevo Jugador')

@section('contenido')

<form method="POST"
      action="{{ route('jugadores.store') }}"
      enctype="multipart/form-data">

    @include('jugadores._form')

     <div class="mt-8 flex justify-end gap-3">
    <a href="{{ route('jugadores.index') }}"
       class="bg-gray-500 hover:bg-gray-600 text-white px-8 py-3 rounded-lg shadow">
        Cancelar
    </a>

        <button
            class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg">

            Guardar jugador

        </button>

    </div>

</form>

@endsection
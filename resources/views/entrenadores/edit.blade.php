@extends('layouts.app')

@section('titulo','Editar Entrenador')

@section('contenido')

<form method="POST"
      action="{{ route('entrenadores.update', $entrenador) }}"
      enctype="multipart/form-data">

    @csrf
    @method('PUT')

    @include('entrenadores._form')

    <div class="mt-8 flex justify-end">

        <button
            type="submit"
            class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg">

            Actualizar Entrenador

        </button>

    </div>

</form>

@endsection
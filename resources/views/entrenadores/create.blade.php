@extends('layouts.app')

@section('titulo','Nuevo Entrenador')

@section('contenido')

<form method="POST"
      action="{{ route('entrenadores.store') }}"
      enctype="multipart/form-data">

    @include('entrenadores._form')

<div class="mt-8 flex justify-end gap-3">

    <a href="{{ route('entrenadores.index') }}"
       class="bg-gray-500 hover:bg-gray-600 text-white px-8 py-3 rounded-lg shadow">

        Cancelar

    </a>

    <button
        type="submit"
        class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg shadow">

        {{ isset($entrenador) ? 'Actualizar Entrenador' : 'Guardar Entrenador' }}

    </button>

</div>
</form>

@endsection
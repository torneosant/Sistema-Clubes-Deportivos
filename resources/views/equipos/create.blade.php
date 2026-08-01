@extends('layouts.app')

@section('titulo','Nuevo Equipo')

@section('contenido')

<form
    method="POST"
    action="{{ route('equipos.store') }}"
    enctype="multipart/form-data">

    @csrf

    @include('equipos._form')

    <div class="mt-8 flex justify-end gap-3">
        <a href="{{ route('equipos.index') }}"
       class="bg-gray-500 hover:bg-gray-600 text-white px-8 py-3 rounded-lg shadow">
        Cancelar
    </a>

        <button
            class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg">

            Guardar Equipo

        </button>

    </div>

</form>

@endsection
@extends('layouts.app')

@section('titulo','Editar Equipo')

@section('contenido')

<form
    method="POST"
    action="{{ route('equipos.update',$equipo) }}"
    enctype="multipart/form-data">

    @csrf
    @method('PUT')

    @include('equipos._form')

    <div class="mt-8 flex justify-end">

        <button
            class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg">

            Guardar Cambios

        </button>

    </div>

</form>

@endsection
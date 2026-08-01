@extends('layouts.app')

@section('contenido')

<div class="max-w-4xl mx-auto">

    <div class="bg-white rounded-xl shadow">

        <div class="bg-slate-800 text-white px-6 py-4 rounded-t-xl">
            <h2 class="text-2xl font-bold">
                Editar Usuario
            </h2>
        </div>

        <form action="{{ route('usuarios.update', $usuario) }}" method="POST">

            @csrf
            @method('PUT')

            @include('configuracion.usuarios._form')

        </form>

    </div>

</div>

@endsection
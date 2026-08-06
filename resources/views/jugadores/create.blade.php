@extends('layouts.app')

@section('titulo','Nuevo Jugador')

@section('contenido')

{{-- =======================
    IMPORTACIÓN MASIVA
======================= --}}

<div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-8">

    <h2 class="text-xl font-bold text-blue-700 mb-2">
        📥 Importación Masiva de Jugadores
    </h2>

    <p class="text-sm text-gray-600 mb-5">
        Descargue la plantilla, diligencie la información y cargue el archivo Excel.
    </p>

    <div class="flex flex-wrap items-center gap-4">

        <a href="{{ route('jugadores.plantilla') }}"
           class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg">

            📄 Descargar Plantilla

        </a>

        <form action="{{ route('jugadores.importar') }}"
              method="POST"
              enctype="multipart/form-data"
              class="flex items-center gap-2">

            @csrf

            <input
                type="file"
                name="archivo"
                accept=".xlsx,.xls"
                required
                class="text-sm border rounded-lg p-2">

            <button
                type="submit"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">

                📥 Importar Excel

            </button>

        </form>

    </div>

</div>

{{-- =======================
    FORMULARIO INDIVIDUAL
======================= --}}

<form method="POST"
      action="{{ route('jugadores.store') }}"
      enctype="multipart/form-data">

    @csrf

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
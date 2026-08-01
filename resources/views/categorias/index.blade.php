@extends('layouts.app')

@section('titulo','Categorías')

@section('contenido')

@if(session('success'))
<div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
    {{ session('success') }}
</div>
@endif

<div class="mb-8">

    <h1 class="text-3xl font-bold text-slate-800">📂 Listado de Categorías

    </h2>
     <p class="text-gray-500 mt-2"> Administra las categorias de tu club.
    </p>

    
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-8">

    <div class="bg-white rounded-xl shadow p-5 border-l-4 border-blue-600">

        <p class="text-gray-500 text-sm">
         Total Categorías
         </p>

        <h2 class="text-3xl font-bold text-blue-600">

            {{ $totalCategorias }}

        </h2>

    </div>

    <div class="bg-white rounded-xl shadow p-5 border-l-4 border-blue-600">

        <p class="text-gray-500 text-sm">
            Categorías Activas
        </p>

        <h2 class="text-3xl font-bold text-green-600">

            {{ $totalActivas }}

        </h2>

    </div>
</div>    

<div class="flex flex-wrap gap-3 mb-6">

        <a href="{{ route('categorias.create') }}"
        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg shadow">

        ➕ Nuevo Categoria 

    </a>

</div>
<div class="bg-white rounded-lg shadow p-4 mb-4">

    <form method="GET" action="{{ route('categorias.index') }}">

        <div class="flex gap-3">

            <input
                type="text"
                name="buscar"
                value="{{ $buscar }}"
                placeholder="🔍 Buscar categoría..."
                class="flex-1 border rounded-lg px-4 py-2">

            <button
                type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 rounded-lg">

                Buscar

            </button>

            <a href="{{ route('categorias.index') }}"
               class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">

                Limpiar

            </a>

        </div>

    </form>

</div>
<div class="bg-white rounded-lg shadow">

    <table class="w-full">

        <thead class="bg-gray-100">

            <tr>

                <th class="p-3 text-left">Categoría</th>
                <th class="p-3 text-center">Acciones</th>

            </tr>

        </thead>

        <tbody>

        @forelse($categorias as $categoria)

            <tr class="border-t hover:bg-gray-50">

                <td class="p-3 font-semibold">

                    ⚽ {{ $categoria->nombre }}

                </td>

                <td class="p-3 text-center">

    <div class="flex justify-center gap-2">

        <a href="{{ route('categorias.edit', $categoria) }}"
           class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded">

            ✏️

        </a>

        <form action="{{ route('categorias.estado', $categoria) }}"
              method="POST"
              class="inline">

            @csrf
            @method('PATCH')

            <button
    type="button"
    onclick="confirmarEstado(this)"
    class="{{ $categoria->activo
        ? 'bg-green-600 hover:bg-green-700'
        : 'bg-gray-600 hover:bg-gray-700' }}
        text-white px-3 py-1 rounded">

    {{ $categoria->activo ? 'Activa' : 'Inactiva' }}

</button>
        </form>

        <form
            action="{{ route('categorias.destroy', $categoria) }}"
            method="POST"
            class="inline formulario-eliminar">

            @csrf
            @method('DELETE')

            <button
                type="submit"
                class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">

                🗑️

            </button>

        </form>

    </div>

</td>

            </tr>

        @empty

            <tr>

                <td colspan="3" class="text-center p-6 text-gray-500">

                    No hay categorías registradas.

                </td>

            </tr>

        @endforelse

        </tbody>

    </table>

</div>

<div class="mt-6">

    {{ $categorias->links() }}

</div>

@endsection

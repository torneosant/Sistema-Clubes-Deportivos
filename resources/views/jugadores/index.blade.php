@extends('layouts.app')

@section('titulo', 'Jugadores')

@section('contenido')

@if(session('success'))
    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        {{ session('success') }}
    </div>
@endif


<div class="flex justify-between items-center mb-6">

    <h2 class="text-2xl font-bold">Listado de Jugadores</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 my-6">

    <div class="bg-white rounded-xl shadow p-5 border-l-4 border-blue-500">
        <p class="text-gray-500 text-sm">Total Jugadores</p>

        <h2 class="text-3xl font-bold text-blue-600">
            {{ $totalJugadores }}
        </h2>
    </div>

    <div class="bg-white rounded-xl shadow p-5 border-l-4 border-green-500">
        <p class="text-gray-500 text-sm">Jugadores Activos</p>

        <h2 class="text-3xl font-bold text-green-600">
            {{ $totalActivos }}
        </h2>
    </div>

    <div class="bg-white rounded-xl shadow p-5 border-l-4 border-purple-500">
        <p class="text-gray-500 text-sm">Categorías</p>

        <h2 class="text-3xl font-bold text-purple-600">
            {{ $totalCategorias }}
        </h2>
    </div>

</div>

</div>

    <a href="{{ route('jugadores.create') }}"
       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
        + Nuevo Jugador
    </a>

</div>
<div class="bg-white rounded-lg shadow p-4 mb-4">

    <form method="GET" action="{{ route('jugadores.index') }}"> 

        <div class="flex gap-3">

            <input
                type="text"
                name="buscar"
                value="{{ $buscar }}"
                placeholder="🔍 Buscar por nombre, documento o teléfono..."
                class="flex-1 border rounded-lg px-4 py-2">

            <button
            type="submit"    
            class="bg-blue-600 hover:bg-blue-700 text-white px-6 rounded-lg">
                
                Buscar

            </button>

            <a href="{{ route('jugadores.index') }}"
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
<tr>

    <th class="p-3 text-center">Foto</th>
    <th class="p-3 text-left">Jugador</th>
    <th class="p-3 text-left">Documento</th>
    <th class="p-3 text-left">Nacimiento</th>
    <th class="p-3 text-left">Edad</th>
    <th class="p-3 text-left">Categoría</th>
    <th class="p-3 text-left">Equipo</th>
    <th class="p-3 text-center">Estado</th>
    <th class="p-3 text-center">Acciones</th>

</tr>

            </tr>

        </thead>

        <tbody>


        @forelse($jugadores as $jugador)

<tr class="border-t hover:bg-gray-50">

    <!-- FOTO -->
    <td class="p-3 text-center">

@if($jugador->foto)
    <img
        src="{{ asset('storage/'.$jugador->foto) }}"
        class="w-12 h-12 rounded-full object-cover border-2 border-gray-200 shadow">
@else
    <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center">
        👤
    </div>
@endif
    </td>

    <!-- JUGADOR -->
   <td class="p-3">
    <div class="font-bold">
        {{ $jugador->nombres }} {{ $jugador->apellidos }}
    </div>

    <div class="text-xs text-gray-500 mt-1">
    📞 {{ $jugador->telefono ?: 'Sin teléfono' }}
</div>
</td>

<td class="p-3">
    @if($jugador->numero_documento)
        <span class="font-mono text-blue-700">
            {{ $jugador->numero_documento }}
        </span>
    @else
        <span class="text-gray-400 italic">
            Sin documento
        </span>
    @endif
</td>

<td class="p-3">
    {{ $jugador->fecha_nacimiento ? $jugador->fecha_nacimiento->format('d/m/Y') : '-' }}
</td>

<td class="p-3">
    {{ $jugador->fecha_nacimiento ? $jugador->fecha_nacimiento->age.' años' : '-' }}
</td>

<td class="p-3">
    {{ $jugador->categoria }}
</td>

<td class="p-3">
    {{ $jugador->equipo }}
</td>

    <!-- ESTADO -->
    <td class="p-3 text-center">

    <form action="{{ route('jugadores.estado', $jugador) }}"
          method="POST"
          class="inline">

        @csrf
        @method('PATCH')

        <button
            type="button"
            onclick="confirmarEstado(this, {{ $jugador->activo ? 'true' : 'false' }})"
            class="px-3 py-1 rounded-full text-sm font-semibold
            {{ $jugador->activo
                ? 'bg-green-100 text-green-700 hover:bg-green-200'
                : 'bg-red-100 text-red-700 hover:bg-red-200' }}">

            {{ $jugador->activo ? '🟢 Activo' : '🔴 Inactivo' }}

        </button>

    </form>

</td>

    <!-- ACCIONES -->
    <td class="p-3 text-center">

        <a href="{{ route('jugadores.edit', $jugador) }}"
           class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded mr-2">
            ✏️
        </a>

       <form action="{{ route('jugadores.destroy', $jugador) }}"
      method="POST"
      class="inline formulario-eliminar">

    @csrf
    @method('DELETE')

    <button
    type="button"
    onclick="confirmarEliminar(this)"
    class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">

    🗑️

</button>

</form>

    </td>

</tr>

@empty

<tr>

    <td colspan="9" class="p-6 text-center text-gray-500">
        No hay jugadores registrados.
    </td>

</tr>

@endforelse
        </tbody>

    </table>

</div>

<div class="mt-6">

    {{ $jugadores->links() }}

</div>

@endsection
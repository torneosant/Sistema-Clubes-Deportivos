@extends('layouts.app')

@section('titulo','Equipos')

@section('contenido')

@if(session('success'))
<div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
    {{ session('success') }}
</div>
@endif

<div class="flex justify-between items-center mb-6">

    <h2 class="text-2xl font-bold">Listado de Equipos</h2>

    <a href="{{ route('equipos.create') }}"
       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">

        + Nuevo Equipo

    </a>

</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">

    <div class="bg-white rounded-xl shadow p-5 border-l-4 border-blue-500">

        <p class="text-gray-500 text-sm">
            Total Equipos
        </p>

        <h2 class="text-3xl font-bold text-blue-600">
            {{ $totalEquipos }}
        </h2>

    </div>

    <div class="bg-white rounded-xl shadow p-5 border-l-4 border-green-500">

        <p class="text-gray-500 text-sm">
            Equipos Activos
        </p>

        <h2 class="text-3xl font-bold text-green-600">
            {{ $totalActivos }}
        </h2>

    </div>

</div>

<div class="bg-white rounded-lg shadow p-4 mb-4">

<form method="GET" action="{{ route('equipos.index') }}">

<div class="flex gap-3">

<input
type="text"
name="buscar"
value="{{ $buscar }}"
placeholder="Buscar equipo..."
class="flex-1 border rounded-lg px-4 py-2">

<button
class="bg-blue-600 hover:bg-blue-700 text-white px-6 rounded-lg">

Buscar

</button>

<a
href="{{ route('equipos.index') }}"
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

<th class="p-3 text-center">
Escudo
</th>

<th class="p-3 text-left">
Equipo
</th>

<th class="p-3 text-left">
Categoría
</th>

<th class="p-3 text-left">
Colores
</th>

<th class="p-3 text-center">
Acciones
</th>

</tr>

</thead>

<tbody>

@forelse($equipos as $equipo)

<tr class="border-t hover:bg-gray-50">

<td class="p-3 text-center">

@if($equipo->escudo)

<img
src="{{ asset('storage/'.$equipo->escudo) }}"
class="w-12 h-12 rounded-full object-cover border shadow">

@else

<div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center">

⚽

</div>

@endif

</td>

<td class="p-3">

<div class="font-bold">

{{ $equipo->nombre }}

</div>

</td>

<td class="p-3">

{{ $equipo->categoria->nombre ?? '-' }}

</td>

<td class="p-3">

{{ $equipo->color_principal }}

@if($equipo->color_secundario)

/

{{ $equipo->color_secundario }}

@endif

</td>

<td class="p-3 text-center">

<div class="flex justify-center gap-2">

<a
href="{{ route('equipos.edit',$equipo) }}"
class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded">

✏️

</a>

    <form
    action="{{ route('equipos.estado',$equipo) }}"
    method="POST">

    @csrf
    @method('PATCH')

    <button
type="button"
onclick="return confirmarEstado(this, {{ $equipo->activo ? 'true' : 'false' }})"
class="{{ $equipo->activo
? 'bg-green-600 hover:bg-green-700'
: 'bg-gray-600 hover:bg-gray-700' }}
text-white px-3 py-1 rounded">

{{ $equipo->activo ? 'Activo' : 'Inactivo' }}

</button>

    </form>

<form
action="{{ route('equipos.destroy',$equipo) }}"
method="POST"
class="formulario-eliminar">

@csrf
@method('DELETE')

<button
class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">

🗑️

</button>

</form>

</div>

</td>

</tr>

@empty

<tr>

<td colspan="5"
class="text-center p-6 text-gray-500">

No hay equipos registrados.

</td>

</tr>

@endforelse

</tbody>

</table>

</div>

<div class="mt-6">

{{ $equipos->links() }}

</div>

@endsection
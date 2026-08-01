@extends('layouts.app')

@section('titulo', 'Gestión de Jugadores')

@section('contenido')

<div class="mb-8">

    <h1 class="text-3xl font-bold text-slate-800">
        👥 Gestión de Jugadores
    </h1>

    <p class="text-gray-500 mt-2">
        Administra todos los jugadores registrados en tu club.
    </p>

</div>

@if(session('success'))
<div class="mb-6 rounded-lg bg-green-100 border border-green-300 text-green-700 px-4 py-3">
    {{ session('success') }}
</div>
@endif


<div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-8">

    <div class="bg-white rounded-xl shadow p-5 border-l-4 border-blue-600">

        <p class="text-gray-500 text-sm">
            Total Jugadores
        </p>

        <h2 class="text-4xl font-bold text-blue-600 mt-2">
            {{ $totalJugadores }}
        </h2>

    </div>


    <div class="bg-white rounded-xl shadow p-5 border-l-4 border-green-600">

        <p class="text-gray-500 text-sm">
            Activos
        </p>

        <h2 class="text-4xl font-bold text-green-600 mt-2">
            {{ $totalActivos }}
        </h2>

    </div>


    <div class="bg-white rounded-xl shadow p-5 border-l-4 border-red-500">

        <p class="text-gray-500 text-sm">
            Inactivos
        </p>

        <h2 class="text-4xl font-bold text-red-500 mt-2">

            {{ $totalJugadores - $totalActivos }}

        </h2>

    </div>


    <div class="bg-white rounded-xl shadow p-5 border-l-4 border-purple-600">

        <p class="text-gray-500 text-sm">
            Categorías
        </p>

        <h2 class="text-4xl font-bold text-purple-600 mt-2">

            {{ $totalCategorias }}

        </h2>

    </div>

</div>


<div class="flex flex-wrap gap-3 mb-6">

    <a href="{{ route('jugadores.create') }}"
        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg shadow">

        ➕ Nuevo Jugador

    </a>


    <a href="{{ route('jugadores.exportExcel') }}"
        class="bg-  green-600 hover:bg-green-700 text-white font-semibold px-6 py-3 rounded-lg shadow">

        📊 Exportar Excel

    </a>

<a href="{{ route('jugadores.pdf') }}"
   class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-3 rounded-lg shadow">

    📄 Exportar PDF

</a>

<a href="{{ route('jugadores.print') }}"
   target="_blank"
   class="bg-gray-700 hover:bg-gray-800 text-white font-semibold px-6 py-3 rounded-lg shadow">

    🖨️ Imprimir

</a>

</div>
<div class="bg-white rounded-xl shadow p-6 mb-8">

    <form method="GET" action="{{ route('jugadores.index') }}">

        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">

            {{-- Buscar --}}
            <div class="md:col-span-2">

                <input
                    type="text"
                    name="buscar"
                    value="{{ $buscar }}"
                    placeholder="🔍 Buscar por nombre, documento o teléfono..."
                    class="w-full border rounded-lg px-4 py-3">

            </div>

            {{-- Categoría --}}
            <div>

                <select
                    name="categoria"
                    class="w-full border rounded-lg px-4 py-3">

                    <option value="">Todas las categorías</option>

                    @foreach($categorias as $cat)

                        <option
                            value="{{ $cat->id }}"
                            {{ $categoria == $cat->id ? 'selected' : '' }}>

                            {{ $cat->nombre }}

                        </option>

                    @endforeach

                </select>

            </div>

            {{-- Equipo --}}
            <div>

                <select
                    name="equipo"
                    class="w-full border rounded-lg px-4 py-3">

                    <option value="">Todos los equipos</option>

                    @foreach($equipos as $eq)

                        <option
                            value="{{ $eq->id }}"
                            {{ $equipo == $eq->id ? 'selected' : '' }}>

                            {{ $eq->nombre }}

                        </option>

                    @endforeach

                </select>

            </div>

            {{-- Estado --}}
            <div>

                <select
                    name="estado"
                    class="w-full border rounded-lg px-4 py-3">

                    <option value="">Todos</option>
                    <option value="1" {{ $estado == '1' ? 'selected' : '' }}>Activos</option>
                    <option value="0" {{ $estado == '0' ? 'selected' : '' }}>Inactivos</option>

                </select>

            </div>

        </div>

        <div class="flex gap-3 mt-5">

            <button
                type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">

                🔍 Buscar

            </button>

            <a
                href="{{ route('jugadores.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg">

                Limpiar

            </a>

        </div>

    </form>

</div>
<div class="bg-white rounded-xl shadow overflow-hidden">

    <table class="w-full">

        <thead class="bg-slate-800 text-white">

            <tr>

                <th class="px-4 py-3 text-left">Foto</th>
                <th class="px-4 py-3 text-left">Jugador</th>
                <th class="px-4 py-3 text-center">Documento</th>
                <th class="px-4 py-3 text-center">Edad</th>
                <th class="px-4 py-3 text-center">Categoría</th>
                <th class="px-4 py-3 text-center">Equipo</th>
                <th class="px-4 py-3 text-center">Estado</th>
                <th class="px-4 py-3 text-center">Acciones</th>

            </tr>

        </thead>

        <tbody>

        @forelse($jugadores as $jugador)

            <tr class="border-b hover:bg-gray-50">

                <td class="px-4 py-3">

                    @if($jugador->foto)

                        <img
                            src="{{ asset('storage/'.$jugador->foto) }}"
                            class="w-12 h-12 rounded-full object-cover">

                    @else

                        <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center text-xl">

                            👤

                        </div>

                    @endif

                </td>

                <td class="px-4 py-3">

                    <div class="font-semibold">

                        {{ $jugador->nombres }} {{ $jugador->apellidos }}

                    </div>

                    <div class="text-sm text-gray-500">

                        📞 {{ $jugador->telefono ?? 'Sin teléfono' }}

                    </div>

                </td>

                <td class="text-center">

                    {{ $jugador->numero_documento }}

                </td>

                <td class="text-center">

                    {{ $jugador->fecha_nacimiento ? \Carbon\Carbon::parse($jugador->fecha_nacimiento)->age.' años' : '-' }}

                </td>

                <td class="text-center">

                    {{ $jugador->categoria->nombre ?? '-' }}

                </td>

                <td class="text-center">

                    {{ $jugador->equipo->nombre ?? '-' }}

                </td>

                <td class="text-center">

    <form action="{{ route('jugadores.estado', $jugador) }}"
          method="POST"
          class="inline">

        @csrf
        @method('PATCH')

        @if($jugador->activo)

            <button
                type="submit"
                class="bg-green-100 hover:bg-green-200 text-green-700 px-3 py-1 rounded-full text-sm font-semibold transition">

                🟢 Activo

            </button>

        @else

            <button
                type="submit"
                class="bg-red-100 hover:bg-red-200 text-red-700 px-3 py-1 rounded-full text-sm font-semibold transition">

                🔴 Inactivo

            </button>

        @endif

    </form>

</td>   

                <td class="text-center">
                                        <div class="flex justify-center gap-2">

                        <a href="{{ route('jugadores.show', $jugador) }}"
                           class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg"
                           title="Ver">
                            👁️
                        </a>

                        <a href="{{ route('jugadores.edit', $jugador) }}"
                           class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded-lg"
                           title="Editar">
                            ✏️
                        </a>

                        <form action="{{ route('jugadores.destroy', $jugador) }}"
                              method="POST"
                              class="inline formulario-eliminar">

                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg"
                                title="Eliminar">

                                🗑️

                            </button>

                        </form>

                    </div>

                </td>

            </tr>

        @empty

            <tr>

                <td colspan="8" class="text-center py-10 text-gray-500">

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
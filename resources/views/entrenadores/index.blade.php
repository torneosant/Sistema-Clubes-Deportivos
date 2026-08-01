@extends('layouts.app')

@section('titulo', 'Gestión de Entrenadores')

@section('contenido')

<div class="mb-8">

   <h1 class="text-3xl font-bold text-slate-800">
    🧑‍🏫 Gestión de Entrenadores
</h1>

<p class="text-gray-500 mt-2">
    Administra todos los entrenadores registrados en tu club.
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
           Total Entrenadores   
        </p>

        <h2 class="text-4xl font-bold text-blue-600 mt-2">
            {{ $totalEntrenadores }}
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

           {{ $totalEntrenadores - $totalActivos }}

        </h2>

    </div>

</div>


<div class="flex flex-wrap gap-3 mb-6">

    <a href="{{ route('entrenadores.create') }}"
        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg shadow">

        ➕ Nuevo Entrenador

    </a>


    <a href="{{ route('entrenadores.exportExcel') }}"
        class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-3 rounded-lg shadow">

        📊 Exportar Excel

    </a>

<a href="{{ route('entrenadores.pdf') }}"
   class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-3 rounded-lg shadow">

    📄 Exportar PDF

</a>

<a href="{{ route('entrenadores.print') }}"
   target="_blank"
   class="bg-gray-700 hover:bg-gray-800 text-white font-semibold px-6 py-3 rounded-lg shadow">

    🖨️ Imprimir

</a>

</div>
<div class="bg-white rounded-xl shadow p-6 mb-8">

    <form method="GET" action="{{ route('entrenadores.index') }}">

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
                href="{{ route('entrenadores.index') }}"
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
                <th class="px-4 py-3 text-left">Entrenador</th>
                <th class="px-4 py-3 text-left">Cargo</th>
                <th class="px-4 py-3 text-center">Documento</th>
                <th class="px-4 py-3 text-center">Edad</th>
                <th class="px-4 py-3 text-center">Categoría</th>
                <th class="px-4 py-3 text-center">Equipo</th>
                <th class="px-4 py-3 text-center">Estado</th>
                <th class="px-4 py-3 text-center">Acciones</th>

            </tr>

        </thead>

        <tbody>

        @forelse($entrenadores as $entrenador)

            <tr class="border-b hover:bg-gray-50">

                <td class="px-4 py-3">

                    @if($entrenador->foto)

                        <img
                            src="{{ asset('storage/'.$entrenador->foto) }}"
                            class="w-12 h-12 rounded-full object-cover">

                    @else

                        <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center text-xl">

                            👤

                        </div>

                    @endif

                </td>

                <td class="px-4 py-3">

                    <div class="font-semibold">

                        {{ $entrenador->nombres }} {{ $entrenador->apellidos }}

                    </div>
                    <td class="text-center">
                      {{ $entrenador->cargo ?? '-' }}
                    </td>
                    <div class="text-sm text-gray-500">
{{--
                        📞 {{ $entrenador->telefono ?? 'Sin teléfono' }}
 --}}
                    </div>

                </td>

                <td class="text-center">

                    {{ $entrenador->numero_documento }}

                </td>

                <td class="text-center">

                    {{ $entrenador->fecha_nacimiento ? \Carbon\Carbon::parse($entrenador->fecha_nacimiento)->age.' años' : '-' }}

                </td>

              <td class="text-center">

    @forelse($entrenador->equipos as $equipo)

        <div class="mb-1">
            {{ $equipo->categoria->nombre ?? '-' }}
        </div>

    @empty

        -

    @endforelse

</td>
                <td class="text-center">

    @forelse($entrenador->equipos as $equipo)

        <div class="mb-1 font-medium">
            {{ $equipo->nombre }}
        </div>

    @empty

        -

    @endforelse

</td>
                <td class="text-center">

    <form action="{{ route('entrenadores.estado', $entrenador) }}"
          method="POST"
          class="inline">

        @csrf
        @method('PATCH')

        @if($entrenador->activo)

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

                        <a href="{{ route('entrenadores.show', $entrenador) }}"
                           class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg"
                           title="Ver">
                            👁️
                        </a>

                        <a href="{{ route('entrenadores.edit', $entrenador) }}"
                           class="bg-yellow-500 hover:bg-yellow-600 text-white   px-3 py-2 rounded-lg"
                           title="Editar">
                            ✏️
                        </a>

                        <form action="{{ route('entrenadores.destroy', $entrenador) }}"
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

                    No hay entrenadores registrados.

                </td>

            </tr>

        @endforelse

        </tbody>

    </table>

</div>


<div class="mt-6">

    {{ $entrenadores->links() }}

</div>

@endsection
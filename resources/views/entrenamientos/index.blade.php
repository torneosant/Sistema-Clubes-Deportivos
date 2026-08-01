@extends('layouts.app')

@section('titulo','Entrenamientos')

@section('contenido')

<div class="mb-6">

    <h2 class="text-3xl font-bold text-gray-800">
        🏃 Listado de Entrenamientos
    </h2>

    <p class="text-gray-500 mt-1">
        Programa y administra los entrenamientos del club.
    </p>

</div>

<a href="{{ route('entrenamientos.create') }}"
   class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg shadow">

    ➕ Nuevo Entrenamiento

</a>
<div class="bg-white rounded-xl shadow overflow-hidden mt-6">

    <table class="w-full">

        <thead class="bg-gray-100">

            <tr>

                <th class="px-4 py-3 text-center">Fecha</th>

                <th class="px-4 py-3 text-center">Equipo</th>

                <th class="px-4 py-3 text-center">Categorías</th>

                <th class="px-4 py-3 text-center">Entrenador</th>

                <th class="px-4 py-3 text-center">Horario</th>

                <th class="px-4 py-3 text-center">Lugar</th>

                <th class="px-4 py-3 text-center">Estado</th>

                <th class="px-4 py-3 text-center">Acciones</th>

            </tr>

        </thead>

        <tbody>

        @forelse($entrenamientos as $entrenamiento)

            <tr class="border-b hover:bg-gray-50">

                <td class="text-center py-3">

                    {{ \Carbon\Carbon::parse($entrenamiento->fecha)->format('d/m/Y') }}

                </td>

                <td class="text-center">

                    {{ $entrenamiento->equipo->nombre }}

                </td>

                <td class="text-center">

    @foreach($entrenamiento->categorias as $categoria)

        <span class="inline-block bg-indigo-100 text-indigo-700 text-xs px-2 py-1 rounded-full m-1">

            {{ $categoria->nombre }}

        </span>

    @endforeach

</td>

                <td class="text-center">

                    {{ $entrenamiento->entrenador->nombres }}
                    {{ $entrenamiento->entrenador->apellidos }}

                </td>

                <td class="text-center">

                    {{ substr($entrenamiento->hora_inicio,0,5) }}

                    -

                    {{ substr($entrenamiento->hora_fin,0,5) }}

                </td>

                <td class="text-center">

                    {{ $entrenamiento->lugar }}

                </td>

      <td class="text-center">

    <form
        action="{{ route('entrenamientos.estado',$entrenamiento) }}"
        method="POST">

        @csrf
        @method('PATCH')

        <select
            name="estado"
            onchange="this.form.submit()"
            class="rounded-full px-4 py-2 text-sm font-semibold border">

            <option value="Programado"
                {{ $entrenamiento->estado=='Programado' ? 'selected' : '' }}>
                🟡 Programado
            </option>

            <option value="Realizado"
                {{ $entrenamiento->estado=='Realizado' ? 'selected' : '' }}>
                🟢 Realizado
            </option>

            <option value="Cancelado"
                {{ $entrenamiento->estado=='Cancelado' ? 'selected' : '' }}>
                🔴 Cancelado
            </option>

        </select>

    </form>

</td>

               <td class="text-center">

    <div class="flex justify-center gap-2">

        <a href="{{ route('entrenamientos.show',$entrenamiento) }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg">

            👁️

        </a>

        <a href="{{ route('entrenamientos.edit',$entrenamiento) }}"
           class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded-lg">

            ✏️

        </a>

        <form
            action="{{ route('entrenamientos.destroy',$entrenamiento) }}"
            method="POST"
            class="inline formulario-eliminar">

            @csrf
            @method('DELETE')

            <button
                class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg">

                🗑️

            </button>

        </form>
        <a href="{{ route('asistencias.create', $entrenamiento) }}"
   class="text-green-600 hover:text-green-800"
   title="Tomar asistencia">

    📋

</a>

    </div>

</td>
            </tr>

        @empty

            <tr>

                <td colspan="7" class="text-center py-8 text-gray-500">

                    No hay entrenamientos registrados.

                </td>

            </tr>

        @endforelse

        </tbody>

    </table>

</div>

@endsection
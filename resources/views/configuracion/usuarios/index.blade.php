    @extends('layouts.app')

    @section('contenido')

    <div class="flex justify-between mb-6">

        <h1 class="text-3xl font-bold">
            Usuarios
        </h1>

        <a href="{{ route('usuarios.create') }}"
        class="bg-blue-600 text-white px-5 py-3 rounded-lg">

            Nuevo Usuario

        </a>

    </div>

    <table class="w-full bg-white rounded-xl shadow">

        <thead class="bg-slate-800 text-white">

            <tr>
                <th class="p-3">Nombre</th>
                <th>Correo</th>
                <th>Rol</th>
<th>Estado</th>
<th>Acciones</th>
            </tr>

        </thead>

        <tbody>

        @foreach($usuarios as $usuario)

            <tr class="border-b">

                <td class="p-3">{{ $usuario->name }}</td>

                <td>{{ $usuario->email }}</td>

                <td>{{ $usuario->rol->nombre ?? '' }}</td>

                <td>

    @if($usuario->activo)

        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">
            Activo
        </span>

    @else

        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm">
            Inactivo
        </span>

    @endif

</td>

    <td class="flex gap-3 p-3">

    <a href="{{ route('usuarios.edit',$usuario->id) }}"
       class="text-blue-600 font-semibold">

        Editar

    </a>

@if(
    $usuario->id != auth()->id() &&
    ($usuario->rol->nombre ?? '') != 'Administrador'
)

<form
    action="{{ route('usuarios.destroy',$usuario->id) }}"
    method="POST"
    class="formulario-eliminar">

    @csrf
    @method('DELETE')

    <button
        type="submit"
        class="text-red-700 font-semibold">

        Eliminar

    </button>

</form>

@endif
    

    <form
        action="{{ route('usuarios.estado',$usuario->id) }}"
        method="POST">

        @csrf
        @method('PATCH')

        <button
    type="button"
    onclick="confirmarEstado(this)"
    class="{{ $usuario->activo ? 'text-red-600' : 'text-green-600' }} font-semibold">

    {{ $usuario->activo ? 'Desactivar' : 'Activar' }}

</button>

    </form>

</td>

            </tr>

        @endforeach

        </tbody>

    </table>

    @endsection
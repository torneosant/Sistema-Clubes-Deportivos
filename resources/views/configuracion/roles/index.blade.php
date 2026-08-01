@extends('layouts.app')

@section('contenido')

<div class="flex justify-between items-center mb-6">

    <div>

        <h1 class="text-3xl font-bold">

            Roles del Sistema

        </h1>

        <p class="text-gray-500 mt-1">

            Los roles son propios del sistema y únicamente puedes configurar sus permisos.

        </p>

    </div>

</div>

@if(session('success'))

<div class="mb-4 bg-green-100 border border-green-300 text-green-700 rounded-lg p-4">

    {{ session('success') }}

</div>

@endif

@if(session('error'))

<div class="mb-4 bg-red-100 border border-red-300 text-red-700 rounded-lg p-4">

    {{ session('error') }}

</div>

@endif

<table class="w-full bg-white rounded-xl shadow">

    <thead class="bg-slate-800 text-white">

        <tr>

            <th class="p-4 text-left">
                Rol
            </th>

            <th class="text-center">
                Usuarios
            </th>

            <th class="text-center">
                Permisos
            </th>

            <th class="text-center">
                Acción
            </th>

        </tr>

    </thead>

    <tbody>

    @foreach($roles as $rol)

        <tr class="border-b hover:bg-gray-50">

            <td class="p-4 font-semibold">

                {{ $rol->nombre }}

            </td>

            <td class="text-center">

                {{ $rol->usuarios->count() }}

            </td>

            <td class="text-center">

                {{ $rol->permisos->count() }}

            </td>

            <td class="text-center">

                <a
                    href="{{ route('roles.edit',$rol->id) }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">

                    ⚙ Configurar permisos

                </a>

            </td>

        </tr>

    @endforeach

    </tbody>

</table>

@endsection
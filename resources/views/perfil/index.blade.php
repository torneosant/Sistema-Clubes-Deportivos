@extends('layouts.app')

@section('titulo', 'Mi perfil')

@section('contenido')

<x-page-header
    title="👤 Mi perfil"
    subtitle="Información de tu cuenta en el sistema."
/>

<div class="max-w-3xl">

    <x-card>

        <div class="space-y-5">

            <div>
                <label class="block text-sm font-semibold text-gray-600">
                    Nombre
                </label>

                <div class="mt-1 text-lg text-slate-800">
                    {{ $usuario->name }}
                </div>
            </div>


            <div>
                <label class="block text-sm font-semibold text-gray-600">
                    Correo electrónico
                </label>

                <div class="mt-1 text-lg text-slate-800">
                    {{ $usuario->email }}
                </div>
            </div>


            <div>
                <label class="block text-sm font-semibold text-gray-600">
                    Rol
                </label>

                <div class="mt-1 text-lg text-slate-800">
                    {{ $usuario->rol?->nombre ?? 'Sin rol asignado' }}
                </div>
            </div>


            <div>
                <label class="block text-sm font-semibold text-gray-600">
                    Estado
                </label>

                <div class="mt-1">

                    @if($usuario->activo)
                        <span class="inline-flex px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm">
                            Activo
                        </span>
                    @else
                        <span class="inline-flex px-3 py-1 rounded-full bg-red-100 text-red-700 text-sm">
                            Inactivo
                        </span>
                    @endif

                </div>
            </div>


            <div class="pt-5 border-t">

                <a
                    href="{{ route('perfil.password') }}"
                    class="inline-flex items-center px-5 py-3 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition">

                    🔑 Cambiar contraseña

                </a>

            </div>

        </div>

    </x-card>

</div>

@endsection
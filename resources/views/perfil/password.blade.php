@extends('layouts.app')

@section('titulo', 'Cambiar contraseña')

@section('contenido')

<x-page-header
    title="🔑 Cambiar contraseña"
    subtitle="Actualiza la contraseña de tu cuenta."
/>

<div class="max-w-2xl">

    <x-card>

        <form
            method="POST"
            action="{{ route('perfil.password.update') }}"
            class="space-y-6">

            @csrf
            @method('PUT')


            {{-- Contraseña actual --}}

            <div>

                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Contraseña actual
                </label>

                <input
                    type="password"
                    name="password_actual"
                    required
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                >

                @error('password_actual')
                    <p class="mt-2 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- Nueva contraseña --}}

            <div>

                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Nueva contraseña
                </label>

                <input
                    type="password"
                    name="password"
                    required
                    minlength="8"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                >

                <p class="mt-2 text-xs text-gray-500">
                    Mínimo 8 caracteres.
                </p>

                @error('password')
                    <p class="mt-2 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- Confirmar contraseña --}}

            <div>

                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Confirmar nueva contraseña
                </label>

                <input
                    type="password"
                    name="password_confirmation"
                    required
                    minlength="8"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                >

            </div>


            {{-- Botones --}}

            <div class="flex gap-3 pt-4 border-t">

                <a
                    href="{{ route('perfil') }}"
                    class="px-5 py-3 rounded-lg bg-gray-500 text-white hover:bg-gray-600">

                    Cancelar

                </a>

                <button
                    type="submit"
                    class="px-5 py-3 rounded-lg bg-blue-600 text-white hover:bg-blue-700">

                    🔐 Cambiar contraseña

                </button>

            </div>

        </form>

    </x-card>

</div>

@endsection
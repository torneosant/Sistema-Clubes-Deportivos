@extends('layouts.public')

@section('titulo', 'Registrar Club')

@section('contenido')

<div class="max-w-4xl mx-auto">

    <x-page-header
        title="⚽ Registrar mi club"
        subtitle="Crea tu club y la cuenta del administrador."
    />

    @if ($errors->any())

        <div class="mb-6 bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-lg">

            <p class="font-semibold mb-2">
                Hay algunos errores:
            </p>

            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>

    @endif

    <form
        method="POST"
        action="{{ route('registro.club.store') }}"
        class="space-y-6"
    >

        @csrf

        {{-- ========================= --}}
        {{-- DATOS DEL CLUB --}}
        {{-- ========================= --}}

        <x-card>

            <h2 class="text-xl font-bold text-slate-800 mb-6">
                🏟️ Datos del club
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div class="md:col-span-2">

                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Nombre del club *
                    </label>

                    <input
                        type="text"
                        name="nombre_club"
                        value="{{ old('nombre_club') }}"
                        required
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Ej. Atlético Femenino Medellín"
                    >

                </div>

                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Correo del club
                    </label>

                    <input
                        type="email"
                        name="email_club"
                        value="{{ old('email_club') }}"
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        placeholder="club@correo.com"
                    >

                </div>

                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Teléfono
                    </label>

                    <input
                        type="text"
                        name="telefono"
                        value="{{ old('telefono') }}"
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        placeholder="300 000 0000"
                    >

                </div>

                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Ciudad
                    </label>

                    <input
                        type="text"
                        name="ciudad"
                        value="{{ old('ciudad') }}"
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Medellín"
                    >

                </div>

                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Departamento
                    </label>

                    <input
                        type="text"
                        name="departamento"
                        value="{{ old('departamento') }}"
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Antioquia"
                    >

                </div>

                <div class="md:col-span-2">

                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Dirección
                    </label>

                    <input
                        type="text"
                        name="direccion"
                        value="{{ old('direccion') }}"
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Dirección del club"
                    >

                </div>

            </div>

        </x-card>


        {{-- ========================= --}}
        {{-- ADMINISTRADOR --}}
        {{-- ========================= --}}

        <x-card>

            <h2 class="text-xl font-bold text-slate-800 mb-2">
                👤 Cuenta del administrador
            </h2>

            <p class="text-sm text-gray-500 mb-6">
                Esta será la cuenta con la que podrás ingresar y administrar el club.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div class="md:col-span-2">

                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Nombre del administrador *
                    </label>

                    <input
                        type="text"
                        name="nombre_admin"
                        value="{{ old('nombre_admin') }}"
                        required
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Nombre completo"
                    >

                </div>

                <div class="md:col-span-2">

                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Correo de acceso *
                    </label>

                    <input
                        type="email"
                        name="email_admin"
                        value="{{ old('email_admin') }}"
                        required
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        placeholder="administrador@correo.com"
                    >

                </div>

                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Contraseña *
                    </label>

                    <input
                        type="password"
                        name="password"
                        required
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Mínimo 8 caracteres"
                    >

                </div>

                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Confirmar contraseña *
                    </label>

                    <input
                        type="password"
                        name="password_confirmation"
                        required
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Repite la contraseña"
                    >

                </div>

            </div>

        </x-card>


        {{-- ========================= --}}
        {{-- BOTONES --}}
        {{-- ========================= --}}

        <div class="flex items-center justify-between">

            <a
                href="{{ route('login') }}"
                class="text-gray-600 hover:text-gray-900"
            >
                ← Ya tengo una cuenta
            </a>

            <button
                type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg shadow"
            >
                ⚽ Crear mi club
            </button>

        </div>

    </form>

</div>

@endsection
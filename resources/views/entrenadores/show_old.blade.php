@extends('layouts.app')

@section('titulo', 'Ficha del Jugador')

@section('contenido')

<div class="max-w-7xl mx-auto">

    {{-- CABECERA --}}
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">

        <div class="h-44 bg-gradient-to-r from-blue-700 via-slate-700 to-slate-900"></div>

        <div class="px-10 pb-10">

            <div class="-mt-24 flex flex-col items-center text-center">

                {{-- FOTO --}}
                @if($jugador->foto)

                    <img
                        src="{{ asset('storage/'.$jugador->foto) }}"
                        class="w-44 h-44 rounded-full border-8 border-white shadow-xl object-cover">

                @else

                    <div
                        class="w-44 h-44 rounded-full border-8 border-white shadow-xl
                               bg-slate-200 flex items-center justify-center">

                        <span class="text-6xl font-bold text-slate-600">

                            {{ strtoupper(substr($jugador->nombres,0,1)) }}
                            {{ strtoupper(substr($jugador->apellidos,0,1)) }}

                        </span>

                    </div>

                @endif

                {{-- NOMBRE --}}
                <h1 class="text-4xl font-bold text-slate-800 mt-6">

                    {{ $jugador->nombres }}
                    {{ $jugador->apellidos }}

                </h1>

                {{-- ETIQUETAS --}}
                <div class="flex flex-wrap justify-center gap-3 mt-5">

                    @if($jugador->activo)

                        <span class="bg-green-100 text-green-700 px-4 py-2 rounded-full font-semibold">

                            🟢 Activo

                        </span>

                    @else

                        <span class="bg-red-100 text-red-700 px-4 py-2 rounded-full font-semibold">

                            🔴 Inactivo

                        </span>

                    @endif

                    <span class="bg-blue-100 text-blue-700 px-4 py-2 rounded-full">

                        ⚽
                        {{ optional($jugador->categoria)->nombre ?? 'Sin categoría' }}

                    </span>

                    <span class="bg-purple-100 text-purple-700 px-4 py-2 rounded-full">

                        🛡️
                        {{ optional($jugador->equipo)->nombre ?? 'Sin equipo' }}

                    </span>

                    <span class="bg-gray-100 text-gray-700 px-4 py-2 rounded-full">

                        {{ $jugador->posicion ?? 'Sin posición' }}

                    </span>

                </div>

            </div>

        </div>

    </div>
{{-- TARJETAS --}}

<div class="grid lg:grid-cols-2 gap-6 mt-8">

    {{-- DATOS PERSONALES --}}
    <div class="bg-white rounded-2xl shadow-lg">

        <div class="bg-slate-800 text-white px-6 py-4 rounded-t-2xl">
            <h2 class="text-xl font-bold">👤 Datos personales</h2>
        </div>

        <div class="p-6 space-y-3">

            <div class="flex justify-between border-b pb-2">
                <strong>Documento</strong>
                <span>
                    {{ $jugador->tipo_documento }}
                    {{ $jugador->numero_documento }}
                </span>
            </div>

            <div class="flex justify-between border-b pb-2">
                <strong>Fecha nacimiento</strong>
                <span>
                    {{ optional($jugador->fecha_nacimiento)->format('d/m/Y') }}
                </span>
            </div>

            <div class="flex justify-between border-b pb-2">
                <strong>Género</strong>
                <span>{{ $jugador->genero }}</span>
            </div>

            <div class="flex justify-between border-b pb-2">
                <strong>Teléfono</strong>
                <span>{{ $jugador->telefono }}</span>
            </div>

            <div class="flex justify-between border-b pb-2">
                <strong>Correo</strong>
                <span>{{ $jugador->email }}</span>
            </div>

            <div class="flex justify-between">
                <strong>Ciudad</strong>
                <span>{{ $jugador->ciudad }}</span>
            </div>

        </div>

    </div>

    {{-- INFORMACIÓN DEPORTIVA --}}
    <div class="bg-white rounded-2xl shadow-lg">

        <div class="bg-green-700 text-white px-6 py-4 rounded-t-2xl">
            <h2 class="text-xl font-bold">⚽ Información deportiva</h2>
        </div>

        <div class="p-6 space-y-3">

            <div class="flex justify-between border-b pb-2">
                <strong>Categoría</strong>
                <span>{{ optional($jugador->categoria)->nombre }}</span>
            </div>

            <div class="flex justify-between border-b pb-2">
                <strong>Equipo</strong>
                <span>{{ optional($jugador->equipo)->nombre }}</span>
            </div>

            <div class="flex justify-between border-b pb-2">
                <strong>Posición</strong>
                <span>{{ $jugador->posicion }}</span>
            </div>

            <div class="flex justify-between border-b pb-2">
                <strong>Pierna hábil</strong>
                <span>{{ $jugador->pierna_habil }}</span>
            </div>

            <div class="flex justify-between border-b pb-2">
                <strong>Estatura</strong>
                <span>{{ $jugador->estatura }} m</span>
            </div>

            <div class="flex justify-between">
                <strong>Peso</strong>
                <span>{{ $jugador->peso }} kg</span>
            </div>

        </div>

    </div>

</div>
</div>

@endsection                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             </div>
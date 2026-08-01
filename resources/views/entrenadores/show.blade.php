@extends('layouts.app')

@section('titulo','Ficha del Entrenador')

@section('contenido')

<div class="max-w-5xl mx-auto">

    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">

        <div class="bg-slate-800 h-40"></div>

        <div class="px-8 pb-8">

            <div class="-mt-20 flex flex-col items-center">

                @if($entrenador->foto)
                    <img src="{{ asset('storage/'.$entrenador->foto) }}"
                         class="w-40 h-40 rounded-full border-8 border-white object-cover shadow-lg">
                @else
                    <div class="w-40 h-40 rounded-full border-8 border-white bg-gray-200 flex items-center justify-center text-5xl shadow-lg">
                        👤
                    </div>
                @endif

                <h1 class="text-3xl font-bold mt-4">
                    {{ $entrenador->nombres }} {{ $entrenador->apellidos }}
                </h1>

                <div class="mt-3">

                    @if($entrenador->activo)
                        <span class="bg-green-100 text-green-700 px-4 py-2 rounded-full">
                            🟢 Activo
                        </span>
                    @else
                        <span class="bg-red-100 text-red-700 px-4 py-2 rounded-full">
                            🔴 Inactivo
                        </span>
                    @endif

                </div>

            </div>

        </div>

    </div>


    <div class="grid md:grid-cols-2 gap-6 mt-8">

        <div class="bg-white rounded-xl shadow p-6">

            <h2 class="text-xl font-bold mb-5">
                Información personal
            </h2>

            <div class="space-y-3">

                <p><strong>Documento:</strong> {{ $entrenador->numero_documento }}</p>

                <p><strong>Fecha nacimiento:</strong>
                    {{ $entrenador->fecha_nacimiento }}
                </p>

                <p><strong>Teléfono:</strong>
                    {{ $entrenador->telefono }}
                </p>

                <p><strong>Email:</strong>
                    {{ $entrenador->email }}
                </p>

                <p><strong>Ciudad:</strong>
                    {{ $entrenador->ciudad }}
                </p>

                <p><strong>Dirección:</strong>
                    {{ $entrenador->direccion }}
                </p>

            </div>

        </div>


        <div class="bg-white rounded-xl shadow p-6">

            <h2 class="text-xl font-bold mb-5">
                Información deportiva
            </h2>

            <div class="space-y-3">

                <p><strong>Cargo:</strong>
                    {{ $entrenador->cargo }}
                </p>

                <p><strong>Licencia:</strong>
                    {{ $entrenador->licencia }}
                </p>

                <p><strong>Fecha ingreso:</strong>
                    {{ $entrenador->fecha_ingreso }}
                </p>

                <p><strong>Observaciones:</strong></p>

                <div class="bg-gray-100 rounded-lg p-3">
                    {{ $entrenador->observaciones ?: 'Sin observaciones' }}
                </div>

            </div>

        </div>

    </div>


    <div class="mt-8 flex justify-center gap-4">

        <a href="{{ route('entrenadores.edit',$entrenador) }}"
           class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-lg">

            ✏️ Editar

        </a>

        <a href="{{ route('entrenadores.index') }}"
           class="bg-gray-700 hover:bg-gray-800 text-white px-6 py-3 rounded-lg">

            ← Volver

        </a>    

    </div>

</div>

@endsection
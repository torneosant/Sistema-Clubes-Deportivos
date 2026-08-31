@extends('layouts.app')

@section('titulo', 'Editar evento')

@section('contenido')

<x-page-header
    title="✏️ Editar evento"
    subtitle="Modifica la información del evento."
/>


<x-card>

    <form
        action="{{ route('calendario.eventos.update', $evento) }}"
        method="POST"
    >

        @csrf
        @method('PUT')


        <div class="space-y-5">


            {{-- TÍTULO --}}

            <div>

                <label class="block text-sm font-semibold mb-2">
                    Título del evento
                </label>

                <input
                    type="text"
                    name="titulo"
                    value="{{ old('titulo', $evento->titulo) }}"
                    required
                    class="w-full rounded-lg border-gray-300
                           focus:border-blue-500
                           focus:ring-blue-500"
                >

            </div>


            {{-- TIPO --}}

            <div>

                <label class="block text-sm font-semibold mb-2">
                    Tipo de evento
                </label>

                <select
                    name="tipo"
                    class="w-full rounded-lg border-gray-300
                           focus:border-blue-500
                           focus:ring-blue-500"
                >

                    @foreach([
                        'General',
                        'Reunión',
                        'Actividad',
                        'Torneo',
                        'Entrega',
                        'Otro'
                    ] as $tipo)

                        <option
                            value="{{ $tipo }}"
                            @selected(
                                old('tipo', $evento->tipo) === $tipo
                            )
                        >
                            {{ $tipo }}
                        </option>

                    @endforeach

                </select>

            </div>


            {{-- DESCRIPCIÓN --}}

            <div>

                <label class="block text-sm font-semibold mb-2">
                    Descripción
                </label>

                <textarea
                    name="descripcion"
                    rows="4"
                    class="w-full rounded-lg border-gray-300
                           focus:border-blue-500
                           focus:ring-blue-500"
                >{{ old('descripcion', $evento->descripcion) }}</textarea>

            </div>


            {{-- BOTONES --}}

            <div class="flex justify-between pt-4">

                <a
                    href="{{ route('calendario.index') }}"
                    class="px-4 py-2 rounded-lg
                           bg-gray-200
                           hover:bg-gray-300
                           text-gray-700
                           font-semibold"
                >
                    ← Volver
                </a>


                <x-button
                    type="submit"
                    color="blue"
                >
                    💾 Guardar cambios
                </x-button>

            </div>

        </div>

    </form>

</x-card>

@endsection
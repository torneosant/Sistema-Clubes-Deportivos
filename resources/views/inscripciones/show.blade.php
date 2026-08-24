@extends('layouts.app')

@section('titulo', 'Detalle de Inscripción')

@section('contenido')

<x-page-header
    title="📋 Detalle de Inscripción"
    subtitle="Revisa la información enviada por la persona."
/>

<x-card>

    {{-- INFORMACIÓN DE LA INSCRIPCIÓN --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- NOMBRES --}}
        <div>
            <div class="text-sm text-gray-500">
                Nombres
            </div>

            <div class="font-semibold text-slate-800">
                {{ $inscripcion->nombres }}
            </div>
        </div>


        {{-- APELLIDOS --}}
        <div>
            <div class="text-sm text-gray-500">
                Apellidos
            </div>

            <div class="font-semibold text-slate-800">
                {{ $inscripcion->apellidos }}
            </div>
        </div>


        {{-- DOCUMENTO --}}
        <div>
            <div class="text-sm text-gray-500">
                Documento
            </div>

            <div class="font-semibold text-slate-800">
                {{ $inscripcion->documento ?: 'No registrado' }}
            </div>
        </div>


        {{-- FECHA DE NACIMIENTO --}}
        <div>
            <div class="text-sm text-gray-500">
                Fecha de nacimiento
            </div>

            <div class="font-semibold text-slate-800">
                {{ $inscripcion->fecha_nacimiento?->format('d/m/Y') ?? 'No registrada' }}
            </div>
        </div>


        {{-- TELÉFONO --}}
        <div>
            <div class="text-sm text-gray-500">
                Teléfono
            </div>

            <div class="font-semibold text-slate-800">
                {{ $inscripcion->telefono ?: 'No registrado' }}
            </div>
        </div>


        {{-- CORREO --}}
        <div>
            <div class="text-sm text-gray-500">
                Correo
            </div>

            <div class="font-semibold text-slate-800">
                {{ $inscripcion->email ?: 'No registrado' }}
            </div>
        </div>


        {{-- CATEGORÍA --}}
        <div>
            <div class="text-sm text-gray-500">
                Categoría
            </div>

            <div class="font-semibold text-slate-800">
                {{ $inscripcion->categoria->nombre ?? 'No registrada' }}
            </div>
        </div>


        {{-- POSICIÓN --}}
        <div>
            <div class="text-sm text-gray-500">
                Posición
            </div>

            <div class="font-semibold text-slate-800">
                {{ $inscripcion->posicion ?: 'No registrada' }}
            </div>
        </div>


        {{-- DIRECCIÓN --}}
        <div>
            <div class="text-sm text-gray-500">
                Dirección
            </div>

            <div class="font-semibold text-slate-800">
                {{ $inscripcion->direccion ?: 'No registrada' }}
            </div>
        </div>


        {{-- CLUB ANTERIOR --}}
        <div>
            <div class="text-sm text-gray-500">
                Club anterior
            </div>

            <div class="font-semibold text-slate-800">
                {{ $inscripcion->club_anterior ?: 'No registrado' }}
            </div>
        </div>

    </div>


    {{-- OBSERVACIONES --}}
    <div class="mt-6">

        <div class="text-sm text-gray-500">
            Observaciones
        </div>

        <div class="font-semibold text-slate-800">
            {{ $inscripcion->observaciones ?: 'Sin observaciones' }}
        </div>

    </div>


    <hr class="my-6">


    {{-- ESTADO --}}
    <div>

        <div class="text-sm text-gray-500 mb-2">
            Estado
        </div>

        @if($inscripcion->estado === 'Pendiente')

            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-700">
                🟡 Pendiente
            </span>

        @elseif($inscripcion->estado === 'Aceptada')

            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-700">
                🟢 Aceptada
            </span>

        @elseif($inscripcion->estado === 'Denegada')

            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-700">
                🔴 Denegada
            </span>

        @elseif($inscripcion->estado === 'Disponible')

            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-700">
                🔵 Disponible
            </span>

        @else

            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-gray-100 text-gray-700">
                {{ $inscripcion->estado }}
            </span>

        @endif

    </div>


    {{-- INFORMACIÓN DE REVISIÓN --}}
    @if($inscripcion->fecha_revision)

        <div class="mt-4 text-sm text-gray-600">

            Revisado el:

            <strong>
                {{ $inscripcion->fecha_revision->format('d/m/Y H:i') }}
            </strong>

            @if($inscripcion->revisor)

                por

                <strong>
                    {{ $inscripcion->revisor->name }}
                </strong>

            @endif

        </div>

    @endif


    {{-- MOTIVO DE DENEGACIÓN --}}
    @if(
        $inscripcion->estado === 'Denegada'
        && $inscripcion->motivo_denegacion
    )

        <div class="mt-5 rounded-lg bg-red-50 border border-red-200 p-4">

            <div class="font-semibold text-red-700 mb-1">
                Motivo de denegación
            </div>

            <div class="text-red-600">
                {{ $inscripcion->motivo_denegacion }}
            </div>

        </div>

    @endif


    {{-- ===================================================== --}}
    {{-- ACCIONES DE REVISIÓN --}}
    {{-- ===================================================== --}}

    @if($inscripcion->estado === 'Pendiente')

        <div class="mt-8 pt-6 border-t">

            <div class="font-semibold text-slate-800 mb-4">
                ⚙️ Revisar inscripción
            </div>


            <div class="flex flex-wrap gap-3">


                {{-- ========================================= --}}
                {{-- APROBAR --}}
                {{-- ========================================= --}}

                @if(auth()->user()->tienePermiso('inscripciones.aprobar'))

                    <form
                        action="{{ route('inscripciones.aceptar', $inscripcion) }}"
                        method="POST"
                        class="inline"
                    >

                        @csrf

                        <x-button
                            type="submit"
                            color="green"
                        >
                            ✅ Aprobar inscripción
                        </x-button>

                    </form>

                @endif


                {{-- ========================================= --}}
                {{-- DENEGAR --}}
                {{-- ========================================= --}}

                @if(auth()->user()->tienePermiso('inscripciones.denegar'))

                    <form
                        action="{{ route('inscripciones.denegar', $inscripcion) }}"
                        method="POST"
                        class="inline formulario-denegar"
                    >

                        @csrf

                        <input
                            type="hidden"
                            name="motivo_denegacion"
                            value=""
                        >

                        <x-button
                            type="submit"
                            color="red"
                        >
                            ❌ Denegar inscripción
                        </x-button>

                    </form>

                @endif


            </div>

        </div>

    @endif


    {{-- VOLVER --}}
    <div class="mt-8 flex justify-end">

        <a href="{{ route('inscripciones.index') }}">

            <x-button
                type="button"
                color="gray"
            >
                ← Volver
            </x-button>

        </a>

    </div>

</x-card>


{{-- ========================================================= --}}
{{-- CONFIRMAR DENEGACIÓN --}}
{{-- ========================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const formulario = document.querySelector('.formulario-denegar');

    if (!formulario) {
        return;
    }

    formulario.addEventListener('submit', function (e) {

        e.preventDefault();

        const motivo = prompt(
            'Indica el motivo de la denegación:'
        );

        if (motivo === null) {
            return;
        }

        formulario.querySelector(
            'input[name="motivo_denegacion"]'
        ).value = motivo;

        formulario.submit();

    });

});

</script>

@endsection
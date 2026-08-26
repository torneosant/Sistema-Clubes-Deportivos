@extends('layouts.app')

@section('titulo', 'Detalle de Inscripción')

@section('contenido')

<x-page-header
    title="📋 Detalle de Inscripción"
    subtitle="Revisa la información enviada por la persona antes de aprobar o denegar."
/>

<x-card>

    {{-- ===================================================== --}}
    {{-- INFORMACIÓN DEL JUGADOR --}}
    {{-- ===================================================== --}}

    <div class="mb-6">

        <h2 class="text-xl font-bold text-slate-800 mb-5">
            👤 Datos del jugador
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


            {{-- NOMBRES --}}

            <div>

                <div class="text-sm text-gray-500">
                    Nombres
                </div>

                <div class="font-semibold text-slate-800">
                    {{ $inscripcion->nombres ?: 'No registrado' }}
                </div>

            </div>


            {{-- APELLIDOS --}}

            <div>

                <div class="text-sm text-gray-500">
                    Apellidos
                </div>

                <div class="font-semibold text-slate-800">
                    {{ $inscripcion->apellidos ?: 'No registrado' }}
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


            {{-- FECHA NACIMIENTO --}}

            <div>

                <div class="text-sm text-gray-500">
                    Fecha de nacimiento
                </div>

                <div class="font-semibold text-slate-800">

                    {{ $inscripcion->fecha_nacimiento?->format('d/m/Y') ?? 'No registrada' }}

                </div>

            </div>


            {{-- TELEFONO --}}

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
                    Correo de acceso
                </div>

                <div class="font-semibold text-slate-800">
                    {{ $inscripcion->email ?: 'No registrado' }}
                </div>

            </div>


            {{-- CATEGORIA --}}

            <div>

                <div class="text-sm text-gray-500">
                    Categoría
                </div>

                <div class="font-semibold text-slate-800">
                    {{ $inscripcion->categoria->nombre ?? 'No registrada' }}
                </div>

            </div>


            {{-- DIRECCION --}}

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


            {{-- EPS --}}

            <div>

                <div class="text-sm text-gray-500">
                    EPS
                </div>

                <div class="font-semibold text-slate-800">
                    {{ $inscripcion->eps ?: 'No registrada' }}
                </div>

            </div>


            {{-- TIPO DE SANGRE / RH --}}

            <div>

                <div class="text-sm text-gray-500">
                    Tipo de sangre / RH
                </div>

                <div class="font-semibold text-slate-800">
                    {{ $inscripcion->rh ?: 'No registrado' }}
                </div>

            </div>

        </div>

    </div>


    {{-- ===================================================== --}}
    {{-- DATOS DEL ACUDIENTE --}}
    {{-- ===================================================== --}}

    @if(
        $inscripcion->acudiente ||
        $inscripcion->documento_acudiente ||
        $inscripcion->telefono_acudiente ||
        $inscripcion->email_acudiente ||
        $inscripcion->parentesco
    )

        <div class="mt-8 pt-6 border-t">

            <h2 class="text-xl font-bold text-slate-800 mb-5">
                👨‍👩‍👧 Datos del acudiente
            </h2>


            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


                {{-- NOMBRE --}}

                <div>

                    <div class="text-sm text-gray-500">
                        Nombre del acudiente
                    </div>

                    <div class="font-semibold text-slate-800">
                        {{ $inscripcion->acudiente ?: 'No registrado' }}
                    </div>

                </div>


                {{-- DOCUMENTO --}}

                <div>

                    <div class="text-sm text-gray-500">
                        Documento del acudiente
                    </div>

                    <div class="font-semibold text-slate-800">
                        {{ $inscripcion->documento_acudiente ?: 'No registrado' }}
                    </div>

                </div>


                {{-- TELEFONO --}}

                <div>

                    <div class="text-sm text-gray-500">
                        Teléfono del acudiente
                    </div>

                    <div class="font-semibold text-slate-800">
                        {{ $inscripcion->telefono_acudiente ?: 'No registrado' }}
                    </div>

                </div>


                {{-- CORREO --}}

                <div>

                    <div class="text-sm text-gray-500">
                        Correo del acudiente
                    </div>

                    <div class="font-semibold text-slate-800">
                        {{ $inscripcion->email_acudiente ?: 'No registrado' }}
                    </div>

                </div>


                {{-- PARENTESCO --}}

                <div>

                    <div class="text-sm text-gray-500">
                        Parentesco
                    </div>

                    <div class="font-semibold text-slate-800">
                        {{ $inscripcion->parentesco ?: 'No registrado' }}
                    </div>

                </div>

            </div>

        </div>

    @endif


    {{-- ===================================================== --}}
    {{-- OBSERVACIONES --}}
    {{-- ===================================================== --}}

    @if($inscripcion->observaciones)

        <div class="mt-8 pt-6 border-t">

            <div class="text-sm text-gray-500 mb-1">
                Observaciones
            </div>

            <div class="font-semibold text-slate-800">
                {{ $inscripcion->observaciones }}
            </div>

        </div>

    @endif


    {{-- ===================================================== --}}
    {{-- DOCUMENTOS ADJUNTOS --}}
    {{-- ===================================================== --}}

    <div class="mt-8 pt-6 border-t">

        <div class="flex items-center justify-between mb-5">

            <div>

                <h2 class="text-xl font-bold text-slate-800">
                    📎 Documentos adjuntos
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Revise los documentos antes de aprobar la inscripción.
                </p>

            </div>

        </div>


        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">


            {{-- ================================================= --}}
            {{-- FOTO --}}
            {{-- ================================================= --}}

            <div class="border rounded-xl p-5 bg-gray-50">

                <div class="flex items-center gap-3 mb-4">

                    <div class="text-3xl">
                        📷
                    </div>

                    <div>

                        <div class="font-bold text-slate-800">
                            Foto del jugador
                        </div>

                        <div class="text-sm text-gray-500">
                            Fotografía enviada en la inscripción.
                        </div>

                    </div>

                </div>


                @if($inscripcion->foto)

                    <div class="mb-4">

                        <img
                            src="{{ asset('storage/' . $inscripcion->foto) }}"
                            alt="Foto del jugador"
                            class="w-full max-h-80 object-contain rounded-lg border bg-white"
                        >

                    </div>


                    <a
                        href="{{ asset('storage/' . $inscripcion->foto) }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2 rounded-lg"
                    >

                        👁️ Ver foto

                    </a>

                @else

                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">

                        <p class="text-sm text-yellow-700">
                            No se adjuntó una foto.
                        </p>

                    </div>

                @endif

            </div>


            {{-- ================================================= --}}
            {{-- DOCUMENTO PDF --}}
            {{-- ================================================= --}}

            <div class="border rounded-xl p-5 bg-gray-50">

                <div class="flex items-center gap-3 mb-4">

                    <div class="text-3xl">
                        📄
                    </div>

                    <div>

                        <div class="font-bold text-slate-800">
                            Documento del jugador
                        </div>

                        <div class="text-sm text-gray-500">
                            Documento de identidad enviado en PDF.
                        </div>

                    </div>

                </div>


                @if($inscripcion->documento_pdf)

                    <div class="bg-white border rounded-lg p-4 mb-4">

                        <p class="text-sm text-gray-600">
                            El documento está disponible para revisión.
                        </p>

                    </div>


                    <a
                        href="{{ asset('storage/' . $inscripcion->documento_pdf) }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2 rounded-lg"
                    >

                        📄 Ver documento PDF

                    </a>

                @else

                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">

                        <p class="text-sm text-yellow-700">
                            No se adjuntó el documento.
                        </p>

                    </div>

                @endif

            </div>

        </div>

    </div>


    <hr class="my-8">


    {{-- ===================================================== --}}
    {{-- ESTADO --}}
    {{-- ===================================================== --}}

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


    {{-- ===================================================== --}}
    {{-- INFORMACIÓN DE REVISIÓN --}}
    {{-- ===================================================== --}}

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


    {{-- ===================================================== --}}
    {{-- MOTIVO DE DENEGACIÓN --}}
    {{-- ===================================================== --}}

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


                {{-- ================================================= --}}
                {{-- APROBAR --}}
                {{-- ================================================= --}}

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


                {{-- ================================================= --}}
                {{-- DENEGAR --}}
                {{-- ================================================= --}}

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
                            class="motivo-denegacion"
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


    {{-- ===================================================== --}}
    {{-- VOLVER --}}
    {{-- ===================================================== --}}

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
{{-- SWEETALERT PARA DENEGAR --}}
{{-- ========================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const formulario = document.querySelector(
        '.formulario-denegar'
    );

    if (!formulario) {
        return;
    }


    formulario.addEventListener('submit', function (e) {

        e.preventDefault();


        Swal.fire({

            title: '¿Denegar inscripción?',

            text: 'Esta acción cambiará el estado de la inscripción.',

            icon: 'warning',

            input: 'textarea',

            inputLabel: 'Motivo de la denegación',

            inputPlaceholder:
                'Escriba el motivo de la denegación...',

            inputAttributes: {
                maxlength: 1000
            },

            showCancelButton: true,

            confirmButtonColor: '#dc2626',

            cancelButtonColor: '#6b7280',

            confirmButtonText:
                'Sí, denegar',

            cancelButtonText:
                'Cancelar',

            inputValidator: (value) => {

                if (!value || !value.trim()) {

                    return 'Debe indicar el motivo de la denegación.';

                }

            }

        }).then((result) => {

            if (!result.isConfirmed) {
                return;
            }


            formulario.querySelector(
                '.motivo-denegacion'
            ).value = result.value.trim();


            formulario.submit();

        });

    });

});

</script>

@endsection
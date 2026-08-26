@extends('layouts.public')

@section('titulo', 'Solicitud de Inscripción')

@section('contenido')

<div class="max-w-4xl mx-auto">

    <x-page-header
        title="⚽ Solicitud de inscripción"
        subtitle="Complete la información del jugador para solicitar su inscripción."
    />

    {{-- ===================================================== --}}
    {{-- ERRORES --}}
    {{-- ===================================================== --}}

    @if ($errors->any())

        <x-card class="mb-6">

            <div class="bg-red-50 border border-red-200 rounded-lg p-4">

                <p class="font-semibold text-red-700 mb-2">
                    ⚠️ Hay algunos datos que debe revisar:
                </p>

                <ul class="list-disc list-inside text-sm text-red-600">

                    @foreach ($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        </x-card>

    @endif


    <form
        method="POST"
        action="{{ route('inscripcion.publica.store', $inscripcion->token) }}"
        enctype="multipart/form-data"
        id="formInscripcion"
    >

        @csrf


        {{-- ================================================= --}}
        {{-- CATEGORÍA --}}
        {{-- ================================================= --}}

        <x-card class="mb-6">

            <div class="flex items-center gap-3">

                <div class="text-3xl">
                    🏆
                </div>

                <div>

                    <div class="text-sm text-gray-500">
                        Categoría de inscripción
                    </div>

                    <div class="text-lg font-bold text-slate-800">

                        {{ $inscripcion->categoria->nombre ?? 'Inscripción general' }}

                    </div>

                </div>

            </div>

        </x-card>


        {{-- ================================================= --}}
        {{-- DATOS DEL JUGADOR --}}
        {{-- ================================================= --}}

        <x-card class="mb-6">

            <h2 class="text-xl font-bold text-slate-800 mb-2">
                👤 Datos del jugador
            </h2>

            <p class="text-sm text-gray-500 mb-6">
                Ingrese la información personal del jugador.
            </p>


            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">


                {{-- NOMBRES --}}

                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Nombres *
                    </label>

                    <input
                        type="text"
                        name="nombres"
                        value="{{ old('nombres') }}"
                        required
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                    >

                    @error('nombres')

                        <p class="text-red-600 text-sm mt-1">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- APELLIDOS --}}

                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Apellidos *
                    </label>

                    <input
                        type="text"
                        name="apellidos"
                        value="{{ old('apellidos') }}"
                        required
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                    >

                    @error('apellidos')

                        <p class="text-red-600 text-sm mt-1">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- DOCUMENTO --}}

                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Número de documento *
                    </label>

                    <input
                        type="text"
                        name="documento"
                        value="{{ old('documento') }}"
                        required
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                    >

                    @error('documento')

                        <p class="text-red-600 text-sm mt-1">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- FECHA DE NACIMIENTO --}}

                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Fecha de nacimiento *
                    </label>

                    <input
                        type="date"
                        name="fecha_nacimiento"
                        id="fecha_nacimiento"
                        value="{{ old('fecha_nacimiento') }}"
                        required
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                    >

                    <p
                        id="mensajeEdad"
                        class="text-sm mt-2 text-gray-500"
                    ></p>

                    @error('fecha_nacimiento')

                        <p class="text-red-600 text-sm mt-1">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- TELEFONO --}}

                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Teléfono
                    </label>

                    <input
                        type="text"
                        name="telefono"
                        value="{{ old('telefono') }}"
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                    >

                    @error('telefono')

                        <p class="text-red-600 text-sm mt-1">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- EMAIL --}}

                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Correo para acceso al sistema *
                    </label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                    >

                    <p class="text-xs text-gray-500 mt-1">
                        Este correo será utilizado para el acceso al sistema si la inscripción es aprobada.
                    </p>

                    @error('email')

                        <p class="text-red-600 text-sm mt-1">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- ================================================= --}}
                {{-- EPS --}}
                {{-- ================================================= --}}

                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        EPS *
                    </label>

                    <input
                        type="text"
                        name="eps"
                        value="{{ old('eps') }}"
                        required
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Ej. SURA"
                    >

                    @error('eps')

                        <p class="text-red-600 text-sm mt-1">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- ================================================= --}}
                {{-- TIPO DE SANGRE --}}
                {{-- ================================================= --}}

                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Tipo de sangre / RH *
                    </label>

                    <select
                        name="tipo_sangre"
                        required
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                    >

                        <option value="">
                            Seleccione...
                        </option>

                        @foreach([
                            'A+',
                            'A-',
                            'B+',
                            'B-',
                            'AB+',
                            'AB-',
                            'O+',
                            'O-'
                        ] as $tipo)

                            <option
                                value="{{ $tipo }}"
                                @selected(old('tipo_sangre') === $tipo)
                            >
                                {{ $tipo }}
                            </option>

                        @endforeach

                    </select>

                    @error('tipo_sangre')

                        <p class="text-red-600 text-sm mt-1">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- DIRECCIÓN --}}

                <div class="md:col-span-2">

                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Dirección
                    </label>

                    <input
                        type="text"
                        name="direccion"
                        value="{{ old('direccion') }}"
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                    >

                    @error('direccion')

                        <p class="text-red-600 text-sm mt-1">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- CLUB ANTERIOR --}}

                <div class="md:col-span-2">

                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Club anterior
                    </label>

                    <input
                        type="text"
                        name="club_anterior"
                        value="{{ old('club_anterior') }}"
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                    >

                    @error('club_anterior')

                        <p class="text-red-600 text-sm mt-1">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- OBSERVACIONES --}}

                <div class="md:col-span-2">

                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Observaciones
                    </label>

                    <textarea
                        name="observaciones"
                        rows="4"
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                    >{{ old('observaciones') }}</textarea>

                    @error('observaciones')

                        <p class="text-red-600 text-sm mt-1">
                            {{ $message }}
                        </p>

                    @enderror

                </div>

            </div>

        </x-card>


        {{-- ================================================= --}}
        {{-- ACUDIENTE --}}
        {{-- ================================================= --}}

        <div id="bloqueAcudiente">

            <x-card class="mb-6">

                <h2 class="text-xl font-bold text-slate-800 mb-2">
                    👨‍👩‍👧 Datos del acudiente
                </h2>

                <p class="text-sm text-gray-500 mb-6">
                    Para jugadores menores de 18 años estos datos son obligatorios.
                </p>


                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">


                    {{-- ACUDIENTE --}}

                    <div class="md:col-span-2">

                        <label class="block text-sm font-medium text-gray-700 mb-1">

                            Nombre completo del acudiente

                            <span
                                id="asteriscoAcudiente"
                                class="text-red-600"
                            >
                                *
                            </span>

                        </label>

                        <input
                            type="text"
                            name="acudiente"
                            id="acudiente"
                            value="{{ old('acudiente') }}"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        >

                        @error('acudiente')

                            <p class="text-red-600 text-sm mt-1">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>


                    {{-- DOCUMENTO ACUDIENTE --}}

                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">

                            Documento del acudiente

                            <span
                                id="asteriscoDocumentoAcudiente"
                                class="text-red-600"
                            >
                                *
                            </span>

                        </label>

                        <input
                            type="text"
                            name="documento_acudiente"
                            id="documento_acudiente"
                            value="{{ old('documento_acudiente') }}"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        >

                        @error('documento_acudiente')

                            <p class="text-red-600 text-sm mt-1">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>


                    {{-- TELEFONO ACUDIENTE --}}

                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">

                            Teléfono del acudiente

                            <span
                                id="asteriscoTelefonoAcudiente"
                                class="text-red-600"
                            >
                                *
                            </span>

                        </label>

                        <input
                            type="text"
                            name="telefono_acudiente"
                            id="telefono_acudiente"
                            value="{{ old('telefono_acudiente') }}"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        >

                        @error('telefono_acudiente')

                            <p class="text-red-600 text-sm mt-1">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>


                    {{-- EMAIL ACUDIENTE --}}

                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Correo del acudiente
                        </label>

                        <input
                            type="email"
                            name="email_acudiente"
                            id="email_acudiente"
                            value="{{ old('email_acudiente') }}"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        >

                        @error('email_acudiente')

                            <p class="text-red-600 text-sm mt-1">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>


                    {{-- PARENTESCO --}}

                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">

                            Parentesco

                            <span
                                id="asteriscoParentesco"
                                class="text-red-600"
                            >
                                *
                            </span>

                        </label>

                        <select
                            name="parentesco"
                            id="parentesco"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        >

                            <option value="">
                                Seleccione...
                            </option>

                            @foreach([
                                'Padre',
                                'Madre',
                                'Abuelo',
                                'Abuela',
                                'Hermano',
                                'Hermana',
                                'Tutor',
                                'Otro'
                            ] as $parentesco)

                                <option
                                    value="{{ $parentesco }}"
                                    @selected(old('parentesco') === $parentesco)
                                >
                                    {{ $parentesco }}
                                </option>

                            @endforeach

                        </select>

                        @error('parentesco')

                            <p class="text-red-600 text-sm mt-1">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>

                </div>

            </x-card>

        </div>


        {{-- ================================================= --}}
        {{-- DOCUMENTOS --}}
        {{-- ================================================= --}}

        <x-card class="mb-6">

            <h2 class="text-xl font-bold text-slate-800 mb-2">
                📎 Documentos
            </h2>

            <p class="text-sm text-gray-500 mb-6">
                Adjunte los documentos solicitados para que el club pueda revisarlos.
            </p>


            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">


                {{-- FOTO --}}

                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Foto del jugador *
                    </label>

                    <input
                        type="file"
                        name="foto"
                        accept=".jpg,.jpeg,image/jpeg"
                        required
                        class="w-full rounded-lg border-gray-300 bg-white focus:border-blue-500 focus:ring-blue-500"
                    >

                    <p class="text-xs text-gray-500 mt-2">
                        JPG o JPEG. Máximo 3 MB.
                    </p>

                    @error('foto')

                        <p class="text-red-600 text-sm mt-1">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- PDF --}}

                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Documento de identidad *
                    </label>

                    <input
                        type="file"
                        name="documento_pdf"
                        accept=".pdf,application/pdf"
                        required
                        class="w-full rounded-lg border-gray-300 bg-white focus:border-blue-500 focus:ring-blue-500"
                    >

                    <p class="text-xs text-gray-500 mt-2">
                        PDF. Máximo 5 MB.
                    </p>

                    @error('documento_pdf')

                        <p class="text-red-600 text-sm mt-1">
                            {{ $message }}
                        </p>

                    @enderror

                </div>

            </div>

        </x-card>


        {{-- ================================================= --}}
        {{-- AUTORIZACIÓN --}}
        {{-- ================================================= --}}

        <x-card class="mb-6">

            <div class="flex items-start gap-3">

                <input
                    type="checkbox"
                    name="autorizacion"
                    value="1"
                    required
                    class="mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                >

                <div>

                    <p class="text-sm text-gray-700">

                        Declaro que la información suministrada es verdadera
                        y autorizo al club a utilizarla para gestionar el proceso
                        de inscripción y registro del jugador.

                    </p>

                    @error('autorizacion')

                        <p class="text-red-600 text-sm mt-1">
                            {{ $message }}
                        </p>

                    @enderror

                </div>

            </div>

        </x-card>


        {{-- ================================================= --}}
        {{-- BOTONES --}}
        {{-- ================================================= --}}

        <div class="flex items-center justify-between gap-4">

            <a
                href="{{ route('inicio') }}"
                class="text-gray-600 hover:text-gray-900"
            >

                ← Volver

            </a>


            <x-button
                type="submit"
                color="blue"
            >

                📩 Enviar solicitud

            </x-button>

        </div>

    </form>

</div>


{{-- ========================================================= --}}
{{-- CONTROL DE EDAD --}}
{{-- ========================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const fechaNacimiento =
        document.getElementById('fecha_nacimiento');

    const bloqueAcudiente =
        document.getElementById('bloqueAcudiente');

    const mensajeEdad =
        document.getElementById('mensajeEdad');

    const acudiente =
        document.getElementById('acudiente');

    const documentoAcudiente =
        document.getElementById('documento_acudiente');

    const telefonoAcudiente =
        document.getElementById('telefono_acudiente');

    const parentesco =
        document.getElementById('parentesco');


    const camposObligatorios = [

        acudiente,
        documentoAcudiente,
        telefonoAcudiente,
        parentesco

    ];


    const asteriscos = [

        document.getElementById('asteriscoAcudiente'),
        document.getElementById('asteriscoDocumentoAcudiente'),
        document.getElementById('asteriscoTelefonoAcudiente'),
        document.getElementById('asteriscoParentesco')

    ];


    function actualizarEdad() {

        if (!fechaNacimiento.value) {

            bloqueAcudiente.classList.remove('hidden');

            camposObligatorios.forEach(function (campo) {

                campo.required = true;

            });

            asteriscos.forEach(function (asterisco) {

                asterisco.classList.remove('hidden');

            });

            mensajeEdad.textContent =
                'Seleccione la fecha de nacimiento para determinar si necesita acudiente.';

            mensajeEdad.className =
                'text-sm mt-2 text-gray-500';

            return;

        }


        const nacimiento =
            new Date(fechaNacimiento.value + 'T00:00:00');

        const hoy =
            new Date();


        let edad =
            hoy.getFullYear() -
            nacimiento.getFullYear();


        const diferenciaMes =
            hoy.getMonth() -
            nacimiento.getMonth();


        if (
            diferenciaMes < 0 ||
            (
                diferenciaMes === 0 &&
                hoy.getDate() < nacimiento.getDate()
            )
        ) {

            edad--;

        }


        if (edad < 18) {

            bloqueAcudiente.classList.remove('hidden');


            camposObligatorios.forEach(function (campo) {

                campo.required = true;

            });


            asteriscos.forEach(function (asterisco) {

                asterisco.classList.remove('hidden');

            });


            mensajeEdad.textContent =
                'El jugador es menor de edad (' +
                edad +
                ' años). Los datos del acudiente son obligatorios.';

            mensajeEdad.className =
                'text-sm mt-2 text-orange-600 font-semibold';

        } else {

            bloqueAcudiente.classList.add('hidden');


            camposObligatorios.forEach(function (campo) {

                campo.required = false;

            });


            asteriscos.forEach(function (asterisco) {

                asterisco.classList.add('hidden');

            });


            mensajeEdad.textContent =
                'El jugador es mayor de edad (' +
                edad +
                ' años). No se requiere acudiente.';

            mensajeEdad.className =
                'text-sm mt-2 text-green-600 font-semibold';

        }

    }


    fechaNacimiento.addEventListener(
        'change',
        actualizarEdad
    );


    actualizarEdad();

});

</script>

@endsection
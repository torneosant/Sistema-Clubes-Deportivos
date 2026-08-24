<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Solicitud de Inscripción</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

</head>

<body class="bg-gray-100 min-h-screen">

<div class="max-w-3xl mx-auto py-10 px-4">

    <div class="bg-white rounded-2xl shadow-lg p-8">

        <div class="text-center mb-8">

            <div class="text-5xl mb-3">
                ⚽
            </div>

            <h1 class="text-3xl font-bold text-slate-800">
                Solicitud de Inscripción
            </h1>

            <p class="text-gray-500 mt-2">
                Complete la información para solicitar su inscripción.
            </p>

        </div>


        {{-- CATEGORÍA --}}

        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-8">

            <div class="text-sm text-blue-600 font-semibold">
                Categoría
            </div>

            <div class="text-lg font-bold text-slate-800">

                {{ $inscripcion->categoria->nombre ?? 'Inscripción general' }}

            </div>

        </div>


        <form
            action="{{ route(
                'inscripcion.publica.store',
                $inscripcion->token
            ) }}"
            method="POST">

            @csrf


            {{-- NOMBRES --}}

            <div class="mb-5">

                <label class="font-semibold">
                    Nombres *
                </label>

                <input
                    type="text"
                    name="nombres"
                    value="{{ old('nombres') }}"
                    class="w-full border rounded-xl p-3 mt-2"
                    required>

                @error('nombres')

                    <p class="text-red-600 text-sm mt-1">
                        {{ $message }}
                    </p>

                @enderror

            </div>


            {{-- APELLIDOS --}}

            <div class="mb-5">

                <label class="font-semibold">
                    Apellidos *
                </label>

                <input
                    type="text"
                    name="apellidos"
                    value="{{ old('apellidos') }}"
                    class="w-full border rounded-xl p-3 mt-2"
                    required>

                @error('apellidos')

                    <p class="text-red-600 text-sm mt-1">
                        {{ $message }}
                    </p>

                @enderror

            </div>


            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">


                {{-- DOCUMENTO --}}

                <div>

                    <label class="font-semibold">
                        Documento
                    </label>

                    <input
                        type="text"
                        name="documento"
                        value="{{ old('documento') }}"
                        class="w-full border rounded-xl p-3 mt-2">

                </div>


                {{-- FECHA NACIMIENTO --}}

                <div>

                    <label class="font-semibold">
                        Fecha de nacimiento
                    </label>

                    <input
                        type="date"
                        name="fecha_nacimiento"
                        value="{{ old('fecha_nacimiento') }}"
                        class="w-full border rounded-xl p-3 mt-2">

                </div>


                {{-- TELEFONO --}}

                <div>

                    <label class="font-semibold">
                        Teléfono
                    </label>

                    <input
                        type="text"
                        name="telefono"
                        value="{{ old('telefono') }}"
                        class="w-full border rounded-xl p-3 mt-2">

                </div>


                {{-- EMAIL --}}

                <div>

                    <label class="font-semibold">
                        Correo electrónico
                    </label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="w-full border rounded-xl p-3 mt-2">

                    @error('email')

                        <p class="text-red-600 text-sm mt-1">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- POSICION --}}

                <div>

                    <label class="font-semibold">
                        Posición
                    </label>

                    <input
                        type="text"
                        name="posicion"
                        value="{{ old('posicion') }}"
                        class="w-full border rounded-xl p-3 mt-2">

                </div>


                {{-- CLUB ANTERIOR --}}

                <div>

                    <label class="font-semibold">
                        Club anterior
                    </label>

                    <input
                        type="text"
                        name="club_anterior"
                        value="{{ old('club_anterior') }}"
                        class="w-full border rounded-xl p-3 mt-2">

                </div>


                {{-- DIRECCION --}}

                <div class="md:col-span-2">

                    <label class="font-semibold">
                        Dirección
                    </label>

                    <input
                        type="text"
                        name="direccion"
                        value="{{ old('direccion') }}"
                        class="w-full border rounded-xl p-3 mt-2">

                </div>


                {{-- OBSERVACIONES --}}

                <div class="md:col-span-2">

                    <label class="font-semibold">
                        Observaciones
                    </label>

                    <textarea
                        name="observaciones"
                        rows="4"
                        class="w-full border rounded-xl p-3 mt-2">{{ old('observaciones') }}</textarea>

                </div>

            </div>


            <div class="mt-8">

                <button
                    type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-xl">

                    📩 Enviar solicitud de inscripción

                </button>

            </div>

        </form>

    </div>

</div>

</body>

</html>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Gestión Clubes</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-100 min-h-screen">

    <div class="min-h-screen flex items-center justify-center px-6">

        <div class="w-full max-w-5xl">

            {{-- Encabezado --}}
            <div class="text-center mb-12">

                <div class="text-6xl mb-5">
                    ⚽
                </div>

                <h1 class="text-4xl md:text-5xl font-bold text-slate-800">
                    Gestión Clubes
                </h1>

                <p class="mt-4 text-lg text-slate-500">
                    Plataforma de gestión para clubes deportivos
                </p>

            </div>


            {{-- Opciones --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                {{-- Iniciar sesión --}}
                <a href="{{ route('login') }}"
                   class="group bg-white rounded-2xl shadow-lg p-8
                          border border-slate-200
                          hover:shadow-xl hover:-translate-y-1
                          transition duration-200">

                    <div class="text-5xl mb-6">
                        🔐
                    </div>

                    <h2 class="text-2xl font-bold text-slate-800 mb-3">
                        Iniciar sesión
                    </h2>

                    <p class="text-slate-500 mb-6">
                        Accede a tu cuenta como administrador de  tu club,
                        o usuario del club
                    </p>

                    <div class="inline-block bg-blue-600
                                group-hover:bg-blue-700
                                text-white px-6 py-3
                                rounded-lg font-semibold">

                        Ingresar →

                    </div>

                </a>


                {{-- Registrar club --}}
                <a href="{{ route('registro.club') }}"
                   class="group bg-white rounded-2xl shadow-lg p-8
                          border border-slate-200
                          hover:shadow-xl hover:-translate-y-1
                          transition duration-200">

                    <div class="text-5xl mb-6">
                        🏟️
                    </div>

                    <h2 class="text-2xl font-bold text-slate-800 mb-3">
                        Registrar un club nuevo
                    </h2>

                    <p class="text-slate-500 mb-6">
                        Registra tu club en Gestión Clubes y crea
                        la cuenta del administrador para comenzar
                        a utilizar la plataforma.
                    </p>

                    <div class="inline-block bg-emerald-600
                                group-hover:bg-emerald-700
                                text-white px-6 py-3
                                rounded-lg font-semibold">

                        Registrar club →

                    </div>

                </a>

            </div>


            {{-- Pie --}}
            <div class="text-center mt-12 text-sm text-slate-400">

                Gestión Clubes · Gestión deportiva integral

            </div>

        </div>

    </div>

</body>

</html>
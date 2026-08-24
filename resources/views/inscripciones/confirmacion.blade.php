<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Solicitud enviada</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

</head>

<body class="bg-gray-100 min-h-screen">

<div class="max-w-xl mx-auto py-20 px-4">

    <div class="bg-white rounded-2xl shadow-lg p-10 text-center">

        <div class="text-6xl mb-5">
            ✅
        </div>

        <h1 class="text-3xl font-bold text-slate-800">
            Solicitud enviada
        </h1>

        <p class="text-gray-500 mt-4">

            Gracias por enviar tu información.

            La solicitud será revisada por el administrador
            del club.

        </p>

        <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-xl p-4">

            <div class="font-semibold text-yellow-700">
                Estado
            </div>

            <div class="text-yellow-800 mt-1">
                Pendiente de revisión
            </div>

        </div>

    </div>

</div>

</body>

</html>
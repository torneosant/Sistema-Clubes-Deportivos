<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión Clubes</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">

<div class="flex min-h-screen">

    <!-- Menú lateral -->
    <aside class="w-64 bg-slate-900 text-white">

        <div class="p-5 text-2xl font-bold border-b border-slate-700">
            ⚽ Gestión Clubes
        </div>

        <nav class="mt-5">

            <a href="/dashboard" class="block px-5 py-3 hover:bg-slate-800">
                🏠 Dashboard
            </a>

            <a href="/club" class="block px-5 py-3 hover:bg-slate-800">
                🏟️ Mi Club
            </a>

            <a href="#" class="block px-5 py-3 hover:bg-slate-800">
                👥 Jugadores
            </a>

            <a href="{{ route('equipos.index') }}"
              class="block px-5 py-3 hover:bg-slate-800">

            ⚽ Equipos

            </a>

            <a href="{{ route('categorias.index') }}" class="block px-5 py-3 hover:bg-slate-800">
             📂 Categorías
            </a>

            <a href="#" class="block px-5 py-3 hover:bg-slate-800">
                👨‍🏫 Entrenadores
            </a>

            <a href="#" class="block px-5 py-3 hover:bg-slate-800">
                💰 Pagos
            </a>

            <a href="#" class="block px-5 py-3 hover:bg-slate-800">
                📊 Reportes
            </a>

        </nav>

    </aside>

    <!-- Contenido -->

    <main class="flex-1">

        <header class="bg-white shadow px-8 py-4 flex justify-between">

            <h1 class="text-2xl font-bold">
                @yield('titulo')
            </h1>

            <div>

                {{ auth()->user()->name }}

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button class="text-red-600 ml-4">
                        Salir
                    </button>
                </form>

            </div>

        </header>

        <div class="p-8">

            @yield('contenido')

        </div>

    </main>

</div>
@if(session('success'))
<script>
document.addEventListener('DOMContentLoaded', () => {
    Swal.fire({
        icon: 'success',
        title: '¡Correcto!',
        text: '{{ session('success') }}',
        confirmButtonColor: '#16a34a'
    });
});
</script>
@endif


</body>
</html>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión Clubes</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-gray-100">

<div class="flex min-h-screen">

    <!-- Menú lateral -->
    <aside class="w-64 bg-slate-900 text-white">

        <div class="p-5 text-2xl font-bold border-b border-slate-700">
            ⚽ Gestión Clubes
        </div>

        <nav class="mt-5">

            @if(auth()->user()->tienePermiso('dashboard.ver'))

<a href="/dashboard" class="block px-5 py-3 hover:bg-slate-800">
🏠 Dashboard
</a>

@endif

            @if(auth()->user()->tienePermiso('club.ver'))

<a href="/club" class="block px-5 py-3 hover:bg-slate-800">
🏟️ Mi Club
</a>

@endif

            @if(auth()->user()->tienePermiso('equipos.ver'))

<a href="{{ route('equipos.index') }}"
class="block px-5 py-3 hover:bg-slate-800">

⚽ Equipos

</a>

@endif

            @if(auth()->user()->tienePermiso('categorias.ver'))

<a href="{{ route('categorias.index') }}"
class="block px-5 py-3 hover:bg-slate-800">

📂 Categorías

</a>

@endif



          
@if(auth()->user()->tienePermiso('jugadores.ver'))

<a href="{{ route('jugadores.index') }}"
   class="block px-5 py-3 hover:bg-slate-800">

    👥 Jugadores

</a>

@endif

            @if(auth()->user()->tienePermiso('entrenadores.ver'))

<a href="{{ route('entrenadores.index') }}"
class="block px-5 py-3 hover:bg-slate-800">

👨‍🏫 Entrenadores

</a>

@endif

          @if(auth()->user()->tienePermiso('entrenamientos.ver'))

<a href="{{ route('entrenamientos.index') }}"
class="block px-5 py-3 hover:bg-slate-800">

🏃 Entrenamientos

</a>

@endif

           @if(auth()->user()->tienePermiso('partidos.ver'))

<a href="{{ route('partidos.index') }}"
class="block px-5 py-3 hover:bg-slate-800">

⚽ Partidos

</a>

@endif

           @if(auth()->user()->tienePermiso('calendario.ver'))

<a href="{{ route('calendario.index') }}"
class="block px-5 py-3 hover:bg-slate-800">

📅 Calendario

</a>

@endif

   <div x-data="{ openConta: true }">

@if(auth()->user()->tienePermiso('contabilidad.ver'))

<button
    @click="openConta=!openConta"
    class="w-full flex justify-between items-center px-5 py-3 hover:bg-slate-800">

    <span>💰 Contabilidad</span>

    <span x-text="openConta ? '▼' : '▶'"></span>

</button>

<div x-show="openConta" x-transition>

    <a href="{{ route('contabilidad.index') }}"
       class="block px-10 py-2 hover:bg-slate-800">
        📋 Movimientos
    </a>

    <a href="{{ route('conceptos-contables.index') }}"
       class="block px-10 py-2 hover:bg-slate-800">
        📂 Conceptos
    </a>

</div>

@endif

</div>


           @if(auth()->user()->tienePermiso('conceptos_contablesver'))

<a href="{{ route('conceptos-contables.index') }}"
class="block px-10 py-2 hover:bg-slate-800">

📂 Conceptos

</a>

@endif


             @if(auth()->user()->tienePermiso('historial-medico.ver'))

<a href="{{ route('historial-medico.index') }}"
class="block px-5 py-3 hover:bg-slate-800">

❤️ Médico

</a>

@endif
     
<div x-data="{ openConfig: true }">

@if(auth()->user()->tienePermiso('configuracion.ver'))

<button
    @click="openConfig=!openConfig"
    class="w-full flex justify-between items-center px-5 py-3 hover:bg-slate-800">

    <span>⚙️ Configuración</span>

    <span x-text="openConfig ? '▼' : '▶'"></span>

</button>

<div x-show="openConfig" x-transition>

    <a href="{{ route('configuracion.general') }}" class="block px-10 py-2 hover:bg-slate-800">
        🏢 General
    </a>

    <a href="{{ route('configuracion.redes') }}" class="block px-10 py-2 hover:bg-slate-800">
        🌐 Redes
    </a>

    <a href="{{ route('configuracion.deportivo') }}" class="block px-10 py-2 hover:bg-slate-800">
        ⚽ Deportivo
    </a>

    <a href="{{ route('configuracion.sistema') }}" class="block px-10 py-2 hover:bg-slate-800">
        💻 Sistema
    </a>

    <a href="{{ route('usuarios.index') }}" class="block px-10 py-2 hover:bg-slate-800">
        👥 Usuarios
    </a>

    <a href="{{ route('roles.index') }}" class="block px-10 py-2 hover:bg-slate-800">
        🔐 Roles
    </a>

</div>

@endif

</div>

@if(auth()->user()->tienePermiso('documentacion.ver'))

<a href="{{ route('documentos.index') }}"
class="block px-5 py-3 hover:bg-slate-800">

📚 Centro de Documentación

</a>

@endif

@if(auth()->user()->tienePermiso('tipos_documento.ver'))

<a href="{{ route('tipos-documento.index') }}"
class="block px-10 py-2 hover:bg-slate-800">

📂 Tipos de documentos

</a>

@endif

@if(auth()->user()->tienePermiso('inventario.ver'))

<li x-data="{ open: false }">

    <button
        @click="open=!open"
        class="w-full flex justify-between items-center px-4 py-2 hover:bg-slate-700 rounded-lg">

        <span>📦 Inventario</span>

        <span x-text="open ? '−' : '+'"></span>

    </button>

    <ul
        x-show="open"
        x-transition
        class="ml-5 mt-2 space-y-1">

        @if(auth()->user()->tienePermiso('inventario.ver'))
        <li>
            <a href="{{ route('inventario.index') }}"
               class="block px-3 py-2 hover:bg-slate-700 rounded-lg">
                Inventario
            </a>
        </li>
        @endif

        @if(auth()->user()->tienePermiso('tipos_articulo.ver'))
        <li>
            <a href="{{ route('tipos-articulo.index') }}"
               class="block px-3 py-2 hover:bg-slate-700 rounded-lg">
                Tipos de Artículos
            </a>
        </li>
        @endif

        @if(auth()->user()->tienePermiso('asignaciones_inventario.ver'))
        <li>
            <a href="{{ route('asignaciones-inventario.index') }}"
               class="block px-3 py-2 hover:bg-slate-700 rounded-lg">
                Asignaciones
            </a>
        </li>
        @endif

    </ul>

</li>

@endif




                📊 Reportes
            </a>

        </nav>

    </aside>

    <!-- Contenido -->

    <main class="flex-1">

        <header class="bg-white border-b border-gray-200 px-8 py-5 flex justify-between items-center">

    <div>

        <h1 class="text-3xl font-bold text-slate-800">
            @yield('titulo')
        </h1>

        <p class="text-sm text-gray-500 mt-1">
            Bienvenido nuevamente al Sistema de Gestión de Clubes.
        </p>

    </div>

    <div class="flex items-center gap-6">

        <div class="text-right">

            <div class="font-semibold text-slate-700">
                {{ auth()->user()->name }}
            </div>

            <div class="text-xs text-gray-500">
                Usuario conectado
            </div>

        </div>

        <div class="w-11 h-11 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-lg">

            {{ strtoupper(substr(auth()->user()->name,0,1)) }}

        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button
                class="px-4 py-2 rounded-lg bg-red-500 text-white hover:bg-red-600 transition">

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

<script>
function confirmarEliminar(boton) {

    Swal.fire({
        title: '¿Eliminar registro?',
        text: 'Esta acción no se puede deshacer.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {

        if (result.isConfirmed) {
            boton.closest('form').submit();
        }

    });

}
</script>
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@yield('scripts')

</body>
</html>
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

@if(auth()->user()->tienePermiso('inscripciones.ver'))

<a href="{{ route('inscripciones.index') }}"
   class="block px-5 py-3 hover:bg-slate-800">

    📝 Inscripciones

</a>

@endif


@if(auth()->user()->tienePermiso('competencias.ver'))

<a href="{{ route('competencias.index') }}"
   class="block px-5 py-3 hover:bg-slate-800">

    🏆 Competencias

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

   <div x-data="{ openConta: false }">

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
     
<div x-data="{ openConfig: false }">

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

    <a href="{{ route('configuracion.inscripciones') }}"
   class="block px-5 py-3 hover:bg-slate-800">

    📝 Inscripciones

</a>

</div>

@endif

</div>

{{-- ============================= --}}
{{-- CENTRO DE DOCUMENTACIÓN --}}
{{-- ============================= --}}

@if(
    auth()->user()->tienePermiso('documentacion.ver') ||
    auth()->user()->tienePermiso('tipos_documento.ver')
)

<div x-data="{ openDocumentacion: false }">

    <div class="flex items-center">

        {{-- Centro de documentación --}}
        @if(auth()->user()->tienePermiso('documentacion.ver'))

            <a
                href="{{ route('documentos.index') }}"
                class="flex-1 block px-5 py-3 hover:bg-slate-800"
            >
                📚 Centro de Documentación
            </a>

        @endif


        {{-- Botón para mostrar submenú --}}
        @if(auth()->user()->tienePermiso('tipos_documento.ver'))

            <button
                type="button"
                @click="openDocumentacion = !openDocumentacion"
                class="px-4 py-3 hover:bg-slate-800"
                title="Más opciones"
            >

                <span
                    x-text="openDocumentacion ? '▼' : '▶'"
                ></span>

            </button>

        @endif

    </div>


    {{-- Submenú --}}

    @if(auth()->user()->tienePermiso('tipos_documento.ver'))

        <div
            x-show="openDocumentacion"
            x-transition
            class="bg-slate-950"
        >

            <a
                href="{{ route('tipos-documento.index') }}"
                class="block px-10 py-2 text-sm hover:bg-slate-800"
            >
                📂 Tipos de documentos
            </a>

        </div>

    @endif

</div>

@endif

@if(auth()->user()->tienePermiso('inventario.ver'))

<li x-data="{ open: false }">

    <button
        @click="open=!open"
        class="w-full flex justify-between items-center px-4 py-2 hover:bg-slate-700 rounded-lg">

        <span>📦 Inventario</span>

        <span x-text="open ? '▼' : '▶'"></span>

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

    <div class="flex items-center gap-4">

    <div class="flex items-center gap-4">

    <x-year-selector />

    <div x-data="{ openPerfil: false }" class="relative">

        <button
            @click="openPerfil = !openPerfil"
            class="flex items-center gap-3 focus:outline-none">

            <div class="text-right">

                <div class="font-semibold text-slate-700">
                    {{ auth()->user()->name }}
                </div>

                <div class="text-xs text-gray-500">
                    Usuario conectado
                </div>

            </div>

            <div
                class="w-11 h-11 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-lg">

                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}

            </div>

            <span class="text-gray-500 text-sm">
                ▼
            </span>

        </button>


        {{-- Menú del usuario --}}

        <div
            x-show="openPerfil"
            @click.outside="openPerfil = false"
            x-transition
            class="absolute right-0 mt-3 w-56 bg-white rounded-xl shadow-lg border border-gray-200 z-50 overflow-hidden">

            <div class="px-4 py-3 border-b bg-gray-50">

                <div class="font-semibold text-slate-800">
                    {{ auth()->user()->name }}
                </div>

                <div class="text-xs text-gray-500">
                    {{ auth()->user()->email }}
                </div>

            </div>


            <a
                href="{{ route('perfil') }}"
                class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-100">

                👤 Mi perfil

            </a>


            <a
                href="{{ route('perfil.password') }}"
                class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-100">

                🔑 Cambiar contraseña

            </a>


            <div class="border-t"></div>


            <form method="POST" action="{{ route('logout') }}">

                @csrf

                <button
                    type="submit"
                    class="w-full text-left px-4 py-3 text-sm text-red-600 hover:bg-red-50">

                    🚪 Cerrar sesión

                </button>

            </form>

        </div>

    </div>

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
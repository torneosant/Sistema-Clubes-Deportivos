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

            @if(auth()->user()->tienePermiso('dashboard'))

<a href="/dashboard" class="block px-5 py-3 hover:bg-slate-800">
🏠 Dashboard
</a>

@endif

            @if(auth()->user()->tienePermiso('club'))

<a href="/club" class="block px-5 py-3 hover:bg-slate-800">
🏟️ Mi Club
</a>

@endif

            @if(auth()->user()->tienePermiso('equipos'))

<a href="{{ route('equipos.index') }}"
class="block px-5 py-3 hover:bg-slate-800">

⚽ Equipos

</a>

@endif

            @if(auth()->user()->tienePermiso('categorias'))

<a href="{{ route('categorias.index') }}"
class="block px-5 py-3 hover:bg-slate-800">

📂 Categorías

</a>

@endif



          
@if(auth()->user()->tienePermiso('jugadores'))

<a href="{{ route('jugadores.index') }}"
   class="block px-5 py-3 hover:bg-slate-800">

    👥 Jugadores

</a>

@endif

            @if(auth()->user()->tienePermiso('entrenadores'))

<a href="{{ route('entrenadores.index') }}"
class="block px-5 py-3 hover:bg-slate-800">

👨‍🏫 Entrenadores

</a>

@endif

          @if(auth()->user()->tienePermiso('entrenamientos'))

<a href="{{ route('entrenamientos.index') }}"
class="block px-5 py-3 hover:bg-slate-800">

🏃 Entrenamientos

</a>

@endif

           @if(auth()->user()->tienePermiso('partidos'))

<a href="{{ route('partidos.index') }}"
class="block px-5 py-3 hover:bg-slate-800">

⚽ Partidos

</a>

@endif

           @if(auth()->user()->tienePermiso('calendario'))

<a href="{{ route('calendario.index') }}"
class="block px-5 py-3 hover:bg-slate-800">

📅 Calendario

</a>

@endif

           @if(auth()->user()->tienePermiso('contabilidad'))

<a href="{{ route('contabilidad.index') }}"
class="block px-5 py-3 hover:bg-slate-800">

💰 Contabilidad

</a>

@endif


           @if(auth()->user()->tienePermiso('conceptos_contables'))

<a href="{{ route('conceptos-contables.index') }}"
class="block px-10 py-2 hover:bg-slate-800">

📂 Conceptos

</a>

@endif


             @if(auth()->user()->tienePermiso('historial-medico'))

<a href="{{ route('historial-medico.index') }}"
class="block px-5 py-3 hover:bg-slate-800">

❤️ Médico

</a>

@endif
     
@if(auth()->user()->tienePermiso('configuracion'))

<a href="{{ route('configuracion.general') }}"
class="block px-5 py-3 hover:bg-slate-800">

⚙️ Configuración

</a>




<a href="{{ route('configuracion.general') }}"
class="block px-10 py-2 hover:bg-slate-800">

🏢 General

</a>



<a href="{{ route('configuracion.redes') }}"
class="block px-10 py-2 hover:bg-slate-800">

🌐 Redes Sociales

</a>




<a href="{{ route('configuracion.deportivo') }}"
class="block px-10 py-2 hover:bg-slate-800">

⚽ Deportivo

</a>




<a href="{{ route('configuracion.sistema') }}"
class="block px-10 py-2 hover:bg-slate-800">

⚙️ Sistema

</a>
@endif

@if(auth()->user()->tienePermiso('usuarios'))
<a href="{{ route('usuarios.index') }}"
class="block px-10 py-2 hover:bg-slate-800">

👥 Usuarios

</a>
@endif


<a href="{{ route('roles.index') }}"
class="block px-10 py-2 hover:bg-slate-800">

🔐 Roles

</a>





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

<script>
function confirmarEliminar(boton) {
    if (confirm('¿Está seguro de eliminar este registro?')) {
        boton.closest('form').submit();
    }
}
</script>
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@yield('scripts')

</body>
</html>
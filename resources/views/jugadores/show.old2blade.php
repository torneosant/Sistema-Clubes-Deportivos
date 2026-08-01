@extends('layouts.app')

@section('titulo','Ficha del Jugador')

@section('contenido')

<div class="max-w-7xl mx-auto">

    {{-- CABECERA --}}
    <div class="bg-white rounded-3xl shadow-xl overflow-hidden">

        {{-- Fondo --}}
        <div class="h-52 bg-gradient-to-r from-blue-700 via-slate-800 to-slate-900"></div>

        <div class="px-10 pb-10">

            <div class="-mt-24 flex flex-col items-center">

                {{-- FOTO --}}
                @if($jugador->foto)

                    <img
                        src="{{ asset('storage/'.$jugador->foto) }}"
                        class="w-48 h-48 rounded-full border-8 border-white shadow-xl object-cover">

                @else

                    <div
                        class="w-48 h-48 rounded-full border-8 border-white shadow-xl
                               bg-slate-200 flex items-center justify-center">

                        <span class="text-6xl font-bold text-slate-600">

                            {{ strtoupper(substr($jugador->nombres,0,1)) }}
                            {{ strtoupper(substr($jugador->apellidos,0,1)) }}

                        </span>

                    </div>

                @endif

                <h1 class="text-4xl font-bold text-slate-800 mt-6">

                    {{ $jugador->nombres }}
                    {{ $jugador->apellidos }}

                </h1>

                <div class="flex flex-wrap justify-center gap-3 mt-5">

                    @if($jugador->activo)

                        <span class="px-4 py-2 rounded-full bg-green-100 text-green-700 font-semibold">
                            🟢 Activo
                        </span>

                    @else

                        <span class="px-4 py-2 rounded-full bg-red-100 text-red-700 font-semibold">
                            🔴 Inactivo
                        </span>

                    @endif

                    <span class="px-4 py-2 rounded-full bg-blue-100 text-blue-700">
                        ⚽ {{ optional($jugador->categoria)->nombre ?? 'Sin categoría' }}
                    </span>

                    <span class="px-4 py-2 rounded-full bg-purple-100 text-purple-700">
                        🛡️ {{ optional($jugador->equipo)->nombre ?? 'Sin equipo' }}
                    </span>

                    <span class="px-4 py-2 rounded-full bg-gray-100 text-gray-700">
                        {{ $jugador->posicion ?? 'Sin posición' }}
                    </span>

                </div>

            </div>

        </div>

    </div>
    {{-- TARJETAS --}}
    <div class="grid grid-cols-12 gap-8 mt-8">

    <div class="col-span-12 lg:col-span-3">

    <div class="bg-white rounded-2xl shadow-lg overflow-hidden sticky top-5">

        <div class="bg-slate-800 text-white p-4 font-bold">
            📂 Gestión
        </div>

        <div class="p-2 space-y-2">

            <button class="menu-btn w-full text-left px-4 py-3 rounded-lg bg-blue-100">
                👤 Información
            </button>

            <button class="menu-btn w-full text-left px-4 py-3 rounded-lg">
                ❤️ Médico
            </button>

            <button class="menu-btn w-full text-left px-4 py-3 rounded-lg">
                💰 Contabilidad
            </button>

            <button class="menu-btn w-full text-left px-4 py-3 rounded-lg">
                📊 Estadísticas
            </button>

            <button class="menu-btn w-full text-left px-4 py-3 rounded-lg">
                📅 Asistencia
            </button>

            <button class="menu-btn w-full text-left px-4 py-3 rounded-lg">
                📄 Documentos
            </button>

            <button class="menu-btn w-full text-left px-4 py-3 rounded-lg">
                📝 Observaciones
            </button>

        </div>

    </div>

</div>

<div class="col-span-12 lg:col-span-9">

<div id="panel-info">

 {{-- ================= aqui termina el dashboard lateral ================= --}}
<div class="flex gap-6">

    {{-- MENÚ --}}
    <div class="w-72">

        {{-- aquí ya está tu dashboard --}}

    </div>

    {{-- PANEL --}}
    <div class="flex-1">

        <div id="panel-principal">

            {{-- aquí quedan las tarjetas que ya hicimos --}}

        </div>

        <div id="panel-medico" class="hidden">

        </div>

        <div id="panel-estadisticas" class="hidden">

        </div>

        <div id="panel-finanzas" class="hidden">

        </div>

        <div id="panel-asistencias" class="hidden">

        </div>

    </div>

</div>

      {{-- ================= DATOS PERSONALES ================= --}}
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">

        <div class="bg-slate-800 text-white px-5 py-3 font-bold">
            👤 Datos personales
        </div>

        <div class="p-5">

            <div class="grid grid-cols-2 gap-x-8 gap-y-4">

                <div>
                    <small class="text-gray-500">Documento</small>
                    <div class="font-semibold">
                        {{ $jugador->tipo_documento }}
                        {{ $jugador->numero_documento }}
                    </div>
                </div>

                <div>
                    <small class="text-gray-500">Nacimiento</small>
                    <div class="font-semibold">
                        {{ optional($jugador->fecha_nacimiento)->format('d/m/Y') }}
                    </div>
                </div>

                <div>
                    <small class="text-gray-500">Edad</small>
                    <div class="font-semibold">
                        {{ $jugador->fecha_nacimiento ? $jugador->fecha_nacimiento->age.' años' : '-' }}
                    </div>
                </div>

                <div>
                    <small class="text-gray-500">Género</small>
                    <div class="font-semibold">
                        {{ $jugador->genero ?: '-' }}
                    </div>
                </div>

                <div>
                    <small class="text-gray-500">Teléfono</small>
                    <div class="font-semibold">
                        {{ $jugador->telefono ?: '-' }}
                    </div>
                </div>

                <div>
                    <small class="text-gray-500">Correo</small>
                    <div class="font-semibold break-all">
                        {{ $jugador->email ?: '-' }}
                    </div>
                </div>

                <div>
                    <small class="text-gray-500">Ciudad</small>
                    <div class="font-semibold">
                        {{ $jugador->ciudad ?: '-' }}
                    </div>
                </div>

                <div>
                    <small class="text-gray-500">Dirección</small>
                    <div class="font-semibold">
                        {{ $jugador->direccion ?: '-' }}
                    </div>
                </div>

            </div>

        </div>

    </div>


    {{-- ================= INFORMACIÓN DEPORTIVA ================= --}}
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">

        <div class="bg-green-700 text-white px-5 py-3 font-bold">
            ⚽ Información deportiva
        </div>

        <div class="p-5">

            <div class="grid grid-cols-2 gap-x-8 gap-y-4">

                <div>
                    <small class="text-gray-500">Categoría</small>
                    <div class="font-semibold">
                        {{ optional($jugador->categoria)->nombre ?: '-' }}
                    </div>
                </div>

                <div>
                    <small class="text-gray-500">Equipo</small>
                    <div class="font-semibold">
                        {{ optional($jugador->equipo)->nombre ?: '-' }}
                    </div>
                </div>

                <div>
                    <small class="text-gray-500">Posición</small>
                    <div class="font-semibold">
                        {{ $jugador->posicion ?: '-' }}
                    </div>
                </div>

                <div>
                    <small class="text-gray-500">Pierna hábil</small>
                    <div class="font-semibold">
                        {{ $jugador->pierna_habil ?: '-' }}
                    </div>
                </div>

                <div>
                    <small class="text-gray-500">Estatura</small>
                    <div class="font-semibold">
                        {{ $jugador->estatura ? $jugador->estatura.' cm' : '-' }}
                    </div>
                </div>

                <div>
                    <small class="text-gray-500">Peso</small>
                    <div class="font-semibold">
                        {{ $jugador->peso ? $jugador->peso.' kg' : '-' }}
                    </div>
                </div>

            </div>

        </div>

    </div>

    {{-- ================= INFORMACIÓN MÉDICA ================= --}}
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">

        <div class="bg-red-700 text-white px-5 py-3 font-bold">
            ❤️ Información médica
        </div>

        <div class="p-5">

            <div class="grid grid-cols-2 gap-x-8 gap-y-4">

                <div>
                    <small class="text-gray-500">EPS</small>
                    <div class="font-semibold">
                        {{ $jugador->eps ?: '-' }}
                    </div>
                </div>

                <div>
                    <small class="text-gray-500">Tipo sangre</small>
                    <div class="font-semibold">
                        {{ $jugador->tipo_sangre ?: '-' }}
                    </div>
                </div>

                <div class="col-span-2">
                    <small class="text-gray-500">Alergias</small>
                    <div class="bg-gray-50 rounded-lg p-3 mt-1">
                        {{ $jugador->alergias ?: 'Sin información' }}
                    </div>
                </div>

                <div class="col-span-2">
                    <small class="text-gray-500">Observaciones</small>
                    <div class="bg-gray-50 rounded-lg p-3 mt-1">
                        {{ $jugador->observaciones_medicas ?: 'Sin información' }}
                    </div>
                </div>

            </div>

        </div>

    </div>

    {{-- ================= ACUDIENTE ================= --}}
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">

        <div class="bg-indigo-700 text-white px-5 py-3 font-bold">
            👨 Acudiente
        </div>

        <div class="p-5">

            <div class="grid grid-cols-2 gap-x-8 gap-y-4">

                <div>
                    <small class="text-gray-500">Nombre</small>
                    <div class="font-semibold">
                        {{ $jugador->acudiente ?: '-' }}
                    </div>
                </div>

                <div>
                    <small class="text-gray-500">Parentesco</small>
                    <div class="font-semibold">
                        {{ $jugador->parentesco ?: '-' }}
                    </div>
                </div>

                <div class="col-span-2">
                    <small class="text-gray-500">Teléfono</small>
                    <div class="font-semibold">
                        {{ $jugador->telefono_acudiente ?: '-' }}
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>

</div>

</div>

<div id="panel-medico" class="hidden">

    <div class="bg-white rounded-2xl shadow-lg">

        <div class="flex justify-between items-center p-6 border-b">

            <h2 class="text-2xl font-bold">
                ❤️ Historial Médico
            </h2>

            <a href="{{ route('historial-medico.create', $jugador) }}"
               class="bg-red-600 text-white px-4 py-2 rounded-lg">
                + Nuevo Registro
            </a>

        </div>

        {{-- aquí va la tabla del historial médico --}}

    </div>

</div>

    <button type="button"
        onclick="mostrarPanel('medico')"
        class="w-full bg-red-50 hover:bg-red-100 rounded-xl p-5 text-center">

    ❤️
    <div class="font-bold mt-2">Médico</div>

</button>

    <div class="overflow-x-auto">

        <table class="w-full">

            <thead class="bg-gray-100">

                <tr>

                    <th class="p-3 text-left">Fecha</th>

                    <th class="p-3 text-left">Tipo</th>

                    <th class="p-3 text-left">Diagnóstico</th>

                    <th class="p-3 text-left">Profesional</th>

                    <th class="p-3 text-center">Estado</th>

                </tr>

            </thead>

            <tbody>

            @forelse($historiales as $h)

                <tr class="border-b hover:bg-gray-50">

                    <td class="p-3">
                        {{ $h->fecha->format('d/m/Y') }}
                    </td>

                    <td class="p-3">
                        {{ $h->tipo }}
                    </td>

                    <td class="p-3">
                        {{ $h->diagnostico }}
                    </td>

                    <td class="p-3">
                        {{ $h->profesional }}
                    </td>

                    <td class="p-3 text-center">

                        @if($h->alta)

                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full">
                                Alta
                            </span>

                        @else

                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full">
                                En tratamiento
                            </span>

                        @endif

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="5" class="p-10 text-center text-gray-500">

                        No existen registros médicos.

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

</div>

<div id="panel-contabilidad" class="hidden"></div>

<div id="panel-estadisticas" class="hidden"></div>

<div id="panel-asistencia" class="hidden"></div>

<div id="panel-documentos" class="hidden"></div>

<div id="panel-observaciones" class="hidden"></div>




    {{-- BOTONES --}}
    <div class="flex justify-center gap-4 mt-8">

    

        <a href="{{ route('jugadores.edit', $jugador) }}"
           class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold px-8 py-3 rounded-xl shadow-lg transition">

            ✏️ Editar jugador

        </a>

        <a href="{{ route('jugadores.index') }}"
           class="bg-slate-700 hover:bg-slate-800 text-white font-bold px-8 py-3 rounded-xl shadow-lg transition">

            ← Volver

        </a>

    </div>

</div>

<script>

document.querySelectorAll('.menu-opcion').forEach(btn=>{

    btn.onclick=function(){

        document.querySelectorAll('.menu-opcion').forEach(x=>x.classList.remove('bg-blue-50'));

        this.classList.add('bg-blue-50');

        document.getElementById('contenido-general').innerHTML=
            "<h2 class='text-2xl font-bold'>"+this.innerText+"</h2><br>Este módulo se construirá aquí.";

    }

});

</script>


<script>

document.querySelectorAll('.menu-jugador').forEach(btn=>{

    btn.addEventListener('click',function(e){

        e.preventDefault();

        document.querySelectorAll('[id^="panel-"]').forEach(p=>{

            p.classList.add('hidden');

        });

        document.getElementById('panel-'+this.dataset.panel)
            .classList.remove('hidden');

    });

});

</script>

<script>

function mostrarPanel(panel){

    document.getElementById('panel-principal').classList.add('hidden');

    document.getElementById('panel-medico').classList.add('hidden');
    document.getElementById('panel-estadisticas').classList.add('hidden');
    document.getElementById('panel-finanzas').classList.add('hidden');
    document.getElementById('panel-asistencias').classList.add('hidden');

    document.getElementById('panel-'+panel).classList.remove('hidden');

}

</script>

@endsection
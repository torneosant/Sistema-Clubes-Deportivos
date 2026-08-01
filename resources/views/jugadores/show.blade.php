@extends('layouts.app')

@section('titulo','Ficha del Jugador')

@section('contenido')

<div class="max-w-7xl mx-auto p-6">

    {{-- CABECERA --}}
    <div class="bg-white rounded-3xl shadow-xl overflow-hidden mb-8">

        <div class="h-44 bg-gradient-to-r from-blue-700 via-slate-800 to-slate-900"></div>

        <div class="px-8 pb-8">

            <div class="-mt-20 flex items-center gap-6">

                @if($jugador->foto)
                    <img src="{{ asset('storage/'.$jugador->foto) }}"
                        class="w-40 h-40 rounded-full border-8 border-white object-cover shadow-xl">
                @else
                    <div class="w-40 h-40 rounded-full border-8 border-white bg-slate-200 flex items-center justify-center shadow-xl">

                        <span class="text-5xl font-bold text-slate-600">
                            {{ strtoupper(substr($jugador->nombres,0,1)) }}
                        </span>

                    </div>
                @endif

                <div class="flex-1">

                    <h1 class="text-4xl font-bold text-slate-800">
                        {{ $jugador->nombres }} {{ $jugador->apellidos }}
                    </h1>

                    <div class="flex gap-2 mt-4 flex-wrap">

                        <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full">
                            {{ optional($jugador->categoria)->nombre }}
                        </span>

                        <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full">
                            {{ optional($jugador->equipo)->nombre }}
                        </span>

                        <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full">
                            {{ $jugador->posicion }}
                        </span>

                        @if($jugador->activo)

                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full">
                                Activo
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="grid grid-cols-12 gap-6">

        {{-- MENU LATERAL --}}
        <div class="col-span-12 lg:col-span-3">

            <div class="bg-white rounded-2xl shadow-lg sticky top-5">

                <div class="bg-slate-800 text-white p-4 rounded-t-2xl font-bold">

                    Gestión del jugador

                </div>

                <div class="p-3 space-y-2">

                    <button onclick="mostrarPanel('info')" class="menu-btn w-full text-left p-3 rounded-lg hover:bg-blue-50">
                        👤 Información
                    </button>

                    <button onclick="mostrarPanel('medico')" class="menu-btn w-full text-left p-3 rounded-lg hover:bg-red-50">
                        ❤️ Médico
                    </button>

                    <button onclick="mostrarPanel('contabilidad')" class="menu-btn w-full text-left p-3 rounded-lg hover:bg-green-50">
                        💰 Contabilidad
                    </button>

                    <button onclick="mostrarPanel('estadisticas')" class="menu-btn w-full text-left p-3 rounded-lg hover:bg-yellow-50">
                        📊 Estadísticas
                    </button>

                    <button onclick="mostrarPanel('asistencia')" class="menu-btn w-full text-left p-3 rounded-lg hover:bg-indigo-50">
                        📅 Asistencia
                    </button>

                    <button onclick="mostrarPanel('documentos')" class="menu-btn w-full text-left p-3 rounded-lg hover:bg-gray-100">
                        📄 Documentos
                    </button>

                    <button onclick="mostrarPanel('observaciones')" class="menu-btn w-full text-left p-3 rounded-lg hover:bg-orange-50">
                        📝 Observaciones
                    </button>

                </div>

            </div>

        </div>

        {{-- PANEL DERECHO --}}
        <div class="col-span-12 lg:col-span-9">

            <div id="panel-info" class="panel-jugador space-y-6">

    {{-- DATOS PERSONALES --}}
    <div class="bg-white rounded-2xl shadow-lg">

        <div class="bg-slate-800 text-white px-5 py-3 rounded-t-2xl font-bold">
            👤 Datos personales
        </div>

        <div class="p-6 grid grid-cols-2 gap-5">

            <div>
                <small class="text-gray-500">Documento</small>
                <div class="font-semibold">
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
                <small class="text-gray-500">Teléfono</small>
                <div class="font-semibold">
                    {{ $jugador->telefono }}
                </div>
            </div>

            <div>
                <small class="text-gray-500">Correo</small>
                <div class="font-semibold">
                    {{ $jugador->email }}
                </div>
            </div>

            <div>
                <small class="text-gray-500">Ciudad</small>
                <div class="font-semibold">
                    {{ $jugador->ciudad }}
                </div>
            </div>

        </div>

    </div>


    {{-- INFORMACIÓN DEPORTIVA --}}
    <div class="bg-white rounded-2xl shadow-lg">

        <div class="bg-green-700 text-white px-5 py-3 rounded-t-2xl font-bold">
            ⚽ Información deportiva
        </div>

        <div class="p-6 grid grid-cols-2 gap-5">

            <div>
                <small class="text-gray-500">Equipo</small>
                <div class="font-semibold">
                    {{ optional($jugador->equipo)->nombre }}
                </div>
            </div>

            <div>
                <small class="text-gray-500">Categoría</small>
                <div class="font-semibold">
                    {{ optional($jugador->categoria)->nombre }}
                </div>
            </div>

            <div>
                <small class="text-gray-500">Posición</small>
                <div class="font-semibold">
                    {{ $jugador->posicion }}
                </div>
            </div>

            <div>
                <small class="text-gray-500">Pierna hábil</small>
                <div class="font-semibold">
                    {{ $jugador->pierna_habil }}
                </div>
            </div>

        </div>

    </div>


    {{-- INFORMACIÓN MÉDICA BÁSICA --}}
    <div class="bg-white rounded-2xl shadow-lg">

        <div class="bg-red-700 text-white px-5 py-3 rounded-t-2xl font-bold">
            ❤️ Información médica
        </div>

        <div class="p-6 grid grid-cols-2 gap-5">

            <div>

                <small class="text-gray-500">EPS</small>

                <div class="font-semibold">
                    {{ $jugador->eps ?: '-' }}
                </div>

            </div>

            <div>

                <small class="text-gray-500">Tipo de sangre</small>

                <div class="font-semibold">
                    {{ $jugador->tipo_sangre ?: '-' }}
                </div>

            </div>

            <div class="col-span-2">

                <small class="text-gray-500">Alergias</small>

                <div class="bg-gray-50 rounded-lg p-3">

                    {{ $jugador->alergias ?: 'Sin información' }}

                </div>

            </div>

        </div>

    </div>

</div>



            <div id="panel-medico" class="panel-jugador hidden">
            <div class="bg-white rounded-2xl shadow-lg">

    <div class="flex justify-between items-center p-5 border-b">

        <h2 class="text-2xl font-bold">
            ❤️ Historial Médico
        </h2>

        <a href="{{ route('historial-medico.create',$jugador) }}"
           class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">

            + Nuevo registro

        </a>

    </div>

    <div class="overflow-x-auto">

        <table class="w-full">

            <thead class="bg-gray-100">

                <tr>

                    <th class="p-3">Fecha</th>

                    <th class="p-3">Tipo</th>

                    <th class="p-3">Diagnóstico</th>

                    <th class="p-3">Estado</th>

                </tr>

            </thead>

            <tbody>

            @forelse($historiales as $h)

                <tr class="border-b">

                    <td class="p-3">
                        {{ \Carbon\Carbon::parse($h->fecha)->format('d/m/Y') }}
                    </td>

                    <td class="p-3">
                        {{ $h->tipo }}
                    </td>

                    <td class="p-3">
                        {{ $h->diagnostico }}
                    </td>

                    <td class="p-3">
                        {{ $h->estado }}
                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="4" class="text-center p-8 text-gray-500">

                        No hay registros médicos.

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

</div>  

                 <div id="panel-contabilidad" class="panel-jugador hidden">

<div class="bg-white rounded-2xl shadow-lg">

    <div class="bg-green-700 text-white px-5 py-3 rounded-t-2xl font-bold">
        💰 Estado financiero
    </div>

    <div class="p-6 grid grid-cols-3 gap-5">

        <div class="bg-gray-50 rounded-xl p-4">

            <small class="text-gray-500">Ingresos</small>

            <div class="text-2xl font-bold text-green-600">
                $ {{ number_format($ingresos,0,',','.') }}
            </div>

        </div>

        <div class="bg-gray-50 rounded-xl p-4">

            <small class="text-gray-500">Gastos</small>

            <div class="text-2xl font-bold text-red-600">
                $ {{ number_format($gastos,0,',','.') }}
            </div>

        </div>

        <div class="bg-gray-50 rounded-xl p-4">

            <small class="text-gray-500">Saldo</small>

            <div class="text-2xl font-bold text-blue-700">
                $ {{ number_format($saldo,0,',','.') }}
            </div>

        </div>

    </div>

    <div class="overflow-x-auto">

        <table class="w-full">

            <thead class="bg-gray-100">

                <tr>

                    <th class="p-3">Fecha</th>
                    <th class="p-3">Concepto</th>
                    <th class="p-3">Tipo</th>
                    <th class="p-3">Valor</th>

                </tr>

            </thead>

            <tbody>

            @forelse($movimientos as $m)

                <tr class="border-b">

                    <td class="p-3">
                        {{ \Carbon\Carbon::parse($m->fecha)->format('d/m/Y') }}
                    </td>

                    <td class="p-3">
                        {{ $m->concepto->nombre }}
                    </td>

                    <td class="p-3">
                        {{ $m->tipo }}
                    </td>

                    <td class="p-3">
                        $ {{ number_format($m->valor,0,',','.') }}
                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="4" class="text-center p-8 text-gray-500">

                        No existen movimientos contables.

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

</div>

            <div id="panel-estadisticas" class="panel-jugador hidden">

<div class="bg-white rounded-2xl shadow-lg">

    <div class="bg-indigo-700 text-white px-5 py-3 rounded-t-2xl font-bold">
        📊 Estadísticas Deportivas
    </div>

    <div class="p-6">

        <div class="grid grid-cols-4 gap-5 mb-8">

            <div class="bg-blue-50 rounded-xl p-5 text-center">
                <div class="text-gray-500 text-sm">Partidos</div>
                <div class="text-4xl font-bold text-blue-700">
                    {{ $partidosJugados }}
                </div>
            </div>

            <div class="bg-green-50 rounded-xl p-5 text-center">
                <div class="text-gray-500 text-sm">Minutos</div>
                <div class="text-4xl font-bold text-green-700">
                    {{ $minutosJugados }}
                </div>
            </div>

            <div class="bg-yellow-50 rounded-xl p-5 text-center">
                <div class="text-gray-500 text-sm">Goles</div>
                <div class="text-4xl font-bold text-yellow-600">
                    ⚽ {{ $goles }}
                </div>
            </div>

            <div class="bg-cyan-50 rounded-xl p-5 text-center">
                <div class="text-gray-500 text-sm">Asistencias</div>
                <div class="text-4xl font-bold text-cyan-700">
                    🎯 {{ $asistenciasDeGol }}
                </div>
            </div>

        </div>


        <div class="grid grid-cols-3 gap-5">

            <div class="bg-gray-50 rounded-xl p-5 text-center">

                <div class="text-gray-500 text-sm">
                    Amarillas
                </div>

                <div class="text-4xl font-bold text-yellow-500">
                    🟨 {{ $amarillas }}
                </div>

            </div>

            <div class="bg-gray-50 rounded-xl p-5 text-center">

                <div class="text-gray-500 text-sm">
                    Rojas
                </div>

                <div class="text-4xl font-bold text-red-600">
                    🟥 {{ $rojas }}
                </div>

            </div>

            <div class="bg-gray-50 rounded-xl p-5 text-center">

                <div class="text-gray-500 text-sm">
                    Figura del Partido
                </div>

                <div class="text-4xl font-bold text-indigo-700">
                    ⭐ {{ $figuras }}
                </div>

            </div>

        </div>

    </div>

<div class="mt-8">

    <h3 class="text-xl font-bold mb-4">
        📅 Historial de Partidos
    </h3>

    <div class="overflow-x-auto">

        <table class="w-full">

            <thead class="bg-gray-100">

                <tr>

                    <th class="p-3">Fecha</th>
                    <th class="p-3">Min</th>
                    <th class="p-3">⚽</th>
                    <th class="p-3">🎯</th>
                    <th class="p-3">🟨</th>
                    <th class="p-3">🟥</th>
                    <th class="p-3">⭐</th>

                </tr>

            </thead>

            <tbody>

            @forelse($detallePartidos as $p)

                <tr class="border-b hover:bg-gray-50">

                    <td class="p-3">
                        {{ \Carbon\Carbon::parse($p->partido->fecha)->format('d/m/Y') }}
                    </td>

                    <td class="text-center">
                        {{ $p->minutos }}
                    </td>

                    <td class="text-center">
                        {{ $p->goles }}
                    </td>

                    <td class="text-center">
                        {{ $p->asistencias }}
                    </td>

                    <td class="text-center">
                        {{ $p->amarillas }}
                    </td>

                    <td class="text-center">
                        {{ $p->rojas }}
                    </td>

                    <td class="text-center">
                        {{ $p->figura ? '⭐' : '-' }}
                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="7" class="text-center p-6 text-gray-500">
                        No existen estadísticas de partidos.
                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

</div>

</div>



            <div id="panel-asistencia" class="panel-jugador hidden">

    <div class="bg-white rounded-2xl shadow-lg">

        <div class="bg-blue-700 text-white px-5 py-3 rounded-t-2xl font-bold">
            📅 Historial de Asistencia
        </div>

        <div class="p-6 grid grid-cols-5 gap-4">

            <div class="bg-gray-50 rounded-xl p-4 text-center">
                <div class="text-sm text-gray-500">Registros</div>
                <div class="text-2xl font-bold">{{ $totalAsistencias }}</div>
            </div>

            <div class="bg-green-50 rounded-xl p-4 text-center">
                <div class="text-sm text-gray-500">Presentes</div>
                <div class="text-2xl font-bold text-green-600">{{ $presentes }}</div>
            </div>

            <div class="bg-red-50 rounded-xl p-4 text-center">
                <div class="text-sm text-gray-500">Ausentes</div>
                <div class="text-2xl font-bold text-red-600">{{ $ausentes }}</div>
            </div>

            <div class="bg-yellow-50 rounded-xl p-4 text-center">
                <div class="text-sm text-gray-500">Permisos</div>
                <div class="text-2xl font-bold text-yellow-600">{{ $permisos }}</div>
            </div>

            <div class="bg-cyan-50 rounded-xl p-4 text-center">
                <div class="text-sm text-gray-500">% Asistencia</div>
                <div class="text-2xl font-bold text-blue-700">{{ $porcentajeAsistencia }}%</div>
            </div>

        </div>

        <div class="overflow-x-auto">

            <table class="w-full">

                <thead class="bg-gray-100">

                    <tr>
                        <th class="p-3">Fecha</th>
                        <th class="p-3">Entrenamiento</th>
                        <th class="p-3">Estado</th>
                        <th class="p-3">Observación</th>
                    </tr>

                </thead>

                <tbody>

                @forelse($asistencias as $a)

                    <tr class="border-b">

                        <td class="p-3">
                            {{ \Carbon\Carbon::parse($a->entrenamiento->fecha)->format('d/m/Y') }}
                        </td>

                        <td class="p-3">

    <div class="font-semibold">
        {{ $a->entrenamiento->tipo }}
    </div>

    <div class="text-xs text-gray-500">
        {{ $a->entrenamiento->lugar }}
    </div>

</td>

                        <td class="p-3">

                            @if($a->estado=='Presente')
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full">
                                    {{ $a->estado }}
                                </span>

                            @elseif($a->estado=='Ausente')
                                <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full">
                                    {{ $a->estado }}
                                </span>

                            @elseif($a->estado=='Permiso')
                                <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full">
                                    {{ $a->estado }}
                                </span>

                            @else
                                <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full">
                                    {{ $a->estado }}
                                </span>
                            @endif

                        </td>

                        <td class="p-3">
                            {{ $a->observacion ?: '-' }}
                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="4" class="text-center p-8 text-gray-500">

                            No existen registros de asistencia.

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

            <div id="panel-documentos" class="panel-jugador hidden"></div>

            <div id="panel-observaciones" class="panel-jugador hidden"></div>

        </div>

    </div>

</div>

<script>

function mostrarPanel(panel){

    document.querySelectorAll('.panel-jugador').forEach(function(div){

        div.classList.add('hidden');

    });

    document.getElementById('panel-'+panel).classList.remove('hidden');

}

</script>

@endsection
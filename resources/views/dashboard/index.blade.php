@extends('layouts.app')

@section('titulo', 'Dashboard')

@section('contenido')

<x-page-header
    title="🏠 Panel de Control"
    subtitle="Bienvenido al sistema de Gestión del Club."
/>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Próximos entrenamientos --}}
    <x-card>

        <h2 class="text-xl font-bold mb-4">
            📅 Próximos entrenamientos
        </h2>

        @forelse($entrenamientos as $entrenamiento)

            <div class="border-b py-3">

               <div class="font-semibold">
    {{ $entrenamiento->equipo?->nombre ?? 'Entrenamiento' }}
</div>

<div class="text-sm text-gray-600">
    📅 {{ \Carbon\Carbon::parse($entrenamiento->fecha)->format('d/m/Y') }}
    @if($entrenamiento->hora_inicio)
        · ⏰ {{ \Carbon\Carbon::parse($entrenamiento->hora_inicio)->format('H:i') }}
    @endif
</div>

@if($entrenamiento->lugar)
    <div class="text-sm text-gray-500 mt-1">
        📍 {{ $entrenamiento->lugar }}
    </div>
@endif

            </div>

        @empty

            <p class="text-gray-500">
                No hay entrenamientos programados.
            </p>

        @endforelse

    </x-card>


    {{-- Próximos partidos --}}
    <x-card>

        <h2 class="text-xl font-bold mb-4">
            ⚽ Próximos partidos
        </h2>

        @forelse($partidos as $partido)

            <div class="border-b py-3">

               <div class="font-semibold">
    {{ $partido->equipo?->nombre ?? 'Partido' }}
    @if($partido->rival)
        vs {{ $partido->rival }}
    @endif
</div>

<div class="text-sm text-gray-600">
    📅 {{ \Carbon\Carbon::parse($partido->fecha)->format('d/m/Y') }}

    @if($partido->hora)
        · ⏰ {{ \Carbon\Carbon::parse($partido->hora)->format('H:i') }}
    @endif
</div>

@if($partido->lugar)
    <div class="text-sm text-gray-500 mt-1">
        📍 {{ $partido->lugar }}
    </div>
@endif

            </div>

        @empty

            <p class="text-gray-500">
                No hay partidos programados.
            </p>

        @endforelse

    </x-card>


    {{-- Noticias --}}
    {{-- Noticias --}}
<x-card>

    <div class="flex items-center justify-between mb-4">

        <h2 class="text-xl font-bold">
            📢 Noticias
        </h2>

        @if(auth()->user()->tienePermiso('noticias.ver'))
            <a href="{{ route('noticias.index') }}"
               class="text-sm text-blue-600 hover:text-blue-800">
                Ver todas →
            </a>
        @endif

    </div>

    @forelse($noticias as $noticia)

        <div class="border-b py-3 last:border-b-0">

            <div class="font-semibold">
                {{ $noticia->titulo }}
            </div>

            <div class="text-sm text-gray-500 mt-1">
                📅 {{ \Carbon\Carbon::parse($noticia->fecha_publicacion)->format('d/m/Y') }}
            </div>

            @if($noticia->contenido)
                <div class="text-sm text-gray-600 mt-2">
                    {{ \Illuminate\Support\Str::limit($noticia->contenido, 120) }}
                </div>
            @endif

        </div>

    @empty

        <p class="text-gray-500">
            No hay noticias publicadas.
        </p>

    @endforelse

</x-card>


    {{-- Cumpleaños --}}
    
<x-card>

    <h2 class="text-xl font-bold mb-4">
        🎂 Próximos cumpleaños
    </h2>

    @forelse($cumpleanios as $jugador)

        <div class="flex items-center justify-between border-b py-3">

            <div>
                <div class="font-semibold">
                    {{ $jugador->nombres }}
                    {{ $jugador->apellidos }}
                </div>

                <div class="text-sm text-gray-500">
                    {{ $jugador->fecha_nacimiento->format('d/m') }}
                </div>
            </div>

            <div class="text-2xl">
                🎂
            </div>

        </div>

    @empty

        <p class="text-gray-500">
            No hay cumpleaños próximos.
        </p>

    @endforelse

</x-card>
</div>

@endsection
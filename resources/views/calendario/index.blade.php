@extends('layouts.app')

@section('titulo','Calendario')

@section('contenido')

<div class="bg-white rounded-xl shadow p-6">

    <h1 class="text-3xl font-bold mb-6">

        📅 Calendario General

    </h1>

    <div id="calendar"></div>

    <hr class="my-8">

<h2 class="text-2xl font-bold mb-4">
    📌 Próximos Eventos
</h2>

<div class="space-y-3">

@foreach($proximosEventos as $evento)

<div class="border rounded-lg p-4 flex justify-between items-center">

    <div>

        <div class="font-semibold">
            {{ $evento['title'] }}
        </div>

        <div class="text-gray-500 text-sm">
            {{ \Carbon\Carbon::parse($evento['start'])->format('d/m/Y') }}
        </div>

    </div>

</div>

@endforeach

</div>

</div>


<script>
window.eventosCalendario = @json($eventos);
</script>

@endsection
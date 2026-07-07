@extends('layouts.app')

@section('titulo','Dashboard')

@section('contenido')

<div class="grid grid-cols-4 gap-6">

    <div class="bg-white rounded-xl shadow p-6">
        <div class="text-5xl">🏟️</div>
        <h2 class="font-bold mt-3">Mi Club</h2>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <div class="text-5xl">👥</div>
        <h2 class="font-bold mt-3">Jugadores</h2>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <div class="text-5xl">⚽</div>
        <h2 class="font-bold mt-3">Equipos</h2>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <div class="text-5xl">💰</div>
        <h2 class="font-bold mt-3">Pagos</h2>
    </div>

</div>

@endsection
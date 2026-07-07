<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión Clubes</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">

<div class="flex min-h-screen">

    <!-- Menú lateral -->
    <aside class="w-64 bg-slate-900 text-white">

        <div class="p-6 text-2xl font-bold border-b border-slate-700">
            ⚽ Gestión Clubes
        </div>

        <nav class="p-4 space-y-2">

            <a href="{{ route('dashboard') }}" class="block px-4 py-2 rounded hover:bg-slate-700">
                🏠 Dashboard
            </a>

            <a href="{{ route('club.index') }}" class="block px-4 py-2 rounded hover:bg-slate-700">
                🏟️ Mi Club
            </a>
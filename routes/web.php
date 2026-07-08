<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\JugadorController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\EquipoController;

Route::get('/', function () {
    return view('inicio');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard');

    Route::get('/club', [ClubController::class, 'index'])->name('club.index');
    Route::post('/club', [ClubController::class, 'store'])->name('club.store');

    // Jugadores
    Route::resource('jugadores', JugadorController::class)
        ->parameters([
            'jugadores' => 'jugador'
        ]);

    Route::patch('/jugadores/{jugador}/estado', [JugadorController::class, 'cambiarEstado'])
        ->name('jugadores.estado');

    // Categorías
    Route::resource('categorias', CategoriaController::class)
        ->parameters([
            'categorias' => 'categoria'
        ]);

    Route::patch('/categorias/{categoria}/estado', [CategoriaController::class, 'cambiarEstado'])
        ->name('categorias.estado');

        // Equipos
Route::resource('equipos', EquipoController::class)
    ->parameters([
        'equipos' => 'equipo'
    ]);

Route::patch('/equipos/{equipo}/estado', [EquipoController::class, 'cambiarEstado'])
    ->name('equipos.estado');

});

require __DIR__.'/auth.php';
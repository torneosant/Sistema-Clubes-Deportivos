<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\JugadorController;

Route::get('/', function () {
    return view('inicio');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard');
    Route::get('/club', [App\Http\Controllers\ClubController::class, 'index'])->name('club.index');
    Route::post('/club', [App\Http\Controllers\ClubController::class, 'store'])->name('club.store');
// Jugadores
    Route::resource('jugadores', JugadorController::class)
    ->parameters([
        'jugadores' => 'jugador'
    ]);
    // Cambiar estado del jugador
Route::patch('/jugadores/{jugador}/estado', [JugadorController::class, 'cambiarEstado'])
    ->name('jugadores.estado');     
});

require __DIR__.'/auth.php';
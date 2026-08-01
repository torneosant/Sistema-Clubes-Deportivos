<?php

namespace App\Http\Controllers;

use App\Models\Jugador;
use App\Models\Equipo;
use App\Models\Categoria;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
 public function index()
{
    $totalJugadores = Jugador::count();
    $totalEquipos = Equipo::count();
    $totalCategorias = Categoria::count();
    $totalActivos = Jugador::where('activo', true)->count();

    

    return view('dashboard.index', compact(
        'totalJugadores',
        'totalEquipos',
        'totalCategorias',
        'totalActivos'
    ));
}
}   
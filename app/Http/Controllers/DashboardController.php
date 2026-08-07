<?php

namespace App\Http\Controllers;

use App\Models\Jugador;
use App\Models\Equipo;
use App\Models\Categoria;
use Illuminate\Http\Request;
use App\Models\Entrenamiento;
use App\Models\Partido;
use App\Models\Noticia;

class DashboardController extends Controller
{
    public function index()
    {
        $totalJugadores = Jugador::count();
        $totalEquipos = Equipo::count();
        $totalCategorias = Categoria::count();
        $totalActivos = Jugador::where('activo', true)->count();

        // Próximos entrenamientos
        $entrenamientos = Entrenamiento::with([
            'equipo',
            'entrenador'
        ])
        ->whereDate('fecha', '>=', now())
        ->orderBy('fecha')
        ->orderBy('hora_inicio')
        ->take(5)
        ->get();

        // Próximos partidos
        $partidos = Partido::with([
            'equipo',
            'categoria'
        ])
        ->whereDate('fecha', '>=', now())
        ->orderBy('fecha')
        ->orderBy('hora')
        ->take(5)
        ->get();

        // Próximos cumpleaños
        $mesActual = now()->month;
        $mesSiguiente = now()->copy()->addMonth()->month;

        $cumpleanios = Jugador::whereNotNull('fecha_nacimiento')
            ->where('activo', true)
            ->get()
            ->filter(function ($jugador) use ($mesActual, $mesSiguiente) {

                $mes = $jugador->fecha_nacimiento->month;

                return $mes == $mesActual || $mes == $mesSiguiente;
            })
            ->sortBy(function ($jugador) use ($mesActual) {

                $mes = $jugador->fecha_nacimiento->month;
                $dia = $jugador->fecha_nacimiento->day;

                $ordenMes = $mes == $mesActual ? 0 : 1;

                return sprintf('%d-%02d', $ordenMes, $dia);
            })
            ->values()
            ->take(5);

        // Noticias publicadas
        $noticias = Noticia::where('club_id', 1)
            ->where('publicada', true)
            ->orderByDesc('fecha_publicacion')
            ->orderByDesc('id')
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'totalJugadores',
            'totalEquipos',
            'totalCategorias',
            'totalActivos',
            'entrenamientos',
            'partidos',
            'cumpleanios',
            'noticias'
        ));
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Entrenamiento;
use App\Models\Partido;

class CalendarioController extends Controller
{
 public function index()
{
    $clubId = auth()->user()->club_id;

    $eventos = [];


    // ENTRENAMIENTOS
    foreach (
    Entrenamiento::where('club_id', $clubId)
        ->with(['equipo', 'entrenador'])
        ->get() as $e
) {

        $eventos[] = [

            'title' => '🏃 '.$e->equipo->nombre,

            'start' => $e->fecha,

            'color' => '#16a34a',

            'url' => route('asistencias.create', $e),

            'extendedProps' => [

                'tipo' => 'Entrenamiento',

                'equipo' => $e->equipo->nombre,

                'lugar' => $e->lugar,

                'hora' => $e->hora_inicio,

                'entrenador' => $e->entrenador->nombres ?? '',

            ]

        ];

    }

    // PARTIDOS
    foreach (
    Partido::where('club_id', $clubId)
        ->with(['equipo', 'categoria'])
        ->get() as $p
) {

        $eventos[] = [

            'title' => '⚽ '.$p->equipo->nombre.' vs '.$p->rival,

            'start' => $p->fecha,

            'color' => '#2563eb',

            'url' => $p->estado == 'Jugado'
                ? route('partidos.estadisticas', $p)
                : route('partidos.resultado', $p),

            'extendedProps' => [

                'tipo' => 'Partido',

                'equipo' => $p->equipo->nombre,

                'categoria' => $p->categoria->nombre,

                'rival' => $p->rival,

                'competencia' => $p->competencia,

                'hora' => $p->hora,

                'lugar' => $p->lugar,

                'estado' => $p->estado,

            ]

        ];

    }

    $proximosEventos = collect($eventos)
    ->sortBy('start')
    ->take(10);

    return view(
    'calendario.index',
    compact(
        'eventos',
        'proximosEventos'
    )
);
}   

        
    }
    
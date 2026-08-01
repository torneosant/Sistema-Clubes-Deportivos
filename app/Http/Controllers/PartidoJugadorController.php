<?php

namespace App\Http\Controllers;

use App\Models\Partido;
use App\Models\Jugador;
use Illuminate\Http\Request;
use App\Models\PartidoJugador;

class PartidoJugadorController extends Controller
{
    

    public function create(Partido $partido)
    {
        $jugadores = Jugador::where('equipo_id', $partido->equipo_id)
    ->where('categoria_id', $partido->categoria_id)
    ->where('activo', 1)
    ->orderBy('apellidos')
    ->orderBy('nombres')
    ->get();

        $estadisticas = PartidoJugador::where('partido_id', $partido->id)
            ->get()
            ->keyBy('jugador_id');

            
        return view(
            'partidos.estadisticas',
            compact(
                'partido',
                'jugadores',
                'estadisticas'
            )
        );
    }
   public function store(Request $request, Partido $partido)
{
    
    foreach ($request->goles as $jugadorId => $goles) {

        \App\Models\PartidoJugador::updateOrCreate(

            [
                'partido_id' => $partido->id,
                'jugador_id' => $jugadorId,
            ],

            [
               'participacion' => $request->participacion[$jugadorId] ?? 'No jugó',

                'minutos'      => $request->minutos[$jugadorId] ?? 0,

                'goles'        => $request->goles[$jugadorId] ?? 0,

                'asistencias'  => $request->asistencias[$jugadorId] ?? 0,

                'amarillas'    => $request->amarillas[$jugadorId] ?? 0,

                'rojas'        => $request->rojas[$jugadorId] ?? 0,

                'figura'       => isset($request->figura[$jugadorId]),

            ]

        );

    }

    return redirect()
        ->route('partidos.estadisticas', $partido)
        ->with('success', 'Estadísticas guardadas correctamente.');
}
}
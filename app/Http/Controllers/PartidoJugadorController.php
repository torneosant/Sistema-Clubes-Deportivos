<?php

namespace App\Http\Controllers;

use App\Models\Partido;
use App\Models\Jugador;
use App\Models\PartidoJugador;
use App\Models\Competencia;
use Illuminate\Http\Request;

class PartidoJugadorController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Mostrar estadísticas del partido
    |--------------------------------------------------------------------------
    */

    public function create(Partido $partido)
    {
        /*
        |--------------------------------------------------------------------------
        | Seguridad
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $partido->club_id === auth()->user()->club_id,
            403
        );


        /*
        |--------------------------------------------------------------------------
        | Obtener jugadoras
        |--------------------------------------------------------------------------
        |
        | Si el partido pertenece a una competencia:
        | usamos las jugadoras inscritas en la planilla.
        |
        | Si es amistoso:
        | usamos las jugadoras del equipo y categoría.
        |
        */

        if ($partido->competencia_id) {

            /*
            |--------------------------------------------------------------------------
            | Competencia
            |--------------------------------------------------------------------------
            */

            $competencia = Competencia::findOrFail(
                $partido->competencia_id
            );

            $jugadores = $competencia
                ->jugadores()
                ->where(
                    'jugadores.club_id',
                    auth()->user()->club_id
                )
                ->where(
                    'jugadores.activo',
                    1
                )
                ->orderBy('apellidos')
                ->orderBy('nombres')
                ->get();

        } else {

            /*
            |--------------------------------------------------------------------------
            | Amistoso
            |--------------------------------------------------------------------------
            */

            $jugadores = Jugador::where(
                'club_id',
                auth()->user()->club_id
            )
                ->where(
                    'equipo_id',
                    $partido->equipo_id
                )
                ->where(
                    'categoria_id',
                    $partido->categoria_id
                )
                ->where(
                    'activo',
                    1
                )
                ->orderBy('apellidos')
                ->orderBy('nombres')
                ->get();
        }


        /*
        |--------------------------------------------------------------------------
        | Estadísticas ya registradas
        |--------------------------------------------------------------------------
        */

        $estadisticas = PartidoJugador::where(
            'partido_id',
            $partido->id
        )
            ->get()
            ->keyBy('jugador_id');


        /*
        |--------------------------------------------------------------------------
        | Vista
        |--------------------------------------------------------------------------
        */

        return view(
            'partidos.estadisticas',
            compact(
                'partido',
                'jugadores',
                'estadisticas'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Guardar estadísticas
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        Partido $partido
    ) {

        /*
        |--------------------------------------------------------------------------
        | Seguridad
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $partido->club_id === auth()->user()->club_id,
            403
        );


        /*
        |--------------------------------------------------------------------------
        | Obtener jugadoras válidas
        |--------------------------------------------------------------------------
        */

        if ($partido->competencia_id) {

            /*
            |--------------------------------------------------------------------------
            | Jugadoras inscritas en la competencia
            |--------------------------------------------------------------------------
            */

            $competencia = Competencia::findOrFail(
                $partido->competencia_id
            );

            $jugadoresValidos = $competencia
                ->jugadores()
                ->where(
                    'jugadores.club_id',
                    auth()->user()->club_id
                )
                ->where(
                    'jugadores.activo',
                    1
                )
                ->pluck('jugadores.id');

        } else {

            /*
            |--------------------------------------------------------------------------
            | Jugadoras del equipo para amistoso
            |--------------------------------------------------------------------------
            */

            $jugadoresValidos = Jugador::where(
                'club_id',
                auth()->user()->club_id
            )
                ->where(
                    'equipo_id',
                    $partido->equipo_id
                )
                ->where(
                    'categoria_id',
                    $partido->categoria_id
                )
                ->where(
                    'activo',
                    1
                )
                ->pluck('id');
        }


        /*
        |--------------------------------------------------------------------------
        | Guardar cada jugadora
        |--------------------------------------------------------------------------
        */

        foreach ($jugadoresValidos as $jugadorId) {

            /*
            |--------------------------------------------------------------------------
            | ¿Fue titular?
            |--------------------------------------------------------------------------
            */

            $titular =
                isset($request->titular[$jugadorId])
                && $request->titular[$jugadorId] == 1;


            /*
            |--------------------------------------------------------------------------
            | Participación enviada por el formulario
            |--------------------------------------------------------------------------
            */

            $participacion =
                $request->participacion[$jugadorId]
                ?? 'No jugó';


            /*
            |--------------------------------------------------------------------------
            | TITULAR
            |--------------------------------------------------------------------------
            |
            | El campo titular manda.
            |
            | Como la base de datos usa ENUM, guardamos "Titular"
            | en participación.
            |
            */

            if ($titular) {

                $participacion = 'Titular';

            }


            /*
            |--------------------------------------------------------------------------
            | Validar participación
            |--------------------------------------------------------------------------
            */

            if (
                !in_array(
                    $participacion,
                    [
                        'Titular',
                        'Suplente',
                        'No jugó',
                    ],
                    true
                )
            ) {

                $participacion = 'No jugó';
            }


            /*
            |--------------------------------------------------------------------------
            | NO JUGÓ
            |--------------------------------------------------------------------------
            |
            | Si no fue titular y no fue suplente:
            |
            | Minutos = 0
            | Goles = 0
            | Asistencias = 0
            | Amarillas = 0
            | Rojas = 0
            | Figura = No
            |
            */

            if ($participacion === 'No jugó') {

                $titular = false;

                $minutos = 0;

                $goles = 0;

                $asistencias = 0;

                $amarillas = 0;

                $rojas = 0;

                $figura = false;

            } else {

                /*
                |--------------------------------------------------------------------------
                | TITULAR O SUPLENTE
                |--------------------------------------------------------------------------
                */

                $minutos = max(
                    0,
                    (int) (
                        $request->minutos[$jugadorId]
                        ?? 0
                    )
                );

                $goles = max(
                    0,
                    (int) (
                        $request->goles[$jugadorId]
                        ?? 0
                    )
                );

                $asistencias = max(
                    0,
                    (int) (
                        $request->asistencias[$jugadorId]
                        ?? 0
                    )
                );

                $amarillas = max(
                    0,
                    (int) (
                        $request->amarillas[$jugadorId]
                        ?? 0
                    )
                );

                $rojas = max(
                    0,
                    (int) (
                        $request->rojas[$jugadorId]
                        ?? 0
                    )
                );

                $figura =
                    isset(
                        $request->figura[$jugadorId]
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | Guardar / actualizar
            |--------------------------------------------------------------------------
            */

            PartidoJugador::updateOrCreate(

                [
                    'partido_id' => $partido->id,
                    'jugador_id' => $jugadorId,
                ],

                [

                    'titular' =>
                        $titular,

                    'participacion' =>
                        $participacion,

                    'minutos' =>
                        $minutos,

                    'goles' =>
                        $goles,

                    'asistencias' =>
                        $asistencias,

                    'amarillas' =>
                        $amarillas,

                    'rojas' =>
                        $rojas,

                    'figura' =>
                        $figura,

                ]

            );
        }


        /*
        |--------------------------------------------------------------------------
        | Regresar
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'partidos.estadisticas',
                $partido
            )
            ->with(
                'success',
                'Estadísticas guardadas correctamente.'
            );
    }
}
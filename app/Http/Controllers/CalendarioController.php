<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use App\Models\Entrenamiento;
use App\Models\Partido;
use App\Models\Jugador;
use App\Models\Evento;
use App\Models\EventoOcurrencia;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CalendarioController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | CALENDARIO
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $clubId = auth()->user()->club_id;


        /*
        |--------------------------------------------------------------------------
        | CONFIGURACIÓN DEL CLUB
        |--------------------------------------------------------------------------
        */

        $configuracion = Configuracion::find($clubId);


        /*
        |--------------------------------------------------------------------------
        | AÑO DE TRABAJO
        |--------------------------------------------------------------------------
        */

        $anio = session(
            'anio_trabajo',
            $configuracion?->anio ?? date('Y')
        );


        /*
        |--------------------------------------------------------------------------
        | PERMISOS
        |--------------------------------------------------------------------------
        */

        $puedeConfigurarCalendario =
            auth()->user()->tienePermiso(
                'configuracion.editar'
            );


        /*
        |--------------------------------------------------------------------------
        | VISIBILIDAD
        |--------------------------------------------------------------------------
        |
        | Si todavía no existen estos valores en la configuración,
        | mostramos todo por defecto.
        |
        */

        $mostrarPartidos =
            $configuracion?->calendario_partidos ?? true;

        $mostrarEntrenamientos =
            $configuracion?->calendario_entrenamientos ?? true;

        $mostrarCumpleanos =
            $configuracion?->calendario_cumpleanos ?? true;

        $mostrarEventos =
            $configuracion?->calendario_eventos ?? true;


        /*
        |--------------------------------------------------------------------------
        | EVENTOS DEL CALENDARIO
        |--------------------------------------------------------------------------
        */

        $eventos = collect();


        /*
        |--------------------------------------------------------------------------
        | PARTIDOS
        |--------------------------------------------------------------------------
        */

        if ($mostrarPartidos) {

            $partidos = Partido::where(
                'club_id',
                $clubId
            )
                ->whereYear(
                    'fecha',
                    $anio
                )
                ->with([
                    'equipo',
                    'categoria'
                ])
                ->orderBy('fecha')
                ->orderBy('hora')
                ->get();


            foreach ($partidos as $p) {

                /*
                | Evitamos errores si por alguna razón
                | el equipo fue eliminado.
                */

                $nombreEquipo =
                    $p->equipo?->nombre
                    ?? 'Equipo';


                $nombreCategoria =
                    $p->categoria?->nombre
                    ?? '';


                $titulo =
                    '⚽ ' .
                    $nombreEquipo;


                if ($p->rival) {

                    $titulo .=
                        ' vs ' .
                        $p->rival;
                }


                $eventos->push([

                    'title' =>
                        $titulo,

                    'start' =>
                        $p->fecha,

                    'color' =>
                        '#2563eb',

                    'url' =>
                        $p->estado === 'Jugado'
                            ? route(
                                'partidos.estadisticas',
                                $p
                            )
                            : route(
                                'partidos.resultado',
                                $p
                            ),

                    'extendedProps' => [

                        'tipo' =>
                            'Partido',

                        'equipo' =>
                            $nombreEquipo,

                        'categoria' =>
                            $nombreCategoria,

                        'rival' =>
                            $p->rival,

                        'competencia' =>
                            $p->competencia,

                        'hora' =>
                            $p->hora,

                        'lugar' =>
                            $p->lugar,

                        'estado' =>
                            $p->estado,

                    ],

                ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | ENTRENAMIENTOS
        |--------------------------------------------------------------------------
        */

        if ($mostrarEntrenamientos) {

            $entrenamientos =
                Entrenamiento::where(
                    'club_id',
                    $clubId
                )
                    ->whereYear(
                        'fecha',
                        $anio
                    )
                    ->with([
                        'equipo',
                        'entrenador'
                    ])
                    ->orderBy('fecha')
                    ->orderBy('hora_inicio')
                    ->get();


            foreach ($entrenamientos as $e) {

                $nombreEquipo =
                    $e->equipo?->nombre
                    ?? 'Entrenamiento';


                $eventos->push([

                    'title' =>
                        '🏃 ' .
                        $nombreEquipo,

                    'start' =>
                        $e->fecha,

                    'color' =>
                        '#16a34a',

                    'url' =>
                        route(
                            'asistencias.create',
                            $e
                        ),

                    'extendedProps' => [

                        'tipo' =>
                            'Entrenamiento',

                        'equipo' =>
                            $nombreEquipo,

                        'lugar' =>
                            $e->lugar,

                        'hora' =>
                            $e->hora_inicio,

                        'entrenador' =>
                            $e->entrenador?->nombres
                            ?? '',

                    ],

                ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | EVENTOS GENERALES
        |--------------------------------------------------------------------------
        */

        if ($mostrarEventos) {

            /*
            | Traemos únicamente eventos activos
            | pertenecientes al club.
            */

            $eventosGenerales =
                Evento::where(
                    'club_id',
                    $clubId
                )
                    ->where(
                        'activo',
                        true
                    )
                    ->with('ocurrencias')
                    ->get();


            foreach (
                $eventosGenerales
                as $evento
            ) {

                /*
                |--------------------------------------------------------------------------
                | OCURRENCIAS
                |--------------------------------------------------------------------------
                */

                foreach (
                    $evento->ocurrencias
                    as $ocurrencia
                ) {

                    /*
                    | Las ocurrencias canceladas
                    | no aparecen en el calendario.
                    */

                    if (
                        $ocurrencia->cancelada
                    ) {
                        continue;
                    }


                    /*
                    | Solo mostramos ocurrencias
                    | correspondientes al año de trabajo.
                    */

                    if (
                        $ocurrencia->fecha->year
                        != $anio
                    ) {
                        continue;
                    }


                    $eventos->push([

                        'title' =>
                            '📌 ' .
                            $evento->titulo,

                        'start' =>
                            $ocurrencia->fecha
                                ->toDateString(),

                        'color' =>
                            '#f97316',

                        'extendedProps' => [

                            'tipo' =>
                                'Evento',

                            'evento_id' =>
                                $evento->id,

                            'ocurrencia_id' =>
                                $ocurrencia->id,

                            'titulo' =>
                                $evento->titulo,

                            'descripcion' =>
                                $evento->descripcion,

                            'hora' =>
                                $ocurrencia->hora,

                            'lugar' =>
                                $ocurrencia->lugar,

                            'tipo_evento' =>
                                $evento->tipo,

                            'recurrencia' =>
                                $evento->recurrencia,

                        ],

                    ]);
                }
            }
        }


        /*
        |--------------------------------------------------------------------------
        | CUMPLEAÑOS
        |--------------------------------------------------------------------------
        |
        | Los cumpleaños se muestran en el año de trabajo.
        |
        */

        if ($mostrarCumpleanos) {

            $jugadores =
                Jugador::where(
                    'club_id',
                    $clubId
                )
                    ->where(
                        'activo',
                        true
                    )
                    ->whereNotNull(
                        'fecha_nacimiento'
                    )
                    ->get();


            foreach ($jugadores as $jugador) {

                $fechaNacimiento =
                    Carbon::parse(
                        $jugador->fecha_nacimiento
                    );


                /*
                | Creamos el cumpleaños
                | dentro del año de trabajo.
                */

                $fechaCumpleanos =
                    Carbon::create(
                        $anio,
                        $fechaNacimiento->month,
                        $fechaNacimiento->day
                    );


                $nombre =
                    trim(
                        $jugador->nombres .
                        ' ' .
                        $jugador->apellidos
                    );


                $eventos->push([

                    'title' =>
                        '🎂 Cumpleaños: ' .
                        $nombre,

                    'start' =>
                        $fechaCumpleanos
                            ->toDateString(),

                    'color' =>
                        '#9333ea',

                    'extendedProps' => [

                        'tipo' =>
                            'Cumpleaños',

                        'jugador' =>
                            $nombre,

                    ],

                ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | ORDENAR CALENDARIO
        |--------------------------------------------------------------------------
        */

        $eventos =
            $eventos
                ->sortBy(function ($evento) {

                    return $evento['start'];
                })
                ->values()
                ->all();


        /*
        |--------------------------------------------------------------------------
        | PRÓXIMOS EVENTOS
        |--------------------------------------------------------------------------
        |
        | IMPORTANTE:
        |
        | Aquí NO queremos mostrar eventos históricos.
        |
        | Tampoco queremos mostrar partidos ya jugados.
        |
        */

        $hoy =
            Carbon::create(
                $anio,
                now()->month,
                now()->day
            );


        /*
        | Si estamos viendo un año diferente
        | al actual, usamos el inicio de ese año
        | para permitir revisar sus próximos eventos.
        */

        if ((int) $anio !== (int) now()->year) {

            $hoy =
                Carbon::create(
                    $anio,
                    1,
                    1
                );
        }


        $proximosEventos =
            collect($eventos)
                ->filter(function ($evento) use ($hoy) {

                    $fecha =
                        Carbon::parse(
                            $evento['start']
                        );


                    /*
                    | No mostrar eventos anteriores.
                    */

                    if (
                        $fecha->lt($hoy)
                    ) {
                        return false;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | PARTIDOS JUGADOS
                    |--------------------------------------------------------------------------
                    */

                    if (
                        ($evento['extendedProps']['tipo'] ?? '')
                        === 'Partido'
                    ) {

                        if (
                            ($evento['extendedProps']['estado'] ?? '')
                            === 'Jugado'
                        ) {

                            return false;
                        }
                    }


                    return true;
                })
                ->sortBy(function ($evento) {

                    return $evento['start'];
                })
                ->take(10)
                ->values();


        /*
        |--------------------------------------------------------------------------
        | VISTA
        |--------------------------------------------------------------------------
        */

        return view(
            'calendario.index',
            compact(
                'eventos',
                'proximosEventos',
                'anio',
                'configuracion',
                'puedeConfigurarCalendario',
                'mostrarPartidos',
                'mostrarEntrenamientos',
                'mostrarCumpleanos',
                'mostrarEventos'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ACTUALIZAR CONFIGURACIÓN
    |--------------------------------------------------------------------------
    */

    public function updateConfiguracion(
        Request $request
    ) {

        $clubId =
            auth()->user()->club_id;


        $configuracion =
            Configuracion::find($clubId);


        if (!$configuracion) {

            abort(404);
        }


        /*
        |--------------------------------------------------------------------------
        | CHECKBOXES
        |--------------------------------------------------------------------------
        |
        | Si el checkbox no viene en el formulario,
        | significa que está desactivado.
        |
        */

        $configuracion->calendario_partidos =
            $request->boolean(
                'calendario_partidos'
            );


        $configuracion->calendario_entrenamientos =
            $request->boolean(
                'calendario_entrenamientos'
            );


        $configuracion->calendario_cumpleanos =
            $request->boolean(
                'calendario_cumpleanos'
            );


        $configuracion->calendario_eventos =
            $request->boolean(
                'calendario_eventos'
            );


        $configuracion->save();


        return back()->with(
            'success',
            'Configuración del calendario actualizada correctamente.'
        );
    }
}
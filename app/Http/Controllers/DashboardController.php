<?php

namespace App\Http\Controllers;

use App\Models\Jugador;
use App\Models\Equipo;
use App\Models\Categoria;
use App\Models\Entrenador;
use App\Models\Entrenamiento;
use App\Models\Partido;
use App\Models\Noticia;
use App\Models\CargoJugador;
use App\Models\BecaJugador;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $usuario = auth()->user();
        $clubId = $usuario->club_id;

        /*
        |--------------------------------------------------------------------------
        | AÑO DE TRABAJO
        |--------------------------------------------------------------------------
        |
        | El año se maneja desde el selector global del sistema.
        | Intentamos tomarlo de la configuración ya cargada por el sistema.
        |
        */

        $anio = session('anio_trabajo', now()->year);

        /*
        |--------------------------------------------------------------------------
        | IDENTIFICAR PERFIL
        |--------------------------------------------------------------------------
        */

        $esDeportista = !empty($usuario->jugador_id);

        $esEntrenador = !empty($usuario->entrenador_id);

        /*
        |--------------------------------------------------------------------------
        | VARIABLES GENERALES
        |--------------------------------------------------------------------------
        */

        $totalJugadores = 0;
        $totalEquipos = 0;
        $totalCategorias = 0;
        $totalActivos = 0;
        $totalEntrenadores = 0;

        $totalPendiente = 0;
        $totalObligacionesPendientes = 0;

        $entrenamientos = collect();
        $partidos = collect();
        $noticias = collect();
        $cumpleanios = collect();

        $misEquipos = collect();

        $miJugador = null;
        $misCargos = collect();
        $miBeca = null;

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD ADMINISTRATIVO / GENERAL
        |--------------------------------------------------------------------------
        */

        if (!$esEntrenador && !$esDeportista) {

            /*
            | Jugadores
            */

            $totalJugadores = Jugador::where('club_id', $clubId)
                ->count();

            $totalActivos = Jugador::where('club_id', $clubId)
                ->where('activo', true)
                ->count();


            /*
            | Equipos
            */

            $totalEquipos = Equipo::where('club_id', $clubId)
                ->where('activo', true)
                ->count();


            /*
            | Categorías
            */

            $totalCategorias = Categoria::where('club_id', $clubId)
                ->where('activo', true)
                ->count();


            /*
            | Entrenadores
            */

            $totalEntrenadores = Entrenador::where('club_id', $clubId)
                ->where('activo', true)
                ->count();


            /*
            | Pendientes de pago
            */

            $cargosPendientes = CargoJugador::where('club_id', $clubId)
                ->whereYear('fecha', $anio)
                ->whereNotIn('estado', [
                    'Pagado',
                    'Exonerado',
                    'Anulado'
                ])
                ->get();

            $totalObligacionesPendientes =
                $cargosPendientes->count();

            $totalPendiente =
                $cargosPendientes->sum(function ($cargo) {

                    $valor = (float) ($cargo->valor ?? 0);

                    $pagado = (float) ($cargo->valor_pagado ?? 0);

                    return max(0, $valor - $pagado);
                });


            /*
            | Próximos entrenamientos
            */

            $entrenamientos = Entrenamiento::with([
                    'equipo',
                    'entrenador'
                ])
                ->where('club_id', $clubId)
                ->whereYear('fecha', $anio)
                ->whereDate('fecha', '>=', now())
                ->orderBy('fecha')
                ->orderBy('hora_inicio')
                ->take(5)
                ->get();


            /*
            | Próximos partidos
            */

            $partidos = Partido::with([
                    'equipo',
                    'categoria'
                ])
                ->where('club_id', $clubId)
                ->whereYear('fecha', $anio)
                ->whereDate('fecha', '>=', now())
                ->orderBy('fecha')
                ->orderBy('hora')
                ->take(5)
                ->get();
        }


        /*
        |--------------------------------------------------------------------------
        | DASHBOARD ENTRENADOR
        |--------------------------------------------------------------------------
        */

        if ($esEntrenador) {

            /*
            | Equipos asignados al entrenador
            */

            $entrenador = Entrenador::with('equipos')
                ->where('club_id', $clubId)
                ->find($usuario->entrenador_id);

            if ($entrenador) {

                $misEquipos = $entrenador->equipos()
                    ->where('equipos.club_id', $clubId)
                    ->where('equipos.activo', true)
                    ->orderBy('nombre')
                    ->get();

                $idsEquipos = $misEquipos
                    ->pluck('id');


                /*
                | Entrenamientos de mis equipos
                */

                $entrenamientos = Entrenamiento::with([
                        'equipo',
                        'entrenador'
                    ])
                    ->where('club_id', $clubId)
                    ->whereYear('fecha', $anio)
                    ->whereIn('equipo_id', $idsEquipos)
                    ->whereDate('fecha', '>=', now())
                    ->orderBy('fecha')
                    ->orderBy('hora_inicio')
                    ->take(5)
                    ->get();


                /*
                | Partidos de mis equipos
                */

                $partidos = Partido::with([
                        'equipo',
                        'categoria'
                    ])
                    ->where('club_id', $clubId)
                    ->whereYear('fecha', $anio)
                    ->whereIn('equipo_id', $idsEquipos)
                    ->whereDate('fecha', '>=', now())
                    ->orderBy('fecha')
                    ->orderBy('hora')
                    ->take(5)
                    ->get();
            }
        }


        /*
        |--------------------------------------------------------------------------
        | DASHBOARD DEPORTISTA
        |--------------------------------------------------------------------------
        */

        if ($esDeportista) {

            $miJugador = Jugador::with([
                    'equipos',
                    'categoria'
                ])
                ->where('club_id', $clubId)
                ->find($usuario->jugador_id);


            if ($miJugador) {

                /*
                | Equipos del jugador
                */

                $misEquipos = $miJugador->equipos()
                    ->where('equipos.club_id', $clubId)
                    ->where('equipos.activo', true)
                    ->get();

                $idsEquipos = $misEquipos
                    ->pluck('id');


                /*
                | Próximos entrenamientos
                */

                $entrenamientos = Entrenamiento::with([
                        'equipo',
                        'entrenador'
                    ])
                    ->where('club_id', $clubId)
                    ->whereYear('fecha', $anio)
                    ->whereIn('equipo_id', $idsEquipos)
                    ->whereDate('fecha', '>=', now())
                    ->orderBy('fecha')
                    ->orderBy('hora_inicio')
                    ->take(5)
                    ->get();


                /*
                | Próximos partidos
                */

                $partidos = Partido::with([
                        'equipo',
                        'categoria'
                    ])
                    ->where('club_id', $clubId)
                    ->whereYear('fecha', $anio)
                    ->whereIn('equipo_id', $idsEquipos)
                    ->whereDate('fecha', '>=', now())
                    ->orderBy('fecha')
                    ->orderBy('hora')
                    ->take(5)
                    ->get();


                /*
                | Estado de cuenta
                */

                $misCargos = CargoJugador::with([
                        'concepto',
                        'pagos'
                    ])
                    ->where('club_id', $clubId)
                    ->where('jugador_id', $miJugador->id)
                    ->whereYear('fecha', $anio)
                    ->orderByDesc('fecha')
                    ->get();

                $misCargos = $misCargos
                    ->filter(function ($cargo) {

                        return !in_array(
                            $cargo->estado,
                            [
                                'Pagado',
                                'Exonerado',
                                'Anulado'
                            ]
                        );
                    })
                    ->values();


                /*
                | Beca vigente
                */

                $miBeca = BecaJugador::with('concepto')
                    ->where('club_id', $clubId)
                    ->where('jugador_id', $miJugador->id)
                    ->where('activo', true)
                    ->whereDate(
                        'fecha_inicio',
                        '<=',
                        now()->toDateString()
                    )
                    ->whereDate(
                        'fecha_fin',
                        '>=',
                        now()->toDateString()
                    )
                    ->orderByDesc('fecha_inicio')
                    ->first();
            }
        }


        /*
        |--------------------------------------------------------------------------
        | NOTICIAS
        |--------------------------------------------------------------------------
        */

        $noticias = Noticia::where('club_id', $clubId)
            ->where('publicada', true)
            ->orderByDesc('fecha_publicacion')
            ->orderByDesc('id')
            ->take(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | CUMPLEAÑOS
        |--------------------------------------------------------------------------
        */

        $mesActual = now()->month;

        $mesSiguiente = now()
            ->copy()
            ->addMonth()
            ->month;

        $cumpleanios = Jugador::where('club_id', $clubId)
            ->whereNotNull('fecha_nacimiento')
            ->where('activo', true)
            ->get()
            ->filter(function ($jugador) use (
                $mesActual,
                $mesSiguiente
            ) {

                $mes = $jugador
                    ->fecha_nacimiento
                    ->month;

                return $mes == $mesActual
                    || $mes == $mesSiguiente;
            })
            ->sortBy(function ($jugador) use ($mesActual) {

                $mes =
                    $jugador->fecha_nacimiento->month;

                $dia =
                    $jugador->fecha_nacimiento->day;

                $ordenMes =
                    $mes == $mesActual ? 0 : 1;

                return sprintf(
                    '%d-%02d',
                    $ordenMes,
                    $dia
                );
            })
            ->values()
            ->take(5);


        /*
        |--------------------------------------------------------------------------
        | ENVIAR A VISTA
        |--------------------------------------------------------------------------
        */

        return view('dashboard.index', compact(

            'anio',

            'esEntrenador',
            'esDeportista',

            'totalJugadores',
            'totalEquipos',
            'totalCategorias',
            'totalActivos',
            'totalEntrenadores',

            'totalPendiente',
            'totalObligacionesPendientes',

            'entrenamientos',
            'partidos',

            'noticias',
            'cumpleanios',

            'misEquipos',

            'miJugador',
            'misCargos',
            'miBeca'
        ));
    }
}
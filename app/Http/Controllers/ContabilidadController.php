<?php

namespace App\Http\Controllers;

use App\Models\Contabilidad;
use App\Models\ConceptoContable;
use App\Models\Jugador;
use App\Models\CargoJugador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContabilidadController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $clubId = auth()->user()->club_id;

        $configuracion = \App\Models\Configuracion::find($clubId);

        $anio = session(
            'anio_trabajo',
            $configuracion?->anio ?? date('Y')
        );


        /*
        |--------------------------------------------------------------------------
        | PERIODO DE PENDIENTES
        |--------------------------------------------------------------------------
        |
        | Por defecto se utiliza el mes actual.
        |
        | Ejemplo:
        | 2026-08
        |
        | Si el usuario selecciona otro periodo, se utiliza ese.
        |
        */

        $periodoPendientes = $request->get(
            'periodo_pendiente',
            date('Y-m')
        );


        /*
        |--------------------------------------------------------------------------
        | VALIDAR FORMATO DEL PERIODO
        |--------------------------------------------------------------------------
        */

        if (
            !preg_match(
                '/^\d{4}-\d{2}$/',
                $periodoPendientes
            )
        ) {

            $periodoPendientes =
                date('Y-m');

        }


        /*
        |--------------------------------------------------------------------------
        | MOVIMIENTOS CONTABLES
        |--------------------------------------------------------------------------
        */

        $query = Contabilidad::with([
            'concepto',
            'jugador',
            'cargo',
        ])
        ->where(
            'club_id',
            $clubId
        )
        ->whereYear(
            'fecha',
            $anio
        );


        /*
        |--------------------------------------------------------------------------
        | FILTRO TIPO
        |--------------------------------------------------------------------------
        */

        if ($request->filled('tipo')) {

            $query->where(
                'tipo',
                $request->tipo
            );

        }


        /*
        |--------------------------------------------------------------------------
        | FILTRO CONCEPTO
        |--------------------------------------------------------------------------
        */

        if ($request->filled('concepto')) {

            $query->where(
                'concepto_contable_id',
                $request->concepto
            );

        }


        /*
        |--------------------------------------------------------------------------
        | FILTRO JUGADOR
        |--------------------------------------------------------------------------
        */

        if ($request->filled('jugador_movimiento')) {

            $query->where(
                'jugador_id',
                $request->jugador_movimiento
            );

        }


        /*
        |--------------------------------------------------------------------------
        | FILTRO DESDE
        |--------------------------------------------------------------------------
        */

        if ($request->filled('desde')) {

            $query->whereDate(
                'fecha',
                '>=',
                $request->desde
            );

        }


        /*
        |--------------------------------------------------------------------------
        | FILTRO HASTA
        |--------------------------------------------------------------------------
        */

        if ($request->filled('hasta')) {

            $query->whereDate(
                'fecha',
                '<=',
                $request->hasta
            );

        }


        /*
        |--------------------------------------------------------------------------
        | OBTENER MOVIMIENTOS
        |--------------------------------------------------------------------------
        */

        $movimientos = $query
            ->orderByDesc('fecha')
            ->orderByDesc('id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | INGRESOS
        |--------------------------------------------------------------------------
        */

        $ingresos = Contabilidad::where(
            'club_id',
            $clubId
        )
        ->whereYear(
            'fecha',
            $anio
        )
        ->where(
            'tipo',
            'Ingreso'
        )
        ->sum('valor');


        /*
        |--------------------------------------------------------------------------
        | GASTOS
        |--------------------------------------------------------------------------
        */

        $gastos = Contabilidad::where(
            'club_id',
            $clubId
        )
        ->whereYear(
            'fecha',
            $anio
        )
        ->where(
            'tipo',
            'Egreso'
        )
        ->sum('valor');


        /*
        |--------------------------------------------------------------------------
        | SALDO
        |--------------------------------------------------------------------------
        */

        $saldo =
            $ingresos -
            $gastos;


        /*
        |--------------------------------------------------------------------------
        | CARGOS DEL AÑO
        |--------------------------------------------------------------------------
        */

        $cargos = CargoJugador::with([
            'jugador',
            'concepto',
            'pagos',
        ])
        ->where(
            'club_id',
            $clubId
        )
        ->whereYear(
            'fecha',
            $anio
        )
        ->orderByDesc('fecha')
        ->orderByDesc('id')
        ->get();


        /*
        |--------------------------------------------------------------------------
        | TOTAL CARGOS
        |--------------------------------------------------------------------------
        */

        $totalCargos = $cargos
            ->whereNotIn(
                'estado',
                [
                    'Anulado',
                ]
            )
            ->sum('valor');


        /*
        |--------------------------------------------------------------------------
        | TOTAL PAGADO
        |--------------------------------------------------------------------------
        */

        $totalPagadoCargos = $cargos
            ->whereNotIn(
                'estado',
                [
                    'Anulado',
                ]
            )
            ->sum('valor_pagado');


        /*
        |--------------------------------------------------------------------------
        | PENDIENTES DEL PERIODO SELECCIONADO
        |--------------------------------------------------------------------------
        */

        $queryPendientes = CargoJugador::with([
            'jugador',
            'concepto',
        ])
        ->where(
            'club_id',
            $clubId
        )
        ->where(
            'periodo',
            $periodoPendientes
        )
        ->whereNotIn(
            'estado',
            [
                'Pagado',
                'Exonerado',
                'Anulado',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | FILTRO JUGADOR
        |--------------------------------------------------------------------------
        */

        if ($request->filled('jugador')) {

            $queryPendientes->where(
                'jugador_id',
                $request->jugador
            );

        }


        /*
        |--------------------------------------------------------------------------
        | FILTRO CONCEPTO
        |--------------------------------------------------------------------------
        */

        if ($request->filled('concepto_pendiente')) {

            $queryPendientes->where(
                'concepto_contable_id',
                $request->concepto_pendiente
            );

        }


        /*
        |--------------------------------------------------------------------------
        | OBTENER PENDIENTES
        |--------------------------------------------------------------------------
        */

        $cargosPendientes =
            $queryPendientes
                ->orderBy('jugador_id')
                ->orderBy('fecha')
                ->orderBy('id')
                ->get()
                ->filter(function ($cargo) {

                    return (float) $cargo->valor >
                           (float) $cargo->valor_pagado;

                })
                ->values();


        /*
        |--------------------------------------------------------------------------
        | TOTAL PENDIENTE
        |--------------------------------------------------------------------------
        |
        | Este total corresponde ÚNICAMENTE al periodo
        | seleccionado en Pendientes.
        |
        */

        $totalPendiente =
            $cargosPendientes->sum(
                function ($cargo) {

                    return max(
                        0,
                        (float) $cargo->valor -
                        (float) $cargo->valor_pagado
                    );

                }
            );


        /*
        |--------------------------------------------------------------------------
        | TOTAL EXONERADO DEL AÑO
        |--------------------------------------------------------------------------
        */

        $totalExonerado = $cargos
            ->where(
                'estado',
                'Exonerado'
            )
            ->sum('valor');


        /*
        |--------------------------------------------------------------------------
        | CONCEPTOS
        |--------------------------------------------------------------------------
        */

        $conceptos = ConceptoContable::where(
            'club_id',
            $clubId
        )
        ->where(
            'activo',
            1
        )
        ->orderBy('nombre')
        ->get();


        /*
        |--------------------------------------------------------------------------
        | JUGADORES
        |--------------------------------------------------------------------------
        */

        $jugadores = Jugador::where(
            'club_id',
            $clubId
        )
        ->where(
            'activo',
            1
        )
        ->with('categoria')
        ->orderBy('apellidos')
        ->orderBy('nombres')
        ->get();


        /*
        |--------------------------------------------------------------------------
        | VISTA
        |--------------------------------------------------------------------------
        */

        return view(
            'contabilidad.index',
            compact(
                'movimientos',
                'ingresos',
                'gastos',
                'saldo',
                'conceptos',
                'jugadores',
                'cargos',
                'cargosPendientes',
                'totalCargos',
                'totalPagadoCargos',
                'totalPendiente',
                'totalExonerado',
                'anio',
                'periodoPendientes'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREAR MOVIMIENTO
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $clubId = auth()->user()->club_id;


        /*
        |--------------------------------------------------------------------------
        | CONCEPTOS
        |--------------------------------------------------------------------------
        */

        $conceptos = ConceptoContable::where(
            'club_id',
            $clubId
        )
        ->where(
            'activo',
            1
        )
        ->orderBy('nombre')
        ->get();


        /*
        |--------------------------------------------------------------------------
        | JUGADORES
        |--------------------------------------------------------------------------
        */

        $jugadores = Jugador::where(
            'club_id',
            $clubId
        )
        ->where(
            'activo',
            1
        )
        ->with('categoria')
        ->orderBy('apellidos')
        ->orderBy('nombres')
        ->get();


        /*
        |--------------------------------------------------------------------------
        | CARGOS PENDIENTES
        |--------------------------------------------------------------------------
        */

        $cargosPendientes = CargoJugador::with([
            'concepto',
        ])
        ->where(
            'club_id',
            $clubId
        )
        ->whereNotIn(
            'estado',
            [
                'Pagado',
                'Exonerado',
                'Anulado',
            ]
        )
        ->get()
        ->filter(function ($cargo) {

            return (float) $cargo->valor >
                   (float) $cargo->valor_pagado;

        })
        ->values();


        /*
        |--------------------------------------------------------------------------
        | VISTA
        |--------------------------------------------------------------------------
        */

        return view(
            'contabilidad.create',
            compact(
                'conceptos',
                'jugadores',
                'cargosPendientes'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | GUARDAR MOVIMIENTO
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $clubId = auth()->user()->club_id;


        /*
        |--------------------------------------------------------------------------
        | VALIDACIÓN
        |--------------------------------------------------------------------------
        */

        $datos = $request->validate([

            'fecha' =>
                'required|date',

            'tipo' =>
                'required|in:Ingreso,Egreso',

            'concepto_contable_id' =>
                'required|exists:concepto_contables,id',

            'jugador_id' =>
                'nullable|exists:jugadores,id',

            'cargo_jugador_id' =>
                'nullable|exists:cargos_jugadores,id',

            'periodo' =>
                'nullable|date_format:Y-m',

            'tercero' =>
                'nullable|string|max:255',

            'valor' =>
                'required|numeric|min:1',

            'metodo_pago' =>
                'nullable|string|max:100',

            'observaciones' =>
                'nullable|string',

        ]);


        /*
        |--------------------------------------------------------------------------
        | CONCEPTO
        |--------------------------------------------------------------------------
        */

        $concepto = ConceptoContable::where(
            'id',
            $datos['concepto_contable_id']
        )
        ->where(
            'club_id',
            $clubId
        )
        ->where(
            'activo',
            1
        )
        ->first();


        if (!$concepto) {

            return back()
                ->withErrors([
                    'concepto_contable_id' =>
                        'El concepto no pertenece a este club.'
                ])
                ->withInput();

        }


        /*
        |--------------------------------------------------------------------------
        | JUGADOR
        |--------------------------------------------------------------------------
        */

        $jugador = null;


        if (!empty($datos['jugador_id'])) {

            $jugador = Jugador::where(
                'id',
                $datos['jugador_id']
            )
            ->where(
                'club_id',
                $clubId
            )
            ->where(
                'activo',
                1
            )
            ->first();


            if (!$jugador) {

                return back()
                    ->withErrors([
                        'jugador_id' =>
                            'El jugador no pertenece a este club.'
                    ])
                    ->withInput();

            }

        }


        /*
        |--------------------------------------------------------------------------
        | CARGO SELECCIONADO
        |--------------------------------------------------------------------------
        */

        $cargo = null;


        if (!empty($datos['cargo_jugador_id'])) {

            $cargo = CargoJugador::where(
                'id',
                $datos['cargo_jugador_id']
            )
            ->where(
                'club_id',
                $clubId
            )
            ->where(
                'jugador_id',
                $datos['jugador_id']
            )
            ->first();


            if (!$cargo) {

                return back()
                    ->withErrors([
                        'cargo_jugador_id' =>
                            'El cargo seleccionado no pertenece al jugador.'
                    ])
                    ->withInput();

            }


            /*
            |--------------------------------------------------------------------------
            | PENDIENTE ACTUAL
            |--------------------------------------------------------------------------
            */

            $pendiente = max(
                0,
                (float) $cargo->valor -
                (float) $cargo->valor_pagado
            );


            if ($pendiente <= 0) {

                return back()
                    ->withErrors([
                        'cargo_jugador_id' =>
                            'El cargo seleccionado ya está pagado.'
                    ])
                    ->withInput();

            }


            /*
            |--------------------------------------------------------------------------
            | NO PERMITIR SOBREPAGO
            |--------------------------------------------------------------------------
            */

            if (
                (float) $datos['valor'] >
                $pendiente
            ) {

                return back()
                    ->withErrors([
                        'valor' =>
                            'El pago no puede superar el saldo pendiente de $' .
                            number_format(
                                $pendiente,
                                0,
                                ',',
                                '.'
                            )
                    ])
                    ->withInput();

            }

        }


        /*
        |--------------------------------------------------------------------------
        | INGRESO DE JUGADOR
        |--------------------------------------------------------------------------
        */

        if (
            $datos['tipo'] === 'Ingreso' &&
            !empty($datos['jugador_id']) &&
            empty($datos['cargo_jugador_id'])
        ) {

            return back()
                ->withErrors([
                    'cargo_jugador_id' =>
                        'Selecciona qué pendiente está pagando este jugador.'
                ])
                ->withInput();

        }


        /*
        |--------------------------------------------------------------------------
        | SI HAY CARGO, EL PERIODO ES EL DEL CARGO
        |--------------------------------------------------------------------------
        */

        if ($cargo) {

            $datos['periodo'] =
                $cargo->periodo;

        }


        /*
        |--------------------------------------------------------------------------
        | GUARDAR TODO EN UNA TRANSACCIÓN
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $datos,
            $clubId,
            $cargo
        ) {

            /*
            |--------------------------------------------------------------------------
            | MOVIMIENTO
            |--------------------------------------------------------------------------
            */

            $datos['club_id'] =
                $clubId;


            $movimiento =
                Contabilidad::create($datos);


            /*
            |--------------------------------------------------------------------------
            | APLICAR PAGO AL CARGO
            |--------------------------------------------------------------------------
            */

            if ($cargo) {

                $nuevoPagado =
                    (float) $cargo->valor_pagado +
                    (float) $datos['valor'];


                $pendiente =
                    max(
                        0,
                        (float) $cargo->valor -
                        $nuevoPagado
                    );


                $estado =
                    $pendiente <= 0
                        ? 'Pagado'
                        : 'Parcial';


                $cargo->update([

                    'valor_pagado' =>
                        $nuevoPagado,

                    'estado' =>
                        $estado,

                ]);

            }

        });


        return redirect()
            ->route('contabilidad.index')
            ->with(
                'success',
                'Movimiento registrado correctamente.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | EDITAR MOVIMIENTO
    |--------------------------------------------------------------------------
    */

    public function edit(
        Contabilidad $contabilidad
    ) {

        abort_unless(
            $contabilidad->club_id ==
            auth()->user()->club_id,
            403
        );


        $clubId =
            auth()->user()->club_id;


        $conceptos = ConceptoContable::where(
            'club_id',
            $clubId
        )
        ->where(
            'activo',
            1
        )
        ->orderBy('nombre')
        ->get();


        $jugadores = Jugador::where(
            'club_id',
            $clubId
        )
        ->where(
            'activo',
            1
        )
        ->orderBy('apellidos')
        ->orderBy('nombres')
        ->get();


        return view(
            'contabilidad.edit',
            compact(
                'contabilidad',
                'conceptos',
                'jugadores'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ACTUALIZAR MOVIMIENTO
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Contabilidad $contabilidad
    ) {

        $clubId =
            auth()->user()->club_id;


        abort_unless(
            $contabilidad->club_id ==
            $clubId,
            403
        );


        $datos = $request->validate([

            'fecha' =>
                'required|date',

            'tipo' =>
                'required|in:Ingreso,Egreso',

            'concepto_contable_id' =>
                'required|exists:concepto_contables,id',

            'jugador_id' =>
                'nullable|exists:jugadores,id',

            'tercero' =>
                'nullable|string|max:255',

            'valor' =>
                'required|numeric|min:1',

            'metodo_pago' =>
                'nullable|string|max:100',

            'observaciones' =>
                'nullable|string',

        ]);


        /*
        |--------------------------------------------------------------------------
        | VALIDAR CONCEPTO
        |--------------------------------------------------------------------------
        */

        $conceptoValido =
            ConceptoContable::where(
                'id',
                $datos['concepto_contable_id']
            )
            ->where(
                'club_id',
                $clubId
            )
            ->exists();


        if (!$conceptoValido) {

            return back()
                ->withErrors([
                    'concepto_contable_id' =>
                        'El concepto no pertenece a este club.'
                ])
                ->withInput();

        }


        /*
        |--------------------------------------------------------------------------
        | VALIDAR JUGADOR
        |--------------------------------------------------------------------------
        */

        if (!empty($datos['jugador_id'])) {

            $jugadorValido =
                Jugador::where(
                    'id',
                    $datos['jugador_id']
                )
                ->where(
                    'club_id',
                    $clubId
                )
                ->exists();


            if (!$jugadorValido) {

                return back()
                    ->withErrors([
                        'jugador_id' =>
                            'El jugador no pertenece a este club.'
                    ])
                    ->withInput();

            }

        }


        $datos['club_id'] =
            $clubId;


        $contabilidad->update(
            $datos
        );


        return redirect()
            ->route('contabilidad.index')
            ->with(
                'success',
                'Movimiento actualizado correctamente.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | ELIMINAR MOVIMIENTO
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Contabilidad $contabilidad
    ) {

        abort_unless(
            $contabilidad->club_id ==
            auth()->user()->club_id,
            403
        );


        $contabilidad->delete();


        return redirect()
            ->route('contabilidad.index')
            ->with(
                'success',
                'Movimiento eliminado.'
            );
    }
}
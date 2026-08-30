<?php

namespace App\Http\Controllers;

use App\Models\Contabilidad;
use App\Models\ConceptoContable;
use App\Models\Jugador;
use App\Models\CargoJugador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\ContabilidadExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

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
        | Valores posibles:
        |
        | 2026-08   = mes específico
        | todos      = todos los meses del año
        |
        | Por defecto: mes actual.
        |
        */

        $periodoPendientes = $request->get(
            'periodo_pendiente',
            date('Y-m')
        );


        /*
        |--------------------------------------------------------------------------
        | VALIDAR PERIODO
        |--------------------------------------------------------------------------
        */

        if (
            $periodoPendientes !== 'todos' &&
            !preg_match(
                '/^\d{4}-\d{2}$/',
                $periodoPendientes
            )
        ) {

            $periodoPendientes = date('Y-m');

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
        | PENDIENTES
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
            ->whereYear(
                'fecha',
                $anio
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
        | FILTRO DE PERIODO
        |--------------------------------------------------------------------------
        |
        | Si es "todos":
        | muestra todo el año.
        |
        | Si es 2026-08:
        | muestra solamente agosto.
        |
        */

        if ($periodoPendientes !== 'todos') {

            $queryPendientes->where(
                'periodo',
                $periodoPendientes
            );

        }


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

        $cargosPendientes = $queryPendientes
            ->orderBy(
                'periodo'
            )
            ->orderBy(
                'jugador_id'
            )
            ->orderBy(
                'fecha'
            )
            ->orderBy(
                'id'
            )
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
        | CANTIDAD DE PENDIENTES
        |--------------------------------------------------------------------------
        */

        $cantidadPendientes =
            $cargosPendientes->count();


        /*
        |--------------------------------------------------------------------------
        | JUGADORES CON DEUDA
        |--------------------------------------------------------------------------
        */

        $jugadoresConPendiente =
            $cargosPendientes
                ->pluck('jugador_id')
                ->unique()
                ->count();


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
        | AÑOS DISPONIBLES
        |--------------------------------------------------------------------------
        */

        $aniosDisponibles = collect([
            $anio,
            date('Y'),
            date('Y') - 1,
            date('Y') + 1,
        ])
            ->unique()
            ->sort()
            ->values();


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
                'cantidadPendientes',
                'jugadoresConPendiente',
                'anio',
                'periodoPendientes',
                'aniosDisponibles'
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

    /*
|--------------------------------------------------------------------------
| ELIMINAR MOVIMIENTO
|--------------------------------------------------------------------------
*/

public function destroy(
    Contabilidad $contabilidad
) {

    $clubId = auth()->user()->club_id;


    /*
    |--------------------------------------------------------------------------
    | SEGURIDAD
    |--------------------------------------------------------------------------
    */

    abort_unless(
        $contabilidad->club_id == $clubId,
        403
    );


    /*
    |--------------------------------------------------------------------------
    | GUARDAR CARGO RELACIONADO
    |--------------------------------------------------------------------------
    */

    $cargoId =
        $contabilidad->cargo_jugador_id;


    /*
    |--------------------------------------------------------------------------
    | ELIMINAR MOVIMIENTO Y RECALCULAR CARGO
    |--------------------------------------------------------------------------
    */

    DB::transaction(function () use (
        $contabilidad,
        $cargoId,
        $clubId
    ) {


        /*
        |--------------------------------------------------------------------------
        | ELIMINAR MOVIMIENTO
        |--------------------------------------------------------------------------
        */

        $contabilidad->delete();


        /*
        |--------------------------------------------------------------------------
        | SI EL MOVIMIENTO ESTABA RELACIONADO
        | CON UN CARGO DE JUGADOR
        |--------------------------------------------------------------------------
        */

        if ($cargoId) {

            $cargo = CargoJugador::where(
                'id',
                $cargoId
            )
                ->where(
                    'club_id',
                    $clubId
                )
                ->first();


            if ($cargo) {


                /*
                |--------------------------------------------------------------------------
                | RECALCULAR TODO LO PAGADO
                |--------------------------------------------------------------------------
                |
                | No ponemos simplemente 0 porque podría haber
                | otros pagos parciales registrados sobre el mismo cargo.
                |
                */

                $totalPagado =
                    Contabilidad::where(
                        'club_id',
                        $clubId
                    )
                    ->where(
                        'cargo_jugador_id',
                        $cargo->id
                    )
                    ->sum('valor');


                $totalPagado =
                    (float) $totalPagado;


                $valorCargo =
                    (float) $cargo->valor;


                /*
                |--------------------------------------------------------------------------
                | CALCULAR PENDIENTE
                |--------------------------------------------------------------------------
                */

                $pendiente =
                    max(
                        0,
                        $valorCargo -
                        $totalPagado
                    );


                /*
                |--------------------------------------------------------------------------
                | DETERMINAR ESTADO
                |--------------------------------------------------------------------------
                */

                if ($totalPagado <= 0) {

                    $estado = 'Pendiente';

                } elseif ($pendiente > 0) {

                    $estado = 'Parcial';

                } else {

                    $estado = 'Pagado';

                }


                /*
                |--------------------------------------------------------------------------
                | ACTUALIZAR CARGO
                |--------------------------------------------------------------------------
                */

                $cargo->update([

                    'valor_pagado' =>
                        $totalPagado,

                    'estado' =>
                        $estado,

                ]);

            }

        }

    });


    /*
    |--------------------------------------------------------------------------
    | RESPUESTA
    |--------------------------------------------------------------------------
    */

    return redirect()
        ->route('contabilidad.index')
        ->with(
            'success',
            'Movimiento eliminado y estado del cargo actualizado correctamente.'
        );
}

    public function exportExcel(Request $request)
{
    $clubId = auth()->user()->club_id;

    return Excel::download(
        new ContabilidadExport(
            $clubId,
            $request->all()
        ),
        'contabilidad.xlsx'
    );
}


public function exportPdf(Request $request)
{
    $clubId = auth()->user()->club_id;

    /*
    |--------------------------------------------------------------------------
    | CONFIGURACIÓN DEL AÑO
    |--------------------------------------------------------------------------
    */

    $configuracion = \App\Models\Configuracion::find($clubId);

    $anio = session(
        'anio_trabajo',
        $configuracion?->anio ?? date('Y')
    );


    /*
    |--------------------------------------------------------------------------
    | PDF DE PENDIENTES
    |--------------------------------------------------------------------------
    */

    if ($request->boolean('solo_pendientes')) {

        $periodoPendientes = $request->get(
            'periodo_pendiente',
            date('Y-m')
        );


        $query = CargoJugador::with([
            'jugador',
            'concepto',
        ])
            ->where(
                'club_id',
                $clubId
            )
            ->whereYear(
                'fecha',
                $anio
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
        | PERIODO
        |--------------------------------------------------------------------------
        */

        if ($periodoPendientes !== 'todos') {

            $query->where(
                'periodo',
                $periodoPendientes
            );

        }


        /*
        |--------------------------------------------------------------------------
        | JUGADOR
        |--------------------------------------------------------------------------
        */

        if ($request->filled('jugador')) {

            $query->where(
                'jugador_id',
                $request->jugador
            );

        }


        /*
        |--------------------------------------------------------------------------
        | CONCEPTO
        |--------------------------------------------------------------------------
        */

        if ($request->filled('concepto_pendiente')) {

            $query->where(
                'concepto_contable_id',
                $request->concepto_pendiente
            );

        }


        $cargosPendientes = $query
            ->orderBy('periodo')
            ->orderBy('jugador_id')
            ->orderBy('fecha')
            ->get()
            ->filter(function ($cargo) {

                return (float) $cargo->valor >
                    (float) $cargo->valor_pagado;

            })
            ->values();


        /*
        |--------------------------------------------------------------------------
        | TOTAL
        |--------------------------------------------------------------------------
        */

        $totalPendiente = $cargosPendientes->sum(
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
        | PDF SOLO PENDIENTES
        |--------------------------------------------------------------------------
        */

        $pdf = Pdf::loadView(
            'contabilidad.print-pendientes',
            compact(
                'cargosPendientes',
                'totalPendiente',
                'anio',
                'periodoPendientes'
            )
        );


        return $pdf->download(
            'pendientes-de-pago.pdf'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PDF SOLO MOVIMIENTOS
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
    | DESDE
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
    | HASTA
    |--------------------------------------------------------------------------
    */

    if ($request->filled('hasta')) {

        $query->whereDate(
            'fecha',
            '<=',
            $request->hasta
        );

    }


    $movimientos = $query
        ->orderByDesc('fecha')
        ->orderByDesc('id')
        ->get();


    /*
    |--------------------------------------------------------------------------
    | PDF SOLO MOVIMIENTOS
    |--------------------------------------------------------------------------
    */

    $pdf = Pdf::loadView(
        'contabilidad.print-movimientos',
        compact(
            'movimientos',
            'anio'
        )
    );


    return $pdf->download(
        'movimientos-contables.pdf'
    );
}
}
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
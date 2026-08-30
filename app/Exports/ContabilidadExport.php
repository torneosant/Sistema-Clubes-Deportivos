<?php

namespace App\Exports;

use App\Models\Contabilidad;
use App\Models\CargoJugador;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ContabilidadExport implements
    FromCollection,
    WithHeadings
{
    protected $clubId;
    protected $filtros;

    public function __construct(
        $clubId,
        array $filtros = []
    ) {
        $this->clubId = $clubId;
        $this->filtros = $filtros;
    }


    public function collection()
    {
        $f = $this->filtros;


        /*
        |--------------------------------------------------------------------------
        | SI SE SOLICITA SOLO PENDIENTES
        |--------------------------------------------------------------------------
        */

        if (
            isset($f['solo_pendientes']) &&
            $f['solo_pendientes']
        ) {

            $periodo =
                $f['periodo_pendiente']
                ?? date('Y-m');


            $configuracion =
                \App\Models\Configuracion::find(
                    $this->clubId
                );


            $anio = session(
                'anio_trabajo',
                $configuracion?->anio ?? date('Y')
            );


            $cargos =
                CargoJugador::with([
                    'jugador',
                    'concepto',
                ])
                ->where(
                    'club_id',
                    $this->clubId
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
                )

                ->when(
                    $periodo !== 'todos',
                    fn ($q) =>
                        $q->where(
                            'periodo',
                            $periodo
                        )
                )

                ->when(
                    $f['jugador'] ?? null,
                    fn ($q, $jugador) =>
                        $q->where(
                            'jugador_id',
                            $jugador
                        )
                )

                ->when(
                    $f['concepto_pendiente'] ?? null,
                    fn ($q, $concepto) =>
                        $q->where(
                            'concepto_contable_id',
                            $concepto
                        )
                )

                ->orderBy('periodo')
                ->orderBy('jugador_id')
                ->get()
                ->filter(function ($cargo) {

                    return (float) $cargo->valor >
                        (float) $cargo->valor_pagado;

                });


            return $cargos->map(
                function ($cargo) {

                    return [

                        'Jugador' =>
                            ($cargo->jugador->apellidos ?? '') .
                            ' ' .
                            ($cargo->jugador->nombres ?? ''),

                        'Concepto' =>
                            $cargo->concepto->nombre ?? '',

                        'Periodo' =>
                            $cargo->periodo,

                        'Fecha' =>
                            optional(
                                $cargo->fecha
                            )->format('d/m/Y'),

                        'Valor' =>
                            $cargo->valor,

                        'Pagado' =>
                            $cargo->valor_pagado,

                        'Pendiente' =>
                            max(
                                0,
                                (float) $cargo->valor -
                                (float) $cargo->valor_pagado
                            ),

                        'Estado' =>
                            $cargo->estado,

                    ];

                }
            );

        }


        /*
        |--------------------------------------------------------------------------
        | MOVIMIENTOS CONTABLES
        |--------------------------------------------------------------------------
        */

        return Contabilidad::with([
            'concepto',
            'jugador',
        ])
        ->where(
            'club_id',
            $this->clubId
        )

        ->when(
            $f['tipo'] ?? null,
            fn ($q, $tipo) =>
                $q->where(
                    'tipo',
                    $tipo
                )
        )

        ->when(
            $f['concepto'] ?? null,
            fn ($q, $concepto) =>
                $q->where(
                    'concepto_contable_id',
                    $concepto
                )
        )

        ->when(
            $f['jugador_movimiento'] ?? null,
            fn ($q, $jugador) =>
                $q->where(
                    'jugador_id',
                    $jugador
                )
        )

        ->when(
            $f['desde'] ?? null,
            fn ($q, $desde) =>
                $q->whereDate(
                    'fecha',
                    '>=',
                    $desde
                )
        )

        ->when(
            $f['hasta'] ?? null,
            fn ($q, $hasta) =>
                $q->whereDate(
                    'fecha',
                    '<=',
                    $hasta
                )
        )

        ->orderByDesc('fecha')
        ->orderByDesc('id')
        ->get()

        ->map(function ($movimiento) {

            return [

                'Fecha' =>
                    optional(
                        $movimiento->fecha
                    )->format('d/m/Y'),

                'Tipo' =>
                    $movimiento->tipo,

                'Concepto' =>
                    $movimiento->concepto->nombre
                    ?? '',

                'Jugador' =>
                    $movimiento->jugador
                        ? $movimiento->jugador->apellidos .
                          ' ' .
                          $movimiento->jugador->nombres
                        : '',

                'Tercero' =>
                    $movimiento->tercero ?? '',

                'Valor' =>
                    $movimiento->valor,

                'Método de pago' =>
                    $movimiento->metodo_pago ?? '',

                'Periodo' =>
                    $movimiento->periodo ?? '',

                'Estado' =>
                    $movimiento->estado,

                'Observaciones' =>
                    $movimiento->observaciones ?? '',

            ];

        });
    }


    public function headings(): array
    {
        return [

            'Fecha',
            'Tipo',
            'Concepto',
            'Jugador',
            'Tercero',
            'Valor',
            'Método de pago',
            'Periodo',
            'Estado',
            'Observaciones',

        ];
    }
}
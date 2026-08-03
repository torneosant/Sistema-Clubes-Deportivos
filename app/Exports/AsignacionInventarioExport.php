<?php

namespace App\Exports;

use App\Models\AsignacionInventario;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AsignacionInventarioExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return AsignacionInventario::with(['inventario','entrenador'])
            ->latest()
            ->get()
            ->map(function ($a) {

                return [

                    'fecha' => $a->fecha,

                    'articulo' => $a->inventario?->nombre,

                    'destino' => $a->tipo_destino == 'Entrenador'
                        ? $a->entrenador?->nombres.' '.$a->entrenador?->apellidos
                        : ($a->destino_otro ?? $a->tipo_destino),

                    'cantidad' => $a->cantidad,

                    'devuelta' => $a->cantidad_devuelta,

                    'pendiente' => $a->cantidad - $a->cantidad_devuelta,

                    'estado' => $a->estado,

                ];

            });

    }

    public function headings(): array
    {
        return [

            'Fecha',
            'Artículo',
            'Responsable / Destino',
            'Cantidad Entregada',
            'Cantidad Devuelta',
            'Pendiente',
            'Estado',

        ];
    }
}
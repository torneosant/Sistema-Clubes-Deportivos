<?php

namespace App\Exports;

use App\Models\Inventario;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class InventarioExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Inventario::with('tipoArticulo')
            ->get()
            ->map(function ($articulo) {

                $asignado = $articulo->asignaciones()
                    ->where('estado','Activa')
                    ->sum('cantidad');

                return [

                    'codigo'      => $articulo->codigo,
                    'articulo'    => $articulo->nombre,
                    'tipo'        => $articulo->tipoArticulo?->nombre,
                    'stock'       => $articulo->cantidad,
                    'asignado'    => $asignado,
                    'disponible'  => $articulo->cantidad - $asignado,
                    'estado'      => $articulo->estado,
                    'ubicacion'   => $articulo->ubicacion,

                ];

            });
    }

    public function headings(): array
    {
        return [

            'Código',
            'Artículo',
            'Tipo',
            'Stock Total',
            'Asignado',
            'Disponible',
            'Estado',
            'Ubicación',

        ];
    }
}
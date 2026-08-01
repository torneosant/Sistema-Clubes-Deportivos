<?php

namespace App\Exports;

use App\Models\Jugador;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class JugadoresExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Jugador::with(['categoria', 'equipo'])
            ->orderBy('nombres')
            ->get()
            ->map(function ($jugador) {

                return [

                    'Documento' => $jugador->numero_documento,

                    'Nombres' => $jugador->nombres,

                    'Apellidos' => $jugador->apellidos,

                    'Categoría' => $jugador->categoria?->nombre,

                    'Equipo' => $jugador->equipo?->nombre,

                    'Posición' => $jugador->posicion,

                    'Teléfono' => $jugador->telefono,

                    'Estado' => $jugador->activo ? 'Activo' : 'Inactivo',

                ];

            });
    }

    public function headings(): array
    {
        return [

            'Documento',
            'Nombres',
            'Apellidos',
            'Categoría',
            'Equipo',
            'Posición',
            'Teléfono',
            'Estado'

        ];
    }
}
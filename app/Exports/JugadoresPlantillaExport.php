<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class JugadoresPlantillaExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return [

            'Documento',
            'Tipo Documento',
            'Nombres',
            'Apellidos',
            'Fecha Nacimiento',
            'Sexo',
            'Teléfono',
            'Correo',
            'Categoría',
            'Equipo',
            'Posición',
            'RH',
            'EPS',
            'Dirección'

        ];
    }

    public function array(): array
    {
        return [

            [
                '',
                'CC',
                '',
                '',
                '2008-01-01',
                'F',
                '',
                '',
                '',
                '',
                '',
                'O+',
                '',
                ''
            ]

        ];
    }
}

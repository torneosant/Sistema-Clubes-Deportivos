<?php

namespace App\Exports;

use App\Models\Entrenador;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EntrenadoresExport implements FromCollection, WithHeadings
{
    protected $clubId;

    public function __construct($clubId)
    {
        $this->clubId = $clubId;
    }

    public function collection()
    {
        return Entrenador::where('club_id', $this->clubId)
            ->select(
                'nombres',
                'apellidos',
                'numero_documento',
                'telefono',
                'email',
                'ciudad',
                'cargo',
                'licencia',
                'activo'
            )
            ->orderBy('apellidos')
            ->orderBy('nombres')
            ->get()
            ->map(function ($entrenador) {

                return [
                    $entrenador->nombres,
                    $entrenador->apellidos,
                    $entrenador->numero_documento,
                    $entrenador->telefono,
                    $entrenador->email,
                    $entrenador->ciudad,
                    $entrenador->cargo,
                    $entrenador->licencia,
                    $entrenador->activo ? 'Activo' : 'Inactivo',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Nombres',
            'Apellidos',
            'Documento',
            'Teléfono',
            'Correo',
            'Ciudad',
            'Cargo',
            'Licencia',
            'Estado'
        ];
    }
}
<?php

namespace App\Exports;

use App\Models\Competencia;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CompetenciaParticipantesExport implements FromCollection, WithHeadings, WithMapping
{
    protected Competencia $competencia;

    protected array $campos;

    public function __construct(
        Competencia $competencia,
        array $campos = []
    ) {
        $this->competencia = $competencia;
        $this->campos = $campos;
    }

    /**
     * Jugadores que pertenecen a esta competencia.
     */
    public function collection(): Collection
    {
        return $this->competencia
            ->jugadores()
            ->with('categoria')
            ->orderBy('apellidos')
            ->orderBy('nombres')
            ->get();
    }

    /**
     * Encabezados del Excel.
     */
    public function headings(): array
    {
        $encabezados = [];

        $nombresCampos = [
            'nombres' => 'Nombres',
            'apellidos' => 'Apellidos',
            'documento' => 'Documento',
            'fecha_nacimiento' => 'Fecha de nacimiento',
            'telefono' => 'Teléfono',
            'email' => 'Email',
            'direccion' => 'Dirección',
            'eps' => 'EPS',
            'tipo_sangre' => 'Tipo de sangre',
            'acudiente' => 'Acudiente',
            'documento_acudiente' => 'Documento acudiente',
            'telefono_acudiente' => 'Teléfono acudiente',
            'email_acudiente' => 'Email acudiente',
            'parentesco' => 'Parentesco',
        ];

        foreach ($this->campos as $campo) {

            if (isset($nombresCampos[$campo])) {
                $encabezados[] = $nombresCampos[$campo];
            }

        }

        return $encabezados;
    }

    /**
     * Datos de cada jugador.
     */
    public function map($jugador): array
    {
        $fila = [];

        foreach ($this->campos as $campo) {

            $fila[] = match ($campo) {

                'nombres' =>
                    $jugador->nombres,

                'apellidos' =>
                    $jugador->apellidos,

                'documento' =>
                    $jugador->numero_documento,

                'fecha_nacimiento' =>
                    $jugador->fecha_nacimiento?->format('d/m/Y'),

                'telefono' =>
                    $jugador->telefono,

                'email' =>
                    $jugador->email,

                'direccion' =>
                    $jugador->direccion,

                'eps' =>
                    $jugador->eps,

                'tipo_sangre' =>
                    $jugador->tipo_sangre,

                'acudiente' =>
                    $jugador->acudiente,

                'documento_acudiente' =>
                    $jugador->documento_acudiente,

                'telefono_acudiente' =>
                    $jugador->telefono_acudiente,

                'email_acudiente' =>
                    $jugador->email_acudiente,

                'parentesco' =>
                    $jugador->parentesco,

                default => '',
            };
        }

        return $fila;
    }
}
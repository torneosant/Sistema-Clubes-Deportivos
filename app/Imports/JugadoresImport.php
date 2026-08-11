<?php

namespace App\Imports;

use App\Models\Jugador;
use App\Models\Categoria;
use App\Models\Equipo;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class JugadoresImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    protected $clubId;

    public function __construct($clubId)
    {
        $this->clubId = $clubId;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            // Documento obligatorio
            if (empty($row['documento'])) {
                continue;
            }

            // Evitar duplicados
            if (Jugador::where('club_id', $this->clubId)
    ->where('numero_documento', trim($row['documento']))
    ->exists()) {
    continue;
}

            // Buscar categoría (opcional)
            $categoria = null;

            if (!empty($row['categoria'])) {

                $$categoria = Categoria::where('club_id', $this->clubId)
    ->where('nombre', trim($row['categoria']))
    ->first();

            }

            // Buscar equipo (opcional)
            $equipo = null;

            if (!empty($row['equipo'])) {

                $equipo = Equipo::where('club_id', $this->clubId)
    ->where('nombre', trim($row['equipo']))
    ->first();

            }

            Jugador::create([

                'club_id' => $this->clubId,

                'numero_documento' => trim($row['documento']),
                'tipo_documento'   => trim($row['tipo_documento']),

                'nombres' => trim($row['nombres']),
                'apellidos' => trim($row['apellidos']),

                'fecha_nacimiento' => $row['fecha_nacimiento'] ?: null,
                'genero' => match (strtoupper(trim($row['sexo'] ?? ''))) {
    'M', 'MASCULINO' => 'Masculino',
    'F', 'FEMENINO'  => 'Femenino',
    default => null,
},

                'telefono' => $row['telefono'] ?: null,
                'email' => $row['correo'] ?: null,
                'direccion' => $row['direccion'] ?: null,

                'categoria_id' => $categoria?->id,
                'equipo_id'    => $equipo?->id,

                'posicion' => $row['posicion'] ?: null,

                'tipo_sangre' => $row['rh'] ?: null,
                'eps' => $row['eps'] ?: null,

                'activo' => true

            ]);

        }
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Club;

class Jugador extends Model
{
    protected $fillable = [

        'club_id',

        'nombres',
        'apellidos',

        'tipo_documento',
        'numero_documento',

        'fecha_nacimiento',

        'genero',

        'telefono',
        'email',
        'direccion',
        'ciudad',

        'categoria',
        'equipo',
        'posicion',
        'pierna_habil',

        'estatura',
        'peso',

        'eps',
        'tipo_sangre',
        'alergias',
        'observaciones_medicas',

        'acudiente',
        'telefono_acudiente',
        'parentesco',

        'foto',

        'activo',
        'estado'

    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'activo' => 'boolean',
    ];
    public function club()
{
    return $this->belongsTo(Club::class);
}
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entrenador extends Model
{
    protected $fillable = [

        'club_id',

        'nombres',
        'apellidos',

        'numero_documento',

        'fecha_nacimiento',

        'telefono',
        'email',

        'direccion',
        'ciudad',

        'cargo',
        'licencia',

        'fecha_ingreso',

        'foto',

        'observaciones',

        'activo'

    ];
public function equipos()
{
    return $this->belongsToMany(Equipo::class);
}

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inscripcion extends Model
{
     protected $table = 'inscripciones';
    protected $fillable = [

        'club_id',
        'categoria_id',
        'jugador_id',
        'token',

        'nombres',
        'apellidos',
        'documento',
        'fecha_nacimiento',

        'telefono',
        'email',
        'direccion',

        'posicion',
        'club_anterior',
        'observaciones',

        'estado',
        'motivo_denegacion',

        'fecha_revision',
        'revisado_por',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'fecha_revision' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function jugador()
    {
        return $this->belongsTo(Jugador::class);
    }

    public function revisor()
    {
        return $this->belongsTo(User::class, 'revisado_por');
    }
}
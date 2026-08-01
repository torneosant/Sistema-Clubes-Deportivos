<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistorialMedico extends Model
{
    protected $fillable = [

        'club_id',

        'jugador_id',

        'fecha',

        'tipo',

        'zona',

        'diagnostico',

        'tratamiento',

        'dias_incapacidad',

        'fecha_alta',

        'estado',

        'observaciones'

    ];

    public function jugador()
    {
        return $this->belongsTo(Jugador::class);
    }

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    
}
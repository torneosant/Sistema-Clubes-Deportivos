<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartidoJugador extends Model
{
    use HasFactory;

    protected $table = 'partido_jugadores';


    /*
    |--------------------------------------------------------------------------
    | Campos asignables
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        'partido_id',

        'jugador_id',

        'participacion',

        'titular',

        'minutos',

        'goles',

        'asistencias',

        'amarillas',

        'rojas',

        'figura',

        'observaciones',

    ];


    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected $casts = [

        'titular' => 'boolean',

        'figura' => 'boolean',

        'minutos' => 'integer',

        'goles' => 'integer',

        'asistencias' => 'integer',

        'amarillas' => 'integer',

        'rojas' => 'integer',

    ];


    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function partido()
    {
        return $this->belongsTo(
            Partido::class
        );
    }


    public function jugador()
    {
        return $this->belongsTo(
            Jugador::class
        );
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartidoJugador extends Model
{
    use HasFactory;

    protected $table = 'partido_jugadores';

    protected $fillable = [
        'partido_id',
        'jugador_id',
        'minutos',
        'goles',
        'asistencias',
        'amarillas',
        'rojas',
        'figura',
        'observaciones',
        'participacion',
    ];

    public function partido()
    {
        return $this->belongsTo(Partido::class);
    }

    public function jugador()
    {
        return $this->belongsTo(Jugador::class);
    }
    
}
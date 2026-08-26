<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Competencia extends Model
{
    protected $table = 'competencias';

    protected $fillable = [
        'club_id',
        'categoria_id',
        'nombre',
        'tipo',
        'estado',
        'fecha_inicio',
        'fecha_fin',
        'lugar',
        'descripcion',
        'activo',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'activo' => 'boolean',
    ];

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Participantes
    |--------------------------------------------------------------------------
    |
    | Esta relación la completaremos cuando construyamos la inscripción
    | de jugadores a la competencia.
    |
    */

    public function jugadores()
    {
        return $this->belongsToMany(
            Jugador::class,
            'competencia_jugadores',
            'competencia_id',
            'jugador_id'
        )->withPivot([
            'es_refuerzo',
            'categoria_origen_id',
            'observaciones',
        ])->withTimestamps();
    }
}
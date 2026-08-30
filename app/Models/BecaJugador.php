<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BecaJugador extends Model
{
    protected $table = 'becas_jugadores';

    protected $fillable = [
        'club_id',
        'jugador_id',
        'concepto_contable_id',
        'fecha_inicio',
        'fecha_fin',
        'porcentaje',
        'motivo',
        'activo',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'porcentaje' => 'decimal:2',
        'activo' => 'boolean',
    ];

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function jugador()
    {
        return $this->belongsTo(Jugador::class);
    }

    public function concepto()
    {
        return $this->belongsTo(
            ConceptoContable::class,
            'concepto_contable_id'
        );
    }

    public function estaVigente($fecha = null)
    {
        $fecha = $fecha
            ? \Carbon\Carbon::parse($fecha)
            : now();

        return $this->activo
            && $fecha->between(
                $this->fecha_inicio,
                $this->fecha_fin
            );
    }
}
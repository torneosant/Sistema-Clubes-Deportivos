<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CargoJugador extends Model
{
    protected $table = 'cargos_jugadores';

    protected $fillable = [
        'club_id',
        'jugador_id',
        'concepto_contable_id',
        'periodo',
        'fecha',
        'valor',
        'valor_pagado',
        'estado',
        'motivo_exoneracion',
        'observaciones',
    ];

    protected $casts = [
        'fecha' => 'date',
        'valor' => 'decimal:2',
        'valor_pagado' => 'decimal:2',
    ];

    public function club()
    {
        return $this->belongsTo(
            Club::class
        );
    }

    public function jugador()
    {
        return $this->belongsTo(
            Jugador::class
        );
    }

    public function concepto()
    {
        return $this->belongsTo(
            ConceptoContable::class,
            'concepto_contable_id'
        );
    }

    public function pagos()
    {
        return $this->hasMany(
            Contabilidad::class,
            'cargo_jugador_id'
        )
        ->where('tipo', 'Ingreso');
    }

    public function getPendienteAttribute()
    {
        return max(
            0,
            (float) $this->valor - (float) $this->valor_pagado
        );
    }
}
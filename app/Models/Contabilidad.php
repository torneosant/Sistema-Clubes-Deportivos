<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contabilidad extends Model
{
    protected $table = 'contabilidad';

    protected $fillable = [
        'club_id',
        'fecha',
        'periodo',
        'tipo',
        'concepto_contable_id',
        'jugador_id',
        'cargo_jugador_id',
        'tercero',
        'valor',
        'metodo_pago',
        'estado',
        'observaciones',
    ];

    protected $casts = [
        'fecha' => 'date',
        'valor' => 'decimal:2',
    ];

    public function concepto()
    {
        return $this->belongsTo(
            ConceptoContable::class,
            'concepto_contable_id'
        );
    }

    public function jugador()
    {
        return $this->belongsTo(
            Jugador::class,
            'jugador_id'
        );
    }

    public function cargo()
    {
        return $this->belongsTo(
            CargoJugador::class,
            'cargo_jugador_id'
        );
    }
}
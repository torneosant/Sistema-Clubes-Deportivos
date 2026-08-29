<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cobro extends Model
{
    protected $table = 'cobros';

    protected $fillable = [
        'club_id',
        'concepto_contable_id',
        'tipo',
        'valor',
        'dia_cobro',
        'fecha_maxima',
        'fecha_inicio',
        'activo',
        'observaciones',
    ];

    protected $casts = [
        'valor' => 'decimal:2',
        'fecha_maxima' => 'date',
        'fecha_inicio' => 'date',
        'activo' => 'boolean',
    ];

    public function club()
    {
        return $this->belongsTo(
            Club::class
        );
    }

    public function concepto()
    {
        return $this->belongsTo(
            ConceptoContable::class,
            'concepto_contable_id'
        );
    }
}
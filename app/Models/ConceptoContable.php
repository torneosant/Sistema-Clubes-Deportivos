<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConceptoContable extends Model
{
    use HasFactory;

    protected $fillable = [
        'club_id',
        'nombre',
        'tipo',
        'descripcion',
        'valor_predeterminado',
        'activo',
    ];

    protected $casts = [
        'valor_predeterminado' => 'decimal:2',
        'activo' => 'boolean',
    ];


    /**
     * Movimientos contables asociados.
     */
    public function movimientos()
    {
        return $this->hasMany(
            Contabilidad::class,
            'concepto_contable_id'
        );
    }


    /**
     * Cargos realizados a jugadores.
     */
    public function cargos()
    {
        return $this->hasMany(
            CargoJugador::class,
            'concepto_contable_id'
        );
    }
}
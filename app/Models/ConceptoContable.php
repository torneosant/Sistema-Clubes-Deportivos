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
        'activo',

        // Configuración de cobro
        'genera_cobro',
        'tipo_cobro',
        'valor_cobro',
        'dia_cobro',
        'fecha_maxima',
        'fecha_inicio',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'genera_cobro' => 'boolean',
        'valor_cobro' => 'decimal:2',
        'fecha_maxima' => 'date',
        'fecha_inicio' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | MOVIMIENTOS CONTABLES
    |--------------------------------------------------------------------------
    */

    public function movimientos()
    {
        return $this->hasMany(
            Contabilidad::class,
            'concepto_contable_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CARGOS DE JUGADORES
    |--------------------------------------------------------------------------
    */

    public function cargos()
    {
        return $this->hasMany(
            CargoJugador::class,
            'concepto_contable_id'
        );
    }
public function becas()
{
    return $this->hasMany(
        BecaJugador::class,
        'concepto_contable_id'
    );
}

}
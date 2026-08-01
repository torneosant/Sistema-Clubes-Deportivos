<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contabilidad extends Model
{
    protected $table = 'contabilidad';

    protected $fillable = [

        'fecha',

        'tipo',

        'concepto_contable_id',

        'jugador_id',

        'tercero',  

        'valor',

        'metodo_pago',

        'observaciones',

    ];

    public function concepto()
    {
        return $this->belongsTo(ConceptoContable::class,'concepto_contable_id');
    }

    public function jugador()
    {
        return $this->belongsTo(Jugador::class);
    }

}
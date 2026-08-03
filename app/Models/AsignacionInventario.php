<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsignacionInventario extends Model
{
   protected $fillable = [
    'inventario_id',
    'tipo_destino',
    'entrenador_id',
    'destino_otro',
    'cantidad',
    'fecha',
    'observaciones',
    'cantidad_devuelta',
    'estado',
];

    public function inventario()
    {
        return $this->belongsTo(Inventario::class);
    }

    public function entrenador()
{
    return $this->belongsTo(Entrenador::class);
}
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimientoInventario extends Model
{
    protected $fillable = [

    'inventario_id',
    'asignacion_id',
    'tipo',
    'cantidad',
    'fecha',
    'responsable',
    'observaciones'

];

public function inventario()
{
    return $this->belongsTo(Inventario::class);
}

public function asignacion()
{
    return $this->belongsTo(AsignacionInventario::class,'asignacion_id');
}
}

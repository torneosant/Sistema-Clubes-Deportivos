<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    protected $fillable = [
        'nombre',
        'codigo',
        'tipo_articulo_id',
        'marca',
        'cantidad',
        'estado',
        'ubicacion',
        'observaciones',
        'activo',
    ];

    public function tipoArticulo()
    {
        return $this->belongsTo(TipoArticulo::class);
    }
public function asignaciones()
{
    return $this->hasMany(AsignacionInventario::class);
}

public function getDisponibleAttribute()
{
    $prestados = $this->asignaciones()
        ->where('estado', 'Activa')
        ->sum('cantidad');

    return $this->cantidad - $prestados;
}

}   
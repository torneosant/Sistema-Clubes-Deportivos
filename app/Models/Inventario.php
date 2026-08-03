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

    public function getAsignadoAttribute()
    {
        return $this->asignaciones->sum(function ($a) {
            return $a->cantidad - $a->cantidad_devuelta;
        });
    }

    public function getDisponibleAttribute()
    {
        return $this->cantidad - $this->asignado;
    }
}
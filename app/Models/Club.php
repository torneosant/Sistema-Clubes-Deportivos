<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Club extends Model
{
    protected $fillable = [
        'nombre',
        'slug',
        'email',
        'telefono',
        'ciudad',
        'departamento',
        'pais',
        'direccion',
        'logo',
        'descripcion',
        'activo',
    ];
    public function equipos()
{
    return $this->hasMany(Equipo::class);
}
public function partidos()
{
    return $this->hasMany(Partido::class);
}
}
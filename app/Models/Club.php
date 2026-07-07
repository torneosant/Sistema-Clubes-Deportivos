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
}
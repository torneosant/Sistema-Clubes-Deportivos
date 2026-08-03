<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoArticulo extends Model
{
    protected $fillable = [
        'nombre',
        'activo'
    ];
}
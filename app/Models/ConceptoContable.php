<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConceptoContable extends Model
{
    use HasFactory;

    protected $fillable = [

        'nombre',

        'tipo',

        'descripcion',

        'activo',

    ];
}
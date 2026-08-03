<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoDocumento extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'activo'
    ];

    public function documentos()
    {
        return $this->hasMany(Documento::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoDocumento extends Model
{
    protected $fillable = [
        'club_id',
        'nombre',
        'descripcion',
        'activo'
    ];

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function documentos()
    {
        return $this->hasMany(Documento::class);
    }
}
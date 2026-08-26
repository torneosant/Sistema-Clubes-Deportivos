<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoDocumentoClub extends Model
{
    protected $table = 'tipos_documentos_club';

    protected $fillable = [
        'club_id',
        'nombre',
        'descripcion',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function documentos()
    {
        return $this->hasMany(
            DocumentoClub::class,
            'tipo_documento_club_id'
        );
    }
}
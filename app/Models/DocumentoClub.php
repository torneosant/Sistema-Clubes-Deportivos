<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentoClub extends Model
{
    protected $table = 'documentos_club';

    protected $fillable = [
        'club_id',
        'tipo_documento_club_id',
        'titulo',
        'descripcion',
        'archivo',
        'fecha',
        'activo',
    ];

    protected $casts = [
        'fecha' => 'date',
        'activo' => 'boolean',
    ];

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function tipoDocumentoClub()
    {
        return $this->belongsTo(
            TipoDocumentoClub::class,
            'tipo_documento_club_id'
        );
    }

    public function configuracionesInscripcion()
{
    return $this->belongsToMany(
        ConfiguracionInscripcion::class,
        'configuracion_inscripcion_documento',
        'documento_club_id',
        'configuracion_inscripcion_id'
    );
}
}
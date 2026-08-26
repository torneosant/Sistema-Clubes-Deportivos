<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracionInscripcion extends Model
{
    protected $table = 'configuracion_inscripciones';

    protected $fillable = [
        'club_id',
        'enviar_correo',
        'adjuntar_documentos',
        'asunto_correo',
        'mensaje_correo',
    ];

    protected $casts = [
        'enviar_correo' => 'boolean',
        'adjuntar_documentos' => 'boolean',
    ];

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function documentos()
    {
        return $this->belongsToMany(
            DocumentoClub::class,
            'configuracion_inscripcion_documento',
            'configuracion_inscripcion_id',
            'documento_club_id'
        );
    }
}
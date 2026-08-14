<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    protected $fillable = [
        'club_id',
        'jugador_id',
        'tipo_documento_id',
        'titulo',
        'descripcion',
        'archivo',
        'fecha',
        'activo'
    ];

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function tipoDocumento()
    {
        return $this->belongsTo(TipoDocumento::class);
    }

    public function jugador()
    {
        return $this->belongsTo(Jugador::class);
    }
}
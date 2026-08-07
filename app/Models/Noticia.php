<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Noticia extends Model
{
    protected $fillable = [
        'club_id',
        'titulo',
        'contenido',
        'imagen',
        'fecha_publicacion',
        'publicada',
    ];

    protected $casts = [
        'fecha_publicacion' => 'date',
        'publicada' => 'boolean',
    ];

    public function club()
    {
        return $this->belongsTo(Club::class);
    }
}
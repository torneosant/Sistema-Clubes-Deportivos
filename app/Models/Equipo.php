<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    protected $fillable = [
        'club_id',
        'categoria_id',
        'nombre',
        'escudo',
        'color_principal',
        'color_secundario',
        'descripcion',
        'activo',
    ];

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function jugadores()
    {
        return $this->hasMany(Jugador::class);
    }
}

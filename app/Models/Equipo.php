<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
   protected $fillable = [
    'club_id',
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

    public function jugadores()
    {
        return $this->hasMany(Jugador::class);
    }
public function entrenadores()
{
    return $this->belongsToMany(Entrenador::class);
}
public function partidos()
{
    return $this->hasMany(Partido::class);
}

public function categorias()
{
    return $this->belongsToMany(
        Categoria::class,
        'categoria_equipo'
    );
}
    }


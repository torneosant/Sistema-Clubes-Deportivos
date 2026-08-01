<?php

namespace App\Models;
use App\Models\Club;
use App\Models\Jugador;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $fillable = [
        'club_id',
        'nombre',
        'activo',
    ];

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function equipos()
    {
        return $this->hasMany(Equipo::class);
    }

    public function jugadores()
    {
        return $this->hasMany(Jugador::class);
    }
    public function entrenamientos()
{
    return $this->belongsToMany(Entrenamiento::class);
}
public function partidos()
{
    return $this->hasMany(Partido::class);
}
}
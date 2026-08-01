<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entrenamiento extends Model
{
    protected $fillable = [

        'club_id',

        'equipo_id',

        'entrenador_id',

        'fecha',

        'hora_inicio',

        'hora_fin',

        'lugar',

        'tipo',

        'estado',

        'observaciones',

        'es_recurrente',

        'dias_semana',

        'fecha_fin',

    ];

    public function equipo()
    {
        return $this->belongsTo(Equipo::class);
    }

    public function entrenador()
    {
        return $this->belongsTo(Entrenador::class);
    }

    public function club()
    {
        return $this->belongsTo(Club::class);
    }
    public function categorias()
{
    return $this->belongsToMany(Categoria::class);
}

public function asistencias()
{
    return $this->hasMany(Asistencia::class);
}

}
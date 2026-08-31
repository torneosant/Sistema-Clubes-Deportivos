<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    use HasFactory;

    protected $fillable = [
        'club_id',
        'titulo',
        'descripcion',
        'fecha_inicio',
        'hora',
        'lugar',
        'tipo',
        'recurrencia',
        'fecha_fin_recurrencia',
        'dia_recurrencia',
        'meses_recurrencia',
        'activo',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin_recurrencia' => 'date',
        'meses_recurrencia' => 'array',
        'activo' => 'boolean',
    ];


    /*
    |--------------------------------------------------------------------------
    | CLUB
    |--------------------------------------------------------------------------
    */

    public function club()
    {
        return $this->belongsTo(
            Club::class
        );
    }


    /*
    |--------------------------------------------------------------------------
    | OCURRENCIAS
    |--------------------------------------------------------------------------
    */

    public function ocurrencias()
    {
        return $this->hasMany(
            EventoOcurrencia::class
        );
    }
}
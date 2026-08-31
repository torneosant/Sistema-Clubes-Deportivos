<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventoOcurrencia extends Model
{
    use HasFactory;

    protected $table = 'evento_ocurrencias';

    protected $fillable = [
        'evento_id',
        'fecha_original',
        'fecha',
        'hora',
        'lugar',
        'modificada',
        'cancelada',
    ];

    protected $casts = [
        'fecha_original' => 'date',
        'fecha' => 'date',
        'modificada' => 'boolean',
        'cancelada' => 'boolean',
    ];


    /*
    |--------------------------------------------------------------------------
    | EVENTO
    |--------------------------------------------------------------------------
    */

    public function evento()
    {
        return $this->belongsTo(
            Evento::class
        );
    }
}
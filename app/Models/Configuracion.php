<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    protected $table = 'configuracion';

    protected $fillable = [
        'nombre_club',
        'logo',
        'nit',
        'direccion',
        'ciudad',
        'departamento',
        'pais',
        'telefono',
        'whatsapp',
        'correo',
        'pagina_web',
        'facebook',
        'instagram',
        'tiktok',
        'youtube',
        'temporada',
        'anio',
        'color_principal',
        'color_secundario',
        'zona_horaria',
        'idioma',
        'moneda',

        // Configuración del calendario
        'calendario_partidos',
        'calendario_entrenamientos',
        'calendario_cumpleanos',
        'calendario_eventos',
    ];

    protected $casts = [
        'calendario_partidos' => 'boolean',
        'calendario_entrenamientos' => 'boolean',
        'calendario_cumpleanos' => 'boolean',
        'calendario_eventos' => 'boolean',
    ];
}

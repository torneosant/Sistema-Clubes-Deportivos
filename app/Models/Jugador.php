<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Club;
use App\Models\Categoria;
use App\Models\Equipo;
use App\Models\HistorialMedico;

class Jugador extends Model
{
    protected $table = 'jugadores';
    protected $fillable = [

        'club_id',

        'nombres',
        'apellidos',

        'tipo_documento',
        'numero_documento',

        'fecha_nacimiento',

        'genero',

        'telefono',
        'email',
        'direccion',
        'ciudad',

        'categoria_id', 
        'equipo_id',
        'posicion',
        'pierna_habil',

        'estatura',
        'peso',

        'eps',
        'tipo_sangre',
        'alergias',
        'observaciones_medicas',

        'acudiente',
        'telefono_acudiente',
        'parentesco',
        'documento_acudiente',
        'email_acudiente',

        'foto',

        'activo',
        'estado'

    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'activo' => 'boolean',
    ];


    public function historialesMedicos()
{
    return $this->hasMany(HistorialMedico::class);
}

  public function club()
{
    return $this->belongsTo(Club::class);
}

public function categoria()
{
    return $this->belongsTo(Categoria::class);
}

public function equipo()
{
    return $this->belongsTo(Equipo::class);
}

public function partidos()
{
    return $this->hasMany(PartidoJugador::class);
}

public function asistencias()
{
    return $this->hasMany(Asistencia::class);
}

public function estadisticasPartidos()
{
    return $this->hasMany(PartidoJugador::class);
}

public function user()
{
    return $this->hasOne(User::class);
}

public function documentos()
{
    return $this->hasMany(Documento::class);
}

public function competencias()
{
    return $this->belongsToMany(
        Competencia::class,
        'competencia_jugadores',
        'jugador_id',
        'competencia_id'
    )
    ->withPivot([
        'es_refuerzo',
        'categoria_origen_id',
        'observaciones',
    ])
    ->withTimestamps();
}

public function cargos()
{
    return $this->hasMany(
        CargoJugador::class,
        'jugador_id'
    );
}

public function pagos()
{
    return $this->hasMany(
        Contabilidad::class,
        'jugador_id'
    )->where('tipo', 'Ingreso');
}

}
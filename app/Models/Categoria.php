<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $fillable = [
        'club_id',
        'nombre',
        'activo',
    ];
    public function equipos()
{
    return $this->hasMany(Equipo::class);
}
}
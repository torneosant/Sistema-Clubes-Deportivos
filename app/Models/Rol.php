<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'roles';

    protected $fillable = [
        'nombre',
        'activo'
    ];

    public function usuarios()
    {
        return $this->hasMany(User::class);
    }

    public function permisos()
    {
        return $this->belongsToMany(
            Permiso::class,
            'permiso_rol'
        );
    }
}
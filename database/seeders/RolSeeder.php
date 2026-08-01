<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rol;

class RolSeeder extends Seeder
{
    public function run(): void
    {
       $roles = [
    'Administrador',
    'Entrenador',
    'Médico',
    'Tesorero',
    'Secretaria',
    'Consulta',
    'Deportista'
];

        foreach ($roles as $rol) {

            Rol::firstOrCreate([
                'nombre' => $rol
            ]);

        }
    }
}
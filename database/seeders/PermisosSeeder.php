<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permiso;

class PermisosSeeder extends Seeder
{
    public function run(): void
    {
        $permisos = [

            ['nombre'=>'Dashboard','slug'=>'dashboard'],
            ['nombre'=>'Club','slug'=>'club'],
            ['nombre'=>'Jugadores','slug'=>'jugadores'],
            ['nombre'=>'Categorías','slug'=>'categorias'],
            ['nombre'=>'Equipos','slug'=>'equipos'],
            ['nombre'=>'Entrenadores','slug'=>'entrenadores'],
            ['nombre'=>'Entrenamientos','slug'=>'entrenamientos'],
            ['nombre'=>'Asistencia','slug'=>'asistencias'],
            ['nombre'=>'Partidos','slug'=>'partidos'],
            ['nombre'=>'Contabilidad','slug'=>'contabilidad'],
            ['nombre'=>'Calendario','slug'=>'calendario'],
            ['nombre'=>'Historial Médico','slug'=>'historial-medico'],
            ['nombre'=>'Configuración','slug'=>'configuracion'],
            ['nombre'=>'Usuarios','slug'=>'usuarios'],
            ['nombre'=>'Roles','slug'=>'roles'],
            ['nombre'=>'Reportes','slug'=>'reportes'],

        ];

        foreach ($permisos as $permiso) {

            Permiso::firstOrCreate(
                ['slug' => $permiso['slug']],
                [
                    'nombre' => $permiso['nombre'],
                    'activo' => true
                ]
            );

        }
    }
}

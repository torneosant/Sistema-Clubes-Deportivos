<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rol;
use App\Models\Permiso;

class RolesPermisosSeeder extends Seeder
{
    public function run()
    {
        $roles = Rol::all();

        foreach($roles as $rol){

            switch($rol->nombre){

                case 'Administrador':

                    $rol->permisos()->sync(
                        Permiso::pluck('id')->toArray()
                    );

                break;

                case 'Consulta':

                    $rol->permisos()->sync(

                        Permiso::where('slug','like','%.ver')
                            ->pluck('id')
                            ->toArray()

                    );

                break;

                case 'Entrenador':

                    $rol->permisos()->sync(

                        Permiso::where(function($q){

                            $q->whereIn('slug',[

                                'jugadores.ver',
                                'jugadores.crear',
                                'jugadores.editar',

                                'equipos.ver',
                                'equipos.crear',
                                'equipos.editar',

                                'entrenadores.ver',

                                'entrenamientos.ver',
                                'entrenamientos.crear',
                                'entrenamientos.editar',

                                'asistencias.ver',
                                'asistencias.crear',
                                'asistencias.editar',

                                'partidos.ver',
                                'partidos.crear',
                                'partidos.editar',

                                'inventario.ver',

                                'calendario.ver',

                                'noticias.ver',
'noticias.crear',
'noticias.editar',
'noticias.eliminar',

                            ]);

                        })->pluck('id')->toArray()

                    );

                break;

                case 'Tesorero':

    $rol->permisos()->sync(

        Permiso::where(function ($q) {

            $q->where('slug', 'like', 'contabilidad.%')
              ->orWhere('slug', 'like', 'conceptos_contables.%');

        })->pluck('id')->toArray()

    );

break;

                case 'Médico':

                    $rol->permisos()->sync(

                        Permiso::where(function($q){

                            $q->where('slug','like','historial-medico.%')
                              ->orWhere('slug','like','jugadores.ver');

                        })->pluck('id')->toArray()

                    );

                break;

            }

        }

        $this->command->info('Roles configurados correctamente.');

    }
}
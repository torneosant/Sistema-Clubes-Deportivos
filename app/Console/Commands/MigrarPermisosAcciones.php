<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Permiso;

class MigrarPermisosAcciones extends Command
{
    protected $signature = 'permisos:migrar';

    protected $description = 'Crear permisos por acción';

    public function handle()
    {
        $acciones = [
            'ver',
            'crear',
            'editar',
            'eliminar'
        ];

        $permisos = Permiso::all();

        foreach($permisos as $permiso){

            foreach($acciones as $accion){

                Permiso::firstOrCreate(

                    [
                        'slug'=>$permiso->slug.'.'.$accion
                    ],

                    [
                        'nombre'=>$permiso->nombre.' '.ucfirst($accion),
                        'activo'=>1
                    ]

                );

            }

        }

        $this->info('Permisos creados correctamente.');
    }
}
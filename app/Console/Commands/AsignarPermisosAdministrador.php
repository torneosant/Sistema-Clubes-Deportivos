<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Rol;
use App\Models\Permiso;

class AsignarPermisosAdministrador extends Command
{
    protected $signature = 'permisos:Administrador';

    protected $description = 'Asignar todos los permisos al rol Administrador';

    public function handle()
    {
        $rol = Rol::where('nombre','Administrador')->first();

        if(!$rol){

            $this->error('No existe el rol administrador.');

            return;

        }

        $rol->permisos()->sync(

            Permiso::pluck('id')->toArray()

        );

        $this->info('Todos los permisos fueron asignados correctamente.');

    }
}
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
            ['nombre'=>'Conceptos Contables - Ver','slug'=>'conceptos_contables.ver'],
            ['nombre'=>'Conceptos Contables - Crear','slug'=>'conceptos_contables.crear'],
            ['nombre'=>'Conceptos Contables - Editar','slug'=>'conceptos_contables.editar'],
            ['nombre'=>'Conceptos Contables - Eliminar','slug'=>'conceptos_contables.eliminar'],
            ['nombre'=>'Calendario','slug'=>'calendario'],
            ['nombre'=>'Historial Médico','slug'=>'historial-medico'],
            ['nombre'=>'Configuración','slug'=>'configuracion'],
            ['nombre'=>'Usuarios','slug'=>'usuarios'],
            ['nombre'=>'Roles','slug'=>'roles'],
            ['nombre'=>'Reportes','slug'=>'reportes'],
               ['nombre'=>'documentacion','slug'=>'documentacion'],
                  ['nombre'=>'tipos_documento','slug'=>'tipos_documento'],
         ['nombre'=>'Inventario','slug'=>'inventario'],
         ['nombre'=>'Tipos de Artículos','slug'=>'tipos_articulo'],
         ['nombre'=>'Asignaciones de Inventario','slug'=>'asignaciones_inventario'],
       ['nombre'=>'Noticias - Ver','slug'=>'noticias.ver'],
['nombre'=>'Noticias - Crear','slug'=>'noticias.crear'],
['nombre'=>'Noticias - Editar','slug'=>'noticias.editar'],
['nombre'=>'Noticias - Eliminar','slug'=>'noticias.eliminar'],
 ['nombre'=>'Inscripciones - Ver','slug'=>'inscripciones.ver'],
['nombre'=>'Inscripciones - Crear','slug'=>'inscripciones.crear'],
['nombre'=>'Inscripciones - Editar','slug'=>'inscripciones.editar'],
['nombre'=>'Inscripciones - Eliminar','slug'=>'inscripciones.eliminar'],
['nombre'=>'Inscripciones - Aprobar','slug'=>'inscripciones.aprobar'],
['nombre'=>'Inscripciones - Denegar','slug'=>'inscripciones.denegar'],

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

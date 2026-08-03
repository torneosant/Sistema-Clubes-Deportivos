<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipoArticulo;

class ConfiguracionInicialSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = [

            'Balones',
            'Conos',
            'Platos de marcación',
            'Estacas',
            'Escaleras de coordinación',
            'Vallas',
            'Petos',
            'Arcos móviles',
            'Redes',

            'Mancuernas',
            'Bandas elásticas',
            'Colchonetas',

            'Botiquín',
            'Material médico',

            'Uniformes de juego',
            'Uniformes de entrenamiento',
            'Guantes',
            'Medias',

            'Equipos tecnológicos',
            'Papelería',
            'Mobiliario'

        ];

        foreach ($tipos as $tipo) {

            TipoArticulo::firstOrCreate([
                'nombre' => $tipo
            ],[
                'activo' => true
            ]);

        }
    }
}
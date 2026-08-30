<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('becas_jugadores', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Club
            |--------------------------------------------------------------------------
            */

            $table->foreignId('club_id')
                ->constrained('clubs')
                ->cascadeOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Jugador beneficiario
            |--------------------------------------------------------------------------
            */

            $table->foreignId('jugador_id')
                ->constrained('jugadores')
                ->cascadeOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Concepto al que aplica la beca
            |--------------------------------------------------------------------------
            |
            | Ejemplo:
            | Mensualidad
            | Uniforme
            | Inscripción
            |
            */

            $table->foreignId('concepto_contable_id')
                ->constrained('concepto_contables')
                ->cascadeOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Vigencia
            |--------------------------------------------------------------------------
            |
            | Permite configurar:
            |
            | Año completo
            | Medio año
            | Algunos meses
            | Cualquier periodo personalizado
            |
            */

            $table->date('fecha_inicio');

            $table->date('fecha_fin');


            /*
            |--------------------------------------------------------------------------
            | Porcentaje de descuento
            |--------------------------------------------------------------------------
            |
            | 100 = no paga
            | 50  = paga el 50%
            | 25  = paga el 75%
            |
            */

            $table->decimal('porcentaje', 5, 2)
                ->default(100);


            /*
            |--------------------------------------------------------------------------
            | Motivo
            |--------------------------------------------------------------------------
            */

            $table->text('motivo')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | Estado
            |--------------------------------------------------------------------------
            */

            $table->boolean('activo')
                ->default(true);


            $table->timestamps();


            /*
            |--------------------------------------------------------------------------
            | Índice
            |--------------------------------------------------------------------------
            |
            | Nombre corto para evitar superar el límite de MySQL.
            |
            */

            $table->index(
                [
                    'jugador_id',
                    'concepto_contable_id'
                ],
                'becas_jug_concepto_idx'
            );

        });
    }


    public function down(): void
    {
        Schema::dropIfExists('becas_jugadores');
    }
};
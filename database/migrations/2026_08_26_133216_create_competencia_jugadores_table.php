<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competencia_jugadores', function (Blueprint $table) {

            $table->id();

            $table->foreignId('competencia_id')
                ->constrained('competencias')
                ->cascadeOnDelete();

            $table->foreignId('jugador_id')
                ->constrained('jugadores')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Información de participación
            |--------------------------------------------------------------------------
            */

            $table->boolean('es_refuerzo')
                ->default(false);

            /*
            | Categoría a la que pertenece realmente el jugador.
            | Esto permite que un jugador U17 participe como refuerzo
            | en una competencia U21.
            */

            $table->foreignId('categoria_origen_id')
                ->nullable()
                ->constrained('categorias')
                ->nullOnDelete();

            $table->text('observaciones')
                ->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Un jugador no puede estar dos veces en la misma competencia.
            |--------------------------------------------------------------------------
            */

            $table->unique(
                ['competencia_id', 'jugador_id'],
                'comp_jug_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competencia_jugadores');
    }
};
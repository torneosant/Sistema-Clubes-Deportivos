<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eventos', function (Blueprint $table) {

            $table->id();

            $table->foreignId('club_id')
                ->constrained('clubs')
                ->cascadeOnDelete();

            $table->string('titulo');

            $table->text('descripcion')
                ->nullable();

            $table->date('fecha_inicio');

            $table->time('hora')
                ->nullable();

            $table->string('lugar')
                ->nullable();

            $table->string('tipo')
                ->default('General');

            /*
            |--------------------------------------------------------------------------
            | RECURRENCIA
            |--------------------------------------------------------------------------
            */

            $table->enum('recurrencia', [
                'unico',
                'mensual',
                'meses',
            ])->default('unico');

            $table->date('fecha_fin_recurrencia')
                ->nullable();

            /*
            | Día del mes para eventos recurrentes.
            |
            | Ejemplo: 5 = día 5 de cada mes.
            */

            $table->unsignedTinyInteger('dia_recurrencia')
                ->nullable();

            /*
            | Meses seleccionados.
            |
            | Ejemplo:
            | [1, 3, 5, 7, 9, 11]
            */

            $table->json('meses_recurrencia')
                ->nullable();

            $table->boolean('activo')
                ->default(true);

            $table->timestamps();

            $table->index([
                'club_id',
                'fecha_inicio',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eventos');
    }
};
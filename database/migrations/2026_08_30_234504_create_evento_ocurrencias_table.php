<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evento_ocurrencias', function (Blueprint $table) {

            $table->id();

            $table->foreignId('evento_id')
                ->constrained('eventos')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | FECHA ORIGINAL
            |--------------------------------------------------------------------------
            |
            | Identifica la ocurrencia dentro de la serie.
            |
            | Ejemplo:
            |
            | fecha_original = 05/10/2026
            | fecha          = 08/10/2026
            |
            */

            $table->date('fecha_original');

            $table->date('fecha');

            $table->time('hora')
                ->nullable();

            $table->string('lugar')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | ESTADO
            |--------------------------------------------------------------------------
            */

            $table->boolean('modificada')
                ->default(false);

            $table->boolean('cancelada')
                ->default(false);

            $table->timestamps();

            $table->unique([
                'evento_id',
                'fecha_original',
            ]);

            $table->index([
                'evento_id',
                'fecha',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evento_ocurrencias');
    }
};
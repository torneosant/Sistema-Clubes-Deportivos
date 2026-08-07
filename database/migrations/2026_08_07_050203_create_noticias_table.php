<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('noticias', function (Blueprint $table) {

            $table->id();

            // Club propietario de la noticia
            $table->foreignId('club_id')
                ->constrained('clubs')
                ->cascadeOnDelete();

            // Información de la noticia
            $table->string('titulo');
            $table->text('contenido');

            // Imagen opcional
            $table->string('imagen')->nullable();

            // Fecha de publicación
            $table->date('fecha_publicacion')->nullable();

            // Publicada o borrador
            $table->boolean('publicada')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('noticias');
    }
};  
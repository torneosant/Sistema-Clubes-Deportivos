<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documentos_club', function (Blueprint $table) {

            $table->id();

            $table->foreignId('club_id')
                ->constrained('clubs')
                ->cascadeOnDelete();

            $table->foreignId('tipo_documento_club_id')
                ->constrained('tipos_documentos_club')
                ->cascadeOnDelete();

            $table->string('titulo');

            $table->text('descripcion')
                ->nullable();

            $table->string('archivo');

            $table->date('fecha')
                ->nullable();

            $table->boolean('activo')
                ->default(true);

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documentos_club');
    }
};

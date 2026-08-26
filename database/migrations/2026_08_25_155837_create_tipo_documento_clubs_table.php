<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipos_documentos_club', function (Blueprint $table) {

            $table->id();

            $table->foreignId('club_id')
                ->constrained('clubs')
                ->cascadeOnDelete();

            $table->string('nombre');

            $table->text('descripcion')
                ->nullable();

            $table->boolean('activo')
                ->default(true);

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipos_documentos_club');
    }
};

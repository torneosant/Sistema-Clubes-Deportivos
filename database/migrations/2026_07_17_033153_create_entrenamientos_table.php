<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('entrenamientos', function (Blueprint $table) {

        $table->id();

        $table->foreignId('club_id')->constrained()->cascadeOnDelete();

        $table->foreignId('equipo_id')->constrained()->cascadeOnDelete();

        $table->foreignId('entrenador_id')->constrained()->cascadeOnDelete();

        $table->date('fecha');

        $table->time('hora_inicio');

        $table->time('hora_fin')->nullable();

        $table->string('lugar')->nullable();

        $table->string('tipo')->nullable();

        $table->string('estado')->default('Programado');

        $table->text('observaciones')->nullable();

        $table->timestamps();

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::dropIfExists('entrenamientos');
}
};

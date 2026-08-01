<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asistencias', function (Blueprint $table) {

           $table->id();

$table->foreignId('entrenamiento_id')
    ->constrained()
    ->cascadeOnDelete();

$table->foreignId('jugador_id')
    ->constrained('jugadores')
    ->cascadeOnDelete();

$table->enum('estado', [
    'Presente',
    'Ausente',
    'Permiso',
    'Incapacidad'
])->default('Presente');

$table->text('observacion')->nullable();

$table->unique([
    'entrenamiento_id',
    'jugador_id'
]);

$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asistencias');
    }
};

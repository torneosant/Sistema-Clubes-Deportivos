<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historial_medicos', function (Blueprint $table) {

            $table->id();

            $table->foreignId('club_id')->constrained()->cascadeOnDelete();

            $table->foreignId('jugador_id')
                  ->constrained('jugadores')
                  ->cascadeOnDelete();

            $table->date('fecha');

            $table->string('tipo',100);

            $table->string('zona',100)->nullable();

            $table->text('diagnostico')->nullable();

            $table->text('tratamiento')->nullable();

            $table->integer('dias_incapacidad')->default(0);

            $table->date('fecha_alta')->nullable();

            $table->enum('estado',[
                'Activo',
                'En recuperación',
                'Alta médica'
            ])->default('Activo');

            $table->text('observaciones')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historial_medicos');
    }
};

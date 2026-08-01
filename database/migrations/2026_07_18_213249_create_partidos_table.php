<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('partidos', function (Blueprint $table) {

            $table->id();

            $table->foreignId('club_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('equipo_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('categoria_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->date('fecha');

            $table->time('hora');

            $table->string('competencia')->nullable();

            $table->string('rival');

            $table->string('lugar')->nullable();

            $table->enum('condicion',[
                'Local',
                'Visitante'
            ]);

            $table->integer('goles_favor')->default(0);

            $table->integer('goles_contra')->default(0);

            $table->enum('estado',[
                'Programado',
                'Jugado',
                'Aplazado',
                'Cancelado'
            ])->default('Programado');

            $table->text('observaciones')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partidos');
    }
};
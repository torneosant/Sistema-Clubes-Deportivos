<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competencias', function (Blueprint $table) {

            $table->id();

            $table->foreignId('club_id')
                ->constrained('clubs')
                ->cascadeOnDelete();

            $table->foreignId('categoria_id')
                ->nullable()
                ->constrained('categorias')
                ->nullOnDelete();

            $table->string('nombre');

            $table->enum('tipo', [
                'campeonato',
                'festival',
                'evento',
            ])->default('campeonato');

            $table->enum('estado', [
                'proximo',
                'en_curso',
                'finalizado',
                'cancelado',
            ])->default('proximo');

            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();

            $table->string('lugar')->nullable();

            $table->text('descripcion')->nullable();

            $table->boolean('activo')->default(true);

            $table->timestamps();

            $table->index([
                'club_id',
                'estado',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competencias');
    }
};
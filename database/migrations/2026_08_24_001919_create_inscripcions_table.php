<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inscripciones', function (Blueprint $table) {

            $table->id();

            // Club al que pertenece la solicitud
            $table->foreignId('club_id')
                ->constrained('clubs')
                ->cascadeOnDelete();

            // Categoría solicitada
            $table->foreignId('categoria_id')
                ->nullable()
                ->constrained('categorias')
                ->nullOnDelete();

            // Jugador creado después de aprobar
            $table->foreignId('jugador_id')
                ->nullable()
                ->constrained('jugadores')
                ->nullOnDelete();

            // Token único utilizado para el enlace / QR
            $table->string('token', 100)->unique();

            // Datos personales
           $table->string('nombres')->nullable();
           $table->string('apellidos')->nullable();
            $table->string('documento', 50)->nullable();
            $table->date('fecha_nacimiento')->nullable();

            // Contacto
            $table->string('telefono', 50)->nullable();
            $table->string('email')->nullable();
            $table->text('direccion')->nullable();

            // Información adicional
            $table->string('posicion', 100)->nullable();
            $table->string('club_anterior')->nullable();
            $table->text('observaciones')->nullable();

            // Estado de la solicitud
            $table->string('estado')->default('Pendiente');

            // Si el administrador la rechaza
            $table->text('motivo_denegacion')->nullable();

            // Revisión administrativa
            $table->timestamp('fecha_revision')->nullable();

            $table->foreignId('revisado_por')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inscripciones');
    }
};
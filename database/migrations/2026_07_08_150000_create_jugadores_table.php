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
        Schema::create('jugadores', function (Blueprint $table) {

            $table->id();

            // Relación con el club
            $table->foreignId('club_id')->constrained()->cascadeOnDelete();

            // Datos personales
            $table->string('nombres');
            $table->string('apellidos');

            $table->string('tipo_documento')->nullable();
            $table->string('numero_documento')->nullable()->unique();

            $table->date('fecha_nacimiento')->nullable();

            $table->enum('genero', ['Masculino', 'Femenino'])->nullable();

            // Contacto
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->string('direccion')->nullable();
            $table->string('ciudad')->nullable();

            // Deportiva
            $table->foreignId('categoria_id')
      ->nullable()
      ->constrained('categorias')
      ->nullOnDelete();

$table->foreignId('equipo_id')
      ->nullable()
      ->constrained('equipos')
      ->nullOnDelete();
            $table->string('posicion')->nullable();
            $table->string('pierna_habil')->nullable();

            $table->unsignedSmallInteger('estatura')->nullable();
            $table->decimal('peso', 5, 2)->nullable();

            // Médica
            $table->string('eps')->nullable();
            $table->string('tipo_sangre')->nullable();
            $table->text('alergias')->nullable();
            $table->text('observaciones_medicas')->nullable();

            // Acudiente
            $table->string('acudiente')->nullable();
            $table->string('telefono_acudiente')->nullable();
            $table->string('parentesco')->nullable();

            // Imagen
            $table->string('foto')->nullable();

            // Estado
            $table->boolean('activo')->default(true);
            $table->enum('estado', [
            'Activo',
            'Lesionado',
            'Suspendido',
            'Retirado'
])->default('Activo');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jugadores');
    }
};
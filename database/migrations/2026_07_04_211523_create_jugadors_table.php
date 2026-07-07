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
        Schema::create('jugadors', function (Blueprint $table) {

            $table->id();

            // Relación con el club
            $table->foreignId('club_id')->constrained()->cascadeOnDelete();

            // Datos personales
            $table->string('nombres');
            $table->string('apellidos');

            $table->string('tipo_documento')->nullable();
            $table->string('numero_documento')->nullable();

            $table->date('fecha_nacimiento')->nullable();

            $table->enum('genero', ['Masculino', 'Femenino'])->nullable();

            // Contacto
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->string('direccion')->nullable();
            $table->string('ciudad')->nullable();

            // Deportiva
            $table->string('categoria')->nullable();
            $table->string('equipo')->nullable();
            $table->string('posicion')->nullable();

            $table->decimal('estatura', 4, 2)->nullable();
            $table->decimal('peso', 5, 2)->nullable();

            // Médica
            $table->string('eps')->nullable();
            $table->string('tipo_sangre')->nullable();
            $table->text('alergias')->nullable();

            // Acudiente
            $table->string('acudiente')->nullable();
            $table->string('telefono_acudiente')->nullable();
            $table->string('parentesco')->nullable();

            // Imagen
            $table->string('foto')->nullable();

            // Estado
            $table->boolean('activo')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jugadors');
    }
};
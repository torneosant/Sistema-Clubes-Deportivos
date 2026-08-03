<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asignacion_inventarios', function (Blueprint $table) {

            $table->id();

            $table->foreignId('inventario_id')
                  ->constrained('inventarios')
                  ->cascadeOnDelete();

            $table->string('tipo_destino');

            $table->foreignId('entrenador_id')
                  ->nullable()
                  ->constrained('entrenadors')
                  ->nullOnDelete();

            $table->string('destino_otro')->nullable();

            $table->integer('cantidad');

            $table->date('fecha');

            $table->text('observaciones')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asignacion_inventarios');
    }
};
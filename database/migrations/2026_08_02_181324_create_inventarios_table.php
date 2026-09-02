<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventarios', function (Blueprint $table) {

            $table->id();

            $table->foreignId('club_id')
                  ->constrained('clubs')
                  ->cascadeOnDelete();

            $table->string('nombre');

            $table->string('codigo')->nullable();

            $table->foreignId('tipo_articulo_id')
                  ->constrained('tipo_articulos');

            $table->string('marca')->nullable();

            $table->integer('cantidad')->default(0);

            $table->string('estado')->default('Bueno');

            $table->string('ubicacion')->nullable();

            $table->text('observaciones')->nullable();

            $table->boolean('activo')->default(true);

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventarios');
    }
};
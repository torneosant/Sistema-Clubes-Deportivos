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
    Schema::create('entrenador_categoria', function (Blueprint $table) {

        $table->id();

        $table->foreignId('entrenador_id')
              ->constrained('entrenadors')
              ->cascadeOnDelete();

        $table->foreignId('categoria_id')
              ->constrained()
              ->cascadeOnDelete();

        $table->timestamps();

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entrenador_categoria');
    }
};

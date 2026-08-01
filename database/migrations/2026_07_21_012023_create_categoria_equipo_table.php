<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categoria_equipo', function (Blueprint $table) {

            $table->id();

            $table->foreignId('equipo_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('categoria_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categoria_equipo');
    }
};

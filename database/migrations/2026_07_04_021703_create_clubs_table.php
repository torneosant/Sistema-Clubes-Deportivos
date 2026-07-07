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
        Schema::create('clubs', function (Blueprint $table) {

            $table->id();

            $table->string('nombre');
            $table->string('slug')->unique();

            $table->string('email')->nullable();
            $table->string('telefono', 30)->nullable();

            $table->string('ciudad')->nullable();
            $table->string('departamento')->nullable();
            $table->string('pais')->default('Colombia');

            $table->string('direccion')->nullable();

            $table->string('logo')->nullable();

            $table->text('descripcion')->nullable();

            $table->boolean('activo')->default(true);

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clubs');
    }
};

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
    Schema::create('equipos', function (Blueprint $table) {

        $table->id();

        $table->foreignId('club_id')->constrained()->cascadeOnDelete();

        $table->foreignId('categoria_id')->constrained()->cascadeOnDelete();

        $table->string('nombre');

        $table->string('escudo')->nullable();

        $table->string('color_principal')->nullable();

        $table->string('color_secundario')->nullable();

        $table->text('descripcion')->nullable();

        $table->boolean('activo')->default(true);

        $table->timestamps();

    });
}
    public function down(): void
    {
        Schema::dropIfExists('equipos');
    }
};

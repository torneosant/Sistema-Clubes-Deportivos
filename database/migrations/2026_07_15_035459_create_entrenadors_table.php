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
    Schema::create('entrenadors', function (Blueprint $table) {
       $table->id();

$table->foreignId('club_id')->constrained()->cascadeOnDelete();

$table->string('nombres');
$table->string('apellidos');

$table->string('numero_documento')->nullable();

$table->date('fecha_nacimiento')->nullable();

$table->string('telefono')->nullable();
$table->string('email')->nullable();

$table->string('direccion')->nullable();
$table->string('ciudad')->nullable();

$table->string('cargo')->nullable();
$table->string('licencia')->nullable();

$table->date('fecha_ingreso')->nullable();

$table->string('foto')->nullable();

$table->text('observaciones')->nullable();

$table->boolean('activo')->default(true);

$table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entrenadors');
    }
};

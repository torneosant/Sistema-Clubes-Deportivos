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
    Schema::create('movimiento_inventarios', function (Blueprint $table) {

        $table->id();

        $table->foreignId('inventario_id')
            ->constrained('inventarios')
            ->cascadeOnDelete();

        $table->foreignId('asignacion_id')
            ->nullable()
            ->constrained('asignacion_inventarios')
            ->nullOnDelete();

        $table->enum('tipo',['Entrega','Devolucion']);

        $table->integer('cantidad');

        $table->date('fecha');

        $table->string('responsable')->nullable();

        $table->text('observaciones')->nullable();

        $table->timestamps();

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimiento_inventarios');
    }
};

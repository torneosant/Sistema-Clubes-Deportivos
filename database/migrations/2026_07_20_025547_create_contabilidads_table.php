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
    Schema::create('contabilidad', function (Blueprint $table) {

        $table->id();
$table->foreignId('club_id')
    ->nullable()
    ->constrained()
    ->nullOnDelete();

$table->foreignId('jugador_id')
    ->nullable()
    ->constrained('jugadores')
    ->nullOnDelete();

$table->foreignId('concepto_contable_id')
    ->constrained('concepto_contables')
    ->cascadeOnDelete();

$table->date('fecha');

$table->string('periodo')->nullable();

$table->enum('tipo', [
    'Ingreso',
    'Egreso'
]);

$table->string('tercero')->nullable();

$table->decimal('valor', 12, 2);

$table->string('metodo_pago')->nullable();

$table->enum('estado', [
    'Pendiente',
    'Pagado',
    'Anulado'
])->default('Pagado');

$table->text('observaciones')->nullable();

$table->timestamps();

}); 

}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contabilidad');
    }

  
};

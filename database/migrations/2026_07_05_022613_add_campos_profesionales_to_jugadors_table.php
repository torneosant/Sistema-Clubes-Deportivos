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
    Schema::table('jugadors', function (Blueprint $table) {

        // Información deportiva
        $table->string('pierna_habil')->nullable()->after('posicion');

        // Información médica
        $table->text('observaciones_medicas')->nullable()->after('alergias');

        // Estado del jugador
        $table->enum('estado', [
            'Activo',
            'Lesionado',
            'Suspendido',
            'Retirado'
        ])->default('Activo')->after('activo');

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('jugadors', function (Blueprint $table) {

        $table->dropColumn([
            'pierna_habil',
            'observaciones_medicas',
            'estado'
        ]);

    });
}   
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contabilidad', function (Blueprint $table) {

            $table->foreignId('cargo_jugador_id')
                ->nullable()
                ->after('jugador_id')
                ->constrained('cargos_jugadores')
                ->nullOnDelete();

        });
    }

    public function down(): void
    {
        Schema::table('contabilidad', function (Blueprint $table) {

            $table->dropForeign([
                'cargo_jugador_id'
            ]);

            $table->dropColumn('cargo_jugador_id');

        });
    }
};
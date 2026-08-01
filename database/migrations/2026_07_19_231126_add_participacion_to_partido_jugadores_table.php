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
    Schema::table('partido_jugadores', function (Blueprint $table) {

        $table->enum('participacion', [
            'Titular',
            'Suplente',
            'No jugó'
        ])->default('No jugó')->after('jugador_id');

    });
}

public function down(): void
{
    Schema::table('partido_jugadores', function (Blueprint $table) {

        $table->dropColumn('participacion');

    });
}   
};

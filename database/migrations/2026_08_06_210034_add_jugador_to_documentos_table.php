<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('documentos', function (Blueprint $table) {

        $table->foreign('jugador_id')
              ->references('id')
              ->on('jugadores')
              ->cascadeOnDelete();

    });
}

    public function down(): void
    {
        Schema::table('documentos', function (Blueprint $table) {

            $table->dropForeign(['jugador_id']);
            $table->dropColumn('jugador_id');

        });
    }
};
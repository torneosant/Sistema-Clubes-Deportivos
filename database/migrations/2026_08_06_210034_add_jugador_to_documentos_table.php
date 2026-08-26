<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documentos', function (Blueprint $table) {

            $table->foreignId('jugador_id')
                ->nullable()
                ->after('id')
                ->constrained('jugadores')
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
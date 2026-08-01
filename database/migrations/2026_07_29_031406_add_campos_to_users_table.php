<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::table('users', function (Blueprint $table) {

        $table->foreignId('jugador_id')
            ->nullable()
            ->after('rol_id')
            ->constrained('jugadores');

        $table->boolean('activo')
            ->default(true)
            ->after('jugador_id');

    });
}

    public function down(): void
{
    Schema::table('users', function (Blueprint $table) {

        $table->dropForeign(['jugador_id']);

        $table->dropColumn([
            'jugador_id',
            'activo'
        ]);

    });
}
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('partidos', function (Blueprint $table) {
            $table->foreignId('competencia_id')
                ->nullable()
                ->after('categoria_id')
                ->constrained('competencias')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('partidos', function (Blueprint $table) {
            $table->dropForeign(['competencia_id']);
            $table->dropColumn('competencia_id');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('entrenamientos', function (Blueprint $table) {

            $table->boolean('es_recurrente')
                  ->default(false)
                  ->after('estado');

            $table->json('dias_semana')
                  ->nullable()
                  ->after('es_recurrente');

            $table->date('fecha_fin')
                  ->nullable()
                  ->after('fecha');

        });
    }

    public function down(): void
    {
        Schema::table('entrenamientos', function (Blueprint $table) {

            $table->dropColumn([
                'es_recurrente',
                'dias_semana',
                'fecha_fin'
            ]);

        });
    }
};

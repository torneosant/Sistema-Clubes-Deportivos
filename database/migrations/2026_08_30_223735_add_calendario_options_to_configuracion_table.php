<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('configuracion', function (Blueprint $table) {

            $table->boolean('calendario_partidos')
                ->default(true)
                ->after('moneda');

            $table->boolean('calendario_entrenamientos')
                ->default(true)
                ->after('calendario_partidos');

            $table->boolean('calendario_cumpleanos')
                ->default(true)
                ->after('calendario_entrenamientos');

            $table->boolean('calendario_eventos')
                ->default(true)
                ->after('calendario_cumpleanos');

        });
    }

    public function down(): void
    {
        Schema::table('configuracion', function (Blueprint $table) {

            $table->dropColumn([
                'calendario_partidos',
                'calendario_entrenamientos',
                'calendario_cumpleanos',
                'calendario_eventos',
            ]);

        });
    }
};

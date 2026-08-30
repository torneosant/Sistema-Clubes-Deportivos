<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('becas_jugadores', function (Blueprint $table) {

            $table->date('fecha_fin')
                ->nullable()
                ->change();

        });
    }

    public function down(): void
    {
        Schema::table('becas_jugadores', function (Blueprint $table) {

            $table->date('fecha_fin')
                ->nullable(false)
                ->change();

        });
    }
};
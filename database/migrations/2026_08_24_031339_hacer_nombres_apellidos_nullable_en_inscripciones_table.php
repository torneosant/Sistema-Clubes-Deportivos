<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inscripciones', function (Blueprint $table) {

            $table->string('nombres')->nullable()->change();

            $table->string('apellidos')->nullable()->change();

        });
    }

    public function down(): void
    {
        Schema::table('inscripciones', function (Blueprint $table) {

            $table->string('nombres')->nullable(false)->change();

            $table->string('apellidos')->nullable(false)->change();

        });
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jugadors', function (Blueprint $table) {

            $table->unique('numero_documento');

        });
    }

    public function down(): void
    {
        Schema::table('jugadors', function (Blueprint $table) {

            $table->dropUnique(['numero_documento']);

        });
    }
};

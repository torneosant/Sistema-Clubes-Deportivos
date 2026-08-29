<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('concepto_contables', function (Blueprint $table) {
            $table->decimal('valor_predeterminado', 12, 2)
                ->nullable()
                ->after('descripcion');
        });
    }

    public function down(): void
    {
        Schema::table('concepto_contables', function (Blueprint $table) {
            $table->dropColumn('valor_predeterminado');
        });
    }
};
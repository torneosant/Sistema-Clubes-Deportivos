<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('concepto_contables', function (Blueprint $table) {

            $table->boolean('genera_cobro')
                ->default(false)
                ->after('descripcion');

            $table->enum('tipo_cobro', [
                'Unico',
                'Mensual'
            ])
                ->nullable()
                ->after('genera_cobro');

            $table->decimal('valor_cobro', 12, 2)
                ->nullable()
                ->after('tipo_cobro');

            $table->unsignedTinyInteger('dia_cobro')
                ->nullable()
                ->after('valor_cobro');

            $table->date('fecha_maxima')
                ->nullable()
                ->after('dia_cobro');

            $table->date('fecha_inicio')
                ->nullable()
                ->after('fecha_maxima');
        });
    }

    public function down(): void
    {
        Schema::table('concepto_contables', function (Blueprint $table) {

            $table->dropColumn([
                'genera_cobro',
                'tipo_cobro',
                'valor_cobro',
                'dia_cobro',
                'fecha_maxima',
                'fecha_inicio',
            ]);

        });
    }
};
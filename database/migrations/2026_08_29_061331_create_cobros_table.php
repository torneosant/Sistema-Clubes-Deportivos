<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cobros', function (Blueprint $table) {

            $table->id();

            $table->foreignId('club_id')
                ->constrained('clubs')
                ->cascadeOnDelete();

            $table->foreignId('concepto_contable_id')
                ->constrained('concepto_contables')
                ->restrictOnDelete();

            $table->enum('tipo', [
                'Unico',
                'Mensual'
            ]);

            $table->decimal('valor', 12, 2);

            /*
            |----------------------------------------------------------
            | Para cobros mensuales
            | Ejemplo: día 5 de cada mes
            |----------------------------------------------------------
            */

            $table->unsignedTinyInteger('dia_cobro')
                ->nullable();

            /*
            |----------------------------------------------------------
            | Para cobros únicos
            |----------------------------------------------------------
            */

            $table->date('fecha_maxima')
                ->nullable();

            /*
            |----------------------------------------------------------
            | Fecha desde la cual aplica
            |----------------------------------------------------------
            */

            $table->date('fecha_inicio')
                ->nullable();

            $table->boolean('activo')
                ->default(true);

            $table->text('observaciones')
                ->nullable();

            $table->timestamps();

            $table->index([
                'club_id',
                'activo'
            ]);

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cobros');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cargos_jugadores', function (Blueprint $table) {

            $table->id();

            $table->foreignId('club_id')
                ->constrained('clubs')
                ->cascadeOnDelete();

            $table->foreignId('jugador_id')
                ->constrained('jugadores')
                ->cascadeOnDelete();

            $table->foreignId('concepto_contable_id')
                ->constrained('concepto_contables')
                ->restrictOnDelete();

            /*
            |----------------------------------------------------------
            | Periodo al que corresponde el cobro
            | Ejemplo: 2026-08
            |----------------------------------------------------------
            */

            $table->string('periodo', 20)->nullable();

            /*
            |----------------------------------------------------------
            | Fecha en que se genera / vence el cargo
            |----------------------------------------------------------
            */

            $table->date('fecha');

            /*
            |----------------------------------------------------------
            | Valor original del cargo
            |----------------------------------------------------------
            */

            $table->decimal('valor', 12, 2);

            /*
            |----------------------------------------------------------
            | Valor que ha sido pagado
            |----------------------------------------------------------
            */

            $table->decimal('valor_pagado', 12, 2)
                ->default(0);

            /*
            |----------------------------------------------------------
            | Estado
            |----------------------------------------------------------
            */

            $table->enum('estado', [
                'Pendiente',
                'Pagado',
                'Parcial',
                'Exonerado',
                'Anulado'
            ])->default('Pendiente');

            /*
            |----------------------------------------------------------
            | Motivo de exoneración
            |----------------------------------------------------------
            */

            $table->text('motivo_exoneracion')->nullable();

            $table->text('observaciones')->nullable();

            $table->timestamps();

            $table->index([
                'jugador_id',
                'periodo'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cargos_jugadores');
    }
};
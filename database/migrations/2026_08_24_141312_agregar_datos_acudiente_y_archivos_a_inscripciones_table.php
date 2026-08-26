<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecutar la migración.
     */
    public function up(): void
    {
        // Los campos necesarios para inscripciones
        // ya fueron incluidos en las migraciones anteriores.
        //
        // Esta migración se conserva únicamente como
        // historial del cambio realizado durante el desarrollo.
    }

    /**
     * Revertir la migración.
     */
    public function down(): void
    {
        // No hay nada que revertir porque esta migración
        // no modifica la estructura de la base de datos.
    }
};
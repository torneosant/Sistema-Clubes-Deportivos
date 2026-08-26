<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Ejecutar la migración.
     */
    public function up(): void
    {
        // Los campos documento_acudiente y email_acudiente
        // ya existen en la migración original de jugadores.
        //
        // Esta migración se conserva como historial del cambio
        // realizado durante el desarrollo.
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

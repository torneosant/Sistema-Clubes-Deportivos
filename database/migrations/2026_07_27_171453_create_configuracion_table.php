<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('configuracion', function (Blueprint $table) {

            $table->id();

            $table->string('nombre_club')->nullable();
            $table->string('logo')->nullable();
            $table->string('nit')->nullable();

            $table->string('direccion')->nullable();
            $table->string('ciudad')->nullable();
            $table->string('departamento')->nullable();
            $table->string('pais')->default('Colombia');

            $table->string('telefono')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('correo')->nullable();
            $table->string('pagina_web')->nullable();

            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('tiktok')->nullable();
            $table->string('youtube')->nullable();

            $table->string('temporada')->nullable();
            $table->string('anio')->nullable();

            $table->string('color_principal')->nullable();
            $table->string('color_secundario')->nullable();

            $table->string('zona_horaria')->default('America/Bogota');
            $table->string('idioma')->default('Español');
            $table->string('moneda')->default('COP');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('configuracion');
    }
};

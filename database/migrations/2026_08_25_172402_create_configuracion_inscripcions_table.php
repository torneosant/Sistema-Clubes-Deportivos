<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Configuración de inscripciones
        |--------------------------------------------------------------------------
        */

        Schema::create('configuracion_inscripciones', function (Blueprint $table) {

            $table->id();

            $table->foreignId('club_id')
                ->unique()
                ->constrained('clubs')
                ->cascadeOnDelete();

            $table->boolean('enviar_correo')
                ->default(true);

            $table->boolean('adjuntar_documentos')
                ->default(true);

            $table->string('asunto_correo')
                ->default('Inscripción aprobada');

            $table->text('mensaje_correo')
                ->nullable();

            $table->timestamps();
        });


        /*
        |--------------------------------------------------------------------------
        | Documentos que se enviarán con las inscripciones aprobadas
        |--------------------------------------------------------------------------
        */

        Schema::create(
            'configuracion_inscripcion_documento',
            function (Blueprint $table) {

                $table->id();


                /*
                |--------------------------------------------------------------------------
                | Configuración de inscripción
                |--------------------------------------------------------------------------
                */

                $table->foreignId(
                    'configuracion_inscripcion_id'
                );

                $table->foreign(
                    'configuracion_inscripcion_id',
                    'config_insc_doc_config_foreign'
                )
                    ->references('id')
                    ->on('configuracion_inscripciones')
                    ->cascadeOnDelete();


                /*
                |--------------------------------------------------------------------------
                | Documento del club
                |--------------------------------------------------------------------------
                */

                $table->foreignId(
                    'documento_club_id'
                );

                $table->foreign(
                    'documento_club_id',
                    'config_insc_doc_doc_foreign'
                )
                    ->references('id')
                    ->on('documentos_club')
                    ->cascadeOnDelete();


                /*
                |--------------------------------------------------------------------------
                | Evitar documentos duplicados
                |--------------------------------------------------------------------------
                */

                $table->unique(
                    [
                        'configuracion_inscripcion_id',
                        'documento_club_id'
                    ],
                    'config_insc_doc_unique'
                );


                $table->timestamps();
            }
        );
    }


    public function down(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Eliminar primero la tabla intermedia
        |--------------------------------------------------------------------------
        */

        Schema::dropIfExists(
            'configuracion_inscripcion_documento'
        );


        /*
        |--------------------------------------------------------------------------
        | Luego eliminar la configuración
        |--------------------------------------------------------------------------
        */

        Schema::dropIfExists(
            'configuracion_inscripciones'
        );
    }
};
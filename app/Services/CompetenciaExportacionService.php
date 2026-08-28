<?php

namespace App\Services;

use App\Models\Competencia;
use App\Exports\CompetenciaParticipantesExport;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use ZipArchive;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelFormat;

class CompetenciaExportacionService
{
    public function generarZip(
        Competencia $competencia,
        array $campos,
        array $documentosSeleccionados = []
    ): string {

        /*
        |--------------------------------------------------------------------------
        | Nombre base
        |--------------------------------------------------------------------------
        */

        $nombreBase =
            'planilla-' . Str::slug($competencia->nombre);


        /*
        |--------------------------------------------------------------------------
        | Carpeta temporal
        |--------------------------------------------------------------------------
        */

        $carpeta =
            storage_path(
                'app/temp/exportaciones/' .
                $nombreBase . '-' . Str::random(10)
            );

        File::ensureDirectoryExists($carpeta);


        /*
        |--------------------------------------------------------------------------
        | Generar Excel directamente en memoria
        |--------------------------------------------------------------------------
        */

        $excelNombre =
            $nombreBase . '.xlsx';

        $excelPath =
            $carpeta . DIRECTORY_SEPARATOR . $excelNombre;


        $excelContenido =
            Excel::raw(
                new CompetenciaParticipantesExport(
                    $competencia,
                    $campos
                ),
                ExcelFormat::XLSX
            );


        /*
        |--------------------------------------------------------------------------
        | Guardar Excel temporal
        |--------------------------------------------------------------------------
        */

        $resultado =
            file_put_contents(
                $excelPath,
                $excelContenido
            );


        if (
            $resultado === false ||
            !file_exists($excelPath)
        ) {

            throw new \RuntimeException(
                'No fue posible crear el Excel temporal.'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Crear ZIP
        |--------------------------------------------------------------------------
        */

        $zipPath =
            storage_path(
                'app/' .
                $nombreBase .
                '.zip'
            );


        if (file_exists($zipPath)) {

            @unlink($zipPath);

        }


        $zip =
            new ZipArchive();


        if (
            $zip->open(
                $zipPath,
                ZipArchive::CREATE |
                ZipArchive::OVERWRITE
            ) !== true
        ) {

            throw new \RuntimeException(
                'No fue posible crear el archivo ZIP.'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Agregar Excel
        |--------------------------------------------------------------------------
        */

        if (!file_exists($excelPath)) {

            $zip->close();

            throw new \RuntimeException(
                'El archivo Excel temporal no existe.'
            );

        }


        $zip->addFile(
            $excelPath,
            $excelNombre
        );


        /*
        |--------------------------------------------------------------------------
        | Cargar participantes y documentos
        |--------------------------------------------------------------------------
        */

        $competencia->load([
            'jugadores.documentos.tipoDocumento'
        ]);


        /*
        |--------------------------------------------------------------------------
        | Agregar documentos
        |--------------------------------------------------------------------------
        */

        foreach ($competencia->jugadores as $jugador) {

            if (empty($documentosSeleccionados)) {
                continue;
            }


            $nombreJugador =
                Str::slug(
                    trim(
                        $jugador->nombres .
                        ' ' .
                        $jugador->apellidos
                    ),
                    '_'
                );


            $rutaJugador =
                'documentos/' .
                $nombreJugador .
                '/';


            /*
            |--------------------------------------------------------------------------
            | Foto
            |--------------------------------------------------------------------------
            */

            if (
                in_array(
                    'foto',
                    $documentosSeleccionados,
                    true
                )
            ) {

                $this->agregarArchivo(
                    $zip,
                    $jugador->foto,
                    $rutaJugador . 'foto'
                );

            }


            /*
            |--------------------------------------------------------------------------
            | Documentos
            |--------------------------------------------------------------------------
            */

            foreach ($jugador->documentos as $documento) {

                $tipo =
                    $documento->tipoDocumento;


                if (!$tipo) {
                    continue;
                }


                $tipoId =
                    (int) $documento->tipo_documento_id;


                if (
                    !$this->debeIncluirDocumento(
                        $tipoId,
                        $documentosSeleccionados
                    )
                ) {

                    continue;
                }


                $extension =
                    pathinfo(
                        $documento->archivo,
                        PATHINFO_EXTENSION
                    );


                $nombreArchivo =
                    Str::slug(
                        $documento->titulo ??
                        $tipo->nombre
                    );


                if ($extension) {

                    $nombreArchivo .=
                        '.' . $extension;

                }


                $this->agregarArchivo(
                    $zip,
                    $documento->archivo,
                    $rutaJugador .
                    $nombreArchivo
                );

            }

        }


        /*
        |--------------------------------------------------------------------------
        | Cerrar ZIP
        |--------------------------------------------------------------------------
        */

        $zip->close();


        /*
        |--------------------------------------------------------------------------
        | Eliminar carpeta temporal
        |--------------------------------------------------------------------------
        */

        File::deleteDirectory(
            $carpeta
        );


        /*
        |--------------------------------------------------------------------------
        | Verificar ZIP
        |--------------------------------------------------------------------------
        */

        if (!file_exists($zipPath)) {

            throw new \RuntimeException(
                'El archivo ZIP no fue generado correctamente.'
            );

        }


        return $zipPath;
    }


    private function debeIncluirDocumento(
        int $tipoId,
        array $seleccionados
    ): bool {

        if (
            in_array(
                'todos',
                $seleccionados,
                true
            )
        ) {
            return true;
        }


        if (
            $tipoId === 1 &&
            in_array(
                'foto',
                $seleccionados,
                true
            )
        ) {
            return true;
        }


        if (
            $tipoId === 2 &&
            in_array(
                'documento_identidad',
                $seleccionados,
                true
            )
        ) {
            return true;
        }


        if (
            in_array(
                'otros',
                $seleccionados,
                true
            )
        ) {

            return !in_array(
                $tipoId,
                [1, 2],
                true
            );

        }


        return false;
    }


    private function agregarArchivo(
        ZipArchive $zip,
        ?string $archivo,
        string $nombreDentroZip
    ): void {

        if (!$archivo) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | storage/app/public
        |--------------------------------------------------------------------------
        */

        $ruta =
            storage_path(
                'app/public/' . $archivo
            );

        if (file_exists($ruta)) {

            $zip->addFile(
                $ruta,
                $nombreDentroZip
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | storage/app
        |--------------------------------------------------------------------------
        */

        $ruta =
            storage_path(
                'app/' . $archivo
            );

        if (file_exists($ruta)) {

            $zip->addFile(
                $ruta,
                $nombreDentroZip
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | public/
        |--------------------------------------------------------------------------
        */

        $ruta =
            public_path($archivo);

        if (file_exists($ruta)) {

            $zip->addFile(
                $ruta,
                $nombreDentroZip
            );

        }
    }
}
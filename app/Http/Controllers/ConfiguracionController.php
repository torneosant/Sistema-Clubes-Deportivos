<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use Illuminate\Http\Request;

class ConfiguracionController extends Controller
{
    /**
     * Obtener la configuración del club actual.
     *
     * Usamos el ID del club como ID del registro
     * de configuración para no modificar la estructura
     * existente de la base de datos.
     */
    private function config()
    {
        $clubId = auth()->user()->club_id;

        $configuracion = Configuracion::find($clubId);

        if (!$configuracion) {

            $configuracion = new Configuracion();

            $configuracion->id = $clubId;

            $configuracion->nombre_club = null;
            $configuracion->pais = 'Colombia';
            $configuracion->zona_horaria = 'America/Bogota';
            $configuracion->idioma = 'Español';
            $configuracion->moneda = 'COP';

            $configuracion->save();
        }

        return $configuracion;
    }


    // ===========================
    // GENERAL
    // ===========================

    public function general()
    {
        $configuracion = $this->config();

        return view(
            'configuracion.general',
            compact('configuracion')
        );
    }


    public function updateGeneral(Request $request)
    {
        $configuracion = $this->config();

        $datos = $request->except('logo');

        if ($request->hasFile('logo')) {

            $datos['logo'] = $request
                ->file('logo')
                ->store('configuracion', 'public');
        }

        $configuracion->update($datos);

        return back()
            ->with('success', 'Información actualizada.');
    }


    // ===========================
    // REDES
    // ===========================

    public function redes()
    {
        $configuracion = $this->config();

        return view(
            'configuracion.redes',
            compact('configuracion')
        );
    }


    public function updateRedes(Request $request)
    {
        $configuracion = $this->config();

        $configuracion->facebook = $request->facebook;
        $configuracion->instagram = $request->instagram;
        $configuracion->tiktok = $request->tiktok;
        $configuracion->youtube = $request->youtube;

        $configuracion->save();

        return back()
            ->with('success', 'Redes sociales actualizadas.');
    }


    // ===========================
    // DEPORTIVO
    // ===========================

    public function deportivo()
    {
        $configuracion = $this->config();

        return view(
            'configuracion.deportivo',
            compact('configuracion')
        );
    }


    public function updateDeportivo(Request $request)
    {
        $configuracion = $this->config();

        $configuracion->update(
            $request->all()
        );

        return back()
            ->with(
                'success',
                'Configuración deportiva actualizada.'
            );
    }


    // ===========================
    // SISTEMA
    // ===========================

    public function sistema()
    {
        $configuracion = $this->config();

        return view(
            'configuracion.sistema',
            compact('configuracion')
        );
    }


    public function updateSistema(Request $request)
    {
        $configuracion = $this->config();

        $configuracion->update(
            $request->all()
        );

        return back()
            ->with(
                'success',
                'Configuración del sistema actualizada.'
            );
    }

    // ===========================
// INSCRIPCIONES
// ===========================

public function inscripciones()
{
    $clubId = auth()->user()->club_id;

    $configuracion = \App\Models\ConfiguracionInscripcion::firstOrCreate(
        ['club_id' => $clubId],
        [
            'enviar_correo' => true,
            'adjuntar_documentos' => true,
            'asunto_correo' => 'Inscripción aprobada',
            'mensaje_correo' =>
                'Tu inscripción ha sido aprobada. Bienvenido al club.',
        ]
    );

    $documentos = \App\Models\DocumentoClub::where('club_id', $clubId)
        ->where('activo', true)
        ->orderBy('titulo')
        ->get();

    $documentosSeleccionados = $configuracion
        ->documentos()
        ->pluck('documentos_club.id')
        ->toArray();

    return view(
        'configuracion.inscripciones',
        compact(
            'configuracion',
            'documentos',
            'documentosSeleccionados'
        )
    );
}


public function updateInscripciones(Request $request)
{
    $clubId = auth()->user()->club_id;

    $configuracion = \App\Models\ConfiguracionInscripcion::firstOrCreate(
        ['club_id' => $clubId],
        [
            'enviar_correo' => true,
            'adjuntar_documentos' => true,
            'asunto_correo' => 'Inscripción aprobada',
        ]
    );

    $datos = $request->validate([

        'enviar_correo' => 'nullable|boolean',

        'adjuntar_documentos' => 'nullable|boolean',

        'asunto_correo' => 'required|string|max:255',

        'mensaje_correo' => 'nullable|string|max:5000',

        'documentos' => 'nullable|array',

        'documentos.*' =>
            'integer|exists:documentos_club,id',
    ]);


    $configuracion->update([

        'enviar_correo' =>
            $request->boolean('enviar_correo'),

        'adjuntar_documentos' =>
            $request->boolean('adjuntar_documentos'),

        'asunto_correo' =>
            $datos['asunto_correo'],

        'mensaje_correo' =>
            $datos['mensaje_correo'] ?? null,

    ]);


    $documentosIds = collect(
        $datos['documentos'] ?? []
    );

    $documentosValidos = \App\Models\DocumentoClub::where(
        'club_id',
        $clubId
    )
        ->whereIn('id', $documentosIds)
        ->pluck('id')
        ->toArray();


    $configuracion
        ->documentos()
        ->sync($documentosValidos);


    return back()->with(
        'success',
        'Configuración de inscripciones actualizada correctamente.'
    );
}

// ===========================
// CALENDARIO
// ===========================

public function calendario()
{
    $configuracion = $this->config();

    return view(
        'configuracion.calendario',
        compact('configuracion')
    );
}


public function updateCalendario(Request $request)
{
    $configuracion = $this->config();

    $configuracion->update([

        'calendario_partidos' =>
            $request->boolean('calendario_partidos'),

        'calendario_entrenamientos' =>
            $request->boolean('calendario_entrenamientos'),

        'calendario_cumpleanos' =>
            $request->boolean('calendario_cumpleanos'),

        'calendario_eventos' =>
            $request->boolean('calendario_eventos'),

    ]);

    return back()->with(
        'success',
        'Configuración del calendario actualizada correctamente.'
    );
}
}
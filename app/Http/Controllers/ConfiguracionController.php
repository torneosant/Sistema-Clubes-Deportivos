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
}
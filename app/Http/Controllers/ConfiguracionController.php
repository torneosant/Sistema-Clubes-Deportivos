<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use Illuminate\Http\Request;

class ConfiguracionController extends Controller
{
    private function config()
    {
        return Configuracion::firstOrCreate([]);
    }

    public function general()
{
    $configuracion = Configuracion::first();
    return view('configuracion.general', compact('configuracion'));
}

public function redes()
{
    $configuracion = Configuracion::first();
    return view('configuracion.redes', compact('configuracion'));
}

public function deportivo()
{
    $configuracion = Configuracion::first();
    return view('configuracion.deportivo', compact('configuracion'));
}

public function sistema()
{
    $configuracion = Configuracion::first();
    return view('configuracion.sistema', compact('configuracion'));
}



    public function updateGeneral(Request $request)
    {
        $configuracion = $this->config();

        $datos = $request->all();

        if ($request->hasFile('logo')) {

            $datos['logo'] = $request
                ->file('logo')
                ->store('configuracion', 'public');

        }

        $configuracion->update($datos);

        return back()->with('success','Información actualizada.');
    }

  public function updateRedes(Request $request)
{
    $configuracion = Configuracion::first();

    $configuracion->facebook = $request->facebook;
    $configuracion->instagram = $request->instagram;
    $configuracion->tiktok = $request->tiktok;
    $configuracion->youtube = $request->youtube;

    $configuracion->save();

    dd($configuracion->fresh()->toArray());
}

    public function updateDeportivo(Request $request)
    {
        $this->config()->update($request->all());

        return back()->with('success','Configuración deportiva actualizada.');
    }

    public function updateSistema(Request $request)
    {
        $this->config()->update($request->all());

        return back()->with('success','Configuración del sistema actualizada.');
    }
}
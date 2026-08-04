<?php

namespace App\Http\Controllers;

use App\Models\Modulo;
use Illuminate\Http\Request;
use App\Models\Permiso;
use Illuminate\Support\Str;

class ModuloController extends Controller
{
    public function index()
    {
        $modulos = Modulo::orderBy('nombre')->get();

        return view('modulos.index', compact('modulos'));
    }

    public function create()
    {
        return view('modulos.form', [
            'modulo' => new Modulo(),
            'modo' => 'crear'
        ]);
    }

    public function store(Request $request)
{
    $request->validate([
        'nombre' => 'required|max:100',
    ]);

    $modulo = Modulo::create([
        'nombre' => $request->nombre,
        'slug'   => Str::slug($request->nombre),
        'activo' => true
    ]);

    $acciones = ['ver', 'crear', 'editar', 'eliminar'];

    foreach ($acciones as $accion) {

        Permiso::create([
            'nombre' => ucfirst($accion).' '.$modulo->nombre,
            'slug'   => $modulo->slug.'.'.$accion,
            'activo' => true
        ]);

    }

    return redirect()
        ->route('modulos.index')
        ->with('success','Módulo creado correctamente.');
}

    public function edit(Modulo $modulo)
    {
        return view('modulos.form', [
            'modulo' => $modulo,
            'modo'   => 'editar'
        ]);
    }

    public function update(Request $request, Modulo $modulo)
    {
        $request->validate([
            'nombre' => 'required|max:100',
            'slug'   => 'required|unique:modulos,slug,'.$modulo->id
        ]);

        $modulo->update([
            'nombre' => $request->nombre,
            'slug'   => strtolower($request->slug),
            'activo' => $request->has('activo')
        ]);

        return redirect()
            ->route('modulos.index')
            ->with('success','Módulo actualizado.');
    }

    public function destroy(Modulo $modulo)
    {
        $modulo->delete();

        return redirect()
            ->route('modulos.index')
            ->with('success','Módulo eliminado.');
    }
}

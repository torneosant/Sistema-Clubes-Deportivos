<?php

namespace App\Http\Controllers;

use App\Models\TipoArticulo;
use Illuminate\Http\Request;

class TipoArticuloController extends Controller
{
    public function index()
    {
        $tipos = TipoArticulo::orderBy('nombre')->get();

        return view('inventario.tipos.index', compact('tipos'));
    }

 public function create()
{
    return view('inventario.tipos.form', [
        'tipo' => new TipoArticulo(),
        'modo' => 'crear',
    ]);
}

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:100',
        ]);

        TipoArticulo::create([
            'nombre' => $request->nombre,
            'activo' => $request->has('activo'),
        ]);

        return redirect()->route('tipos-articulo.index');
    }

   public function edit(TipoArticulo $tipos_articulo)
{
    return view('inventario.tipos.form', [
        'tipo' => $tipos_articulo,
        'modo' => 'editar',
    ]);
}

    public function update(Request $request, TipoArticulo $tipos_articulo)
    {
        $request->validate([
            'nombre' => 'required|max:100',
        ]);

        $tipos_articulo->update([
            'nombre' => $request->nombre,
            'activo' => $request->has('activo'),
        ]);

        return redirect()->route('tipos-articulo.index');
    }

    public function destroy(TipoArticulo $tipos_articulo)
    {
        $tipos_articulo->delete();

        return redirect()->route('tipos-articulo.index');
    }
}
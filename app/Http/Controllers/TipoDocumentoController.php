<?php

namespace App\Http\Controllers;

use App\Models\TipoDocumento;
use Illuminate\Http\Request;

class TipoDocumentoController extends Controller
{
    public function index()
    {
        $tipos = TipoDocumento::orderBy('nombre')->get();
        return view('tipos-documento.index', compact('tipos'));
    }

    public function create()
    {
        return view('tipos-documento.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:100',
            'descripcion' => 'nullable|max:255',
        ]);

        TipoDocumento::create($request->all());

        return redirect()->route('tipos-documento.index')
            ->with('success', 'Tipo de documento creado correctamente.');
    }

    public function edit(TipoDocumento $tipos_documento)
    {
        return view('tipos-documento.edit', [
            'tipo' => $tipos_documento
        ]);
    }

    public function update(Request $request, TipoDocumento $tipos_documento)
    {
        $request->validate([
            'nombre' => 'required|max:100',
            'descripcion' => 'nullable|max:255',
        ]);

        $tipos_documento->update($request->all());

        return redirect()->route('tipos-documento.index')
            ->with('success', 'Tipo actualizado correctamente.');
    }

    public function destroy(TipoDocumento $tipos_documento)
    {
        $tipos_documento->delete();

        return redirect()->route('tipos-documento.index')
            ->with('success', 'Tipo eliminado.');
    }
}
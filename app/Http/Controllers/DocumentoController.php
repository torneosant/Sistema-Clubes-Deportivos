<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\TipoDocumento;
use Illuminate\Http\Request;

class DocumentoController extends Controller
{
    public function index()
    {
        $documentos = Documento::with('tipoDocumento')
            ->orderByDesc('id')
            ->get();

        return view('documentos.index', compact('documentos'));
    }

    public function create()
    {
        $tipos = TipoDocumento::orderBy('nombre')->get();

        return view('documentos.create', compact('tipos'));
    }

    public function store(Request $request)
{
    $request->validate([
        'tipo_documento_id' => 'required',
        'titulo' => 'required|max:255',
        'descripcion' => 'nullable',
        'fecha' => 'nullable|date',
        'archivo' => 'required|mimes:pdf|max:5120',
    ]);

    $ruta = $request->file('archivo')->store('documentos', 'public');

    Documento::create([
        'tipo_documento_id' => $request->tipo_documento_id,
        'titulo' => $request->titulo,
        'descripcion' => $request->descripcion,
        'fecha' => $request->fecha,
        'archivo' => $ruta,
    ]);

    return redirect()
        ->route('documentos.index')
        ->with('success', 'Documento registrado correctamente.');
}

    public function show(Documento $documento)
    {
        //
    }

    public function edit(Documento $documento)
    {
        $tipos = TipoDocumento::orderBy('nombre')->get();

        return view('documentos.edit', compact('documento','tipos'));
    }

    public function update(Request $request, Documento $documento)
    {
        //
    }

    public function destroy(Documento $documento)
    {
        //
    }
}
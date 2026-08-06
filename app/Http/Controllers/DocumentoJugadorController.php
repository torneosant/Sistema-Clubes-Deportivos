<?php

namespace App\Http\Controllers;

use App\Models\Jugador;
use App\Models\Documento;
use App\Models\TipoDocumento;
use Illuminate\Http\Request;

class DocumentoJugadorController extends Controller
{
    public function index(Jugador $jugador)
    {
        $documentos = $jugador->documentos()
            ->with('tipoDocumento')
            ->latest()
            ->get();

        $tipos = TipoDocumento::where('activo', true)
            ->orderBy('nombre')
            ->get();

        return view(
            'jugadores.documentos',
            compact('jugador','documentos','tipos')
        );
    }

    public function store(Request $request, Jugador $jugador)
    {
        $request->validate([
            'tipo_documento_id' => 'required|exists:tipo_documentos,id',
            'titulo'            => 'required|max:255',
            'descripcion'       => 'nullable',
            'archivo'           => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $ruta = $request->file('archivo')
            ->store('documentos','public');

        Documento::create([
            'jugador_id'        => $jugador->id,
            'tipo_documento_id' => $request->tipo_documento_id,
            'titulo'            => $request->titulo,
            'descripcion'       => $request->descripcion,
            'archivo'           => $ruta,
            'fecha'             => now(),
            'activo'            => true,
        ]);

        return back()->with(
            'success',
            'Documento cargado correctamente.'
        );
    }

    public function destroy(Documento $documento)
    {
        $documento->delete();

        return back()->with(
            'success',
            'Documento eliminado.'
        );
    }
}
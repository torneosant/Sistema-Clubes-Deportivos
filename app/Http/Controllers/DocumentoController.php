<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\TipoDocumento;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DocumentoController extends Controller
{
    public function index()
    {
        
        $clubId = auth()->user()->club_id;

        $documentos = Documento::where('club_id', $clubId)
            ->with('tipoDocumento')
            ->orderByDesc('id')
            ->get();

        return view('documentos.index', compact('documentos'));
    }


   public function create()
{
    $clubId = auth()->user()->club_id;

    $tipos = TipoDocumento::where('club_id', $clubId)
        ->orderBy('nombre')
        ->get();

    return view(
        'documentos.create',
        compact('tipos')
    );
}


    public function store(Request $request)
    {
        $datos = $request->validate([
    'tipo_documento_id' => [
        'required',
        Rule::exists('tipo_documentos', 'id')
            ->where('club_id', auth()->user()->club_id),
    ],
    'titulo' => 'required|max:255',
    'descripcion' => 'nullable',
    'fecha' => 'nullable|date',
    'archivo' => 'required|mimes:pdf|max:5120',
]);

        $ruta = $request->file('archivo')
            ->store('documentos', 'public');

        Documento::create([
            'club_id' => auth()->user()->club_id,
            'tipo_documento_id' => $request->tipo_documento_id,
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'fecha' => $request->fecha,
            'archivo' => $ruta,
            'activo' => true,
        ]);

        return redirect()
            ->route('documentos.index')
            ->with('success', 'Documento registrado correctamente.');
    }


    public function show(Documento $documento)
    {
        abort_unless(
            $documento->club_id == auth()->user()->club_id,
            403,
            'No tiene permiso para acceder a este documento.'
        );

        return view('documentos.show', compact('documento'));
    }


   public function edit(Documento $documento)
{
    abort_unless(
        $documento->club_id == auth()->user()->club_id,
        403,
        'No tiene permiso para editar este documento.'
    );

    $tipos = TipoDocumento::where(
        'club_id',
        auth()->user()->club_id
    )
    ->orderBy('nombre')
    ->get();

    return view(
        'documentos.edit',
        compact('documento', 'tipos')
    );
}


    public function update(Request $request, Documento $documento)
    {
        abort_unless(
            $documento->club_id == auth()->user()->club_id,
            403,
            'No tiene permiso para editar este documento.'
        );

        $datos = $request->validate([
            'tipo_documento_id' => 'required|exists:tipo_documentos,id',
            'titulo' => 'required|max:255',
            'descripcion' => 'nullable',
            'fecha' => 'nullable|date',
            'activo' => 'nullable|boolean',
        ]);

        $documento->update($datos);

        return redirect()
            ->route('documentos.index')
            ->with('success', 'Documento actualizado correctamente.');
    }


    public function destroy(Documento $documento)
    {
        abort_unless(
            $documento->club_id == auth()->user()->club_id,
            403,
            'No tiene permiso para eliminar este documento.'
        );

        $documento->delete();

        return redirect()
            ->route('documentos.index')
            ->with('success', 'Documento eliminado correctamente.');
    }
}
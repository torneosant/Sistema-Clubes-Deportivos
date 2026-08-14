<?php

namespace App\Http\Controllers;

use App\Models\TipoDocumento;
use Illuminate\Http\Request;

class TipoDocumentoController extends Controller
{
    public function index()
    {
        $clubId = auth()->user()->club_id;

        $tipos = TipoDocumento::where('club_id', $clubId)
            ->orderBy('nombre')
            ->get();

        return view(
            'tipos-documento.index',
            compact('tipos')
        );
    }


    public function create()
    {
        return view('tipos-documento.create');
    }


    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'activo' => 'nullable|boolean',
        ]);

        $datos['club_id'] = auth()->user()->club_id;
        $datos['activo'] = $request->has('activo');

        TipoDocumento::create($datos);

        return redirect()
            ->route('tipos-documento.index')
            ->with('success', 'Tipo de documento creado correctamente.');
    }


    public function edit(TipoDocumento $tipoDocumento)
    {
        abort_unless(
            $tipoDocumento->club_id == auth()->user()->club_id,
            403,
            'No tiene permiso para editar este tipo de documento.'
        );

        return view(
            'tipos-documento.edit',
            compact('tipoDocumento')
        );
    }


    public function update(
        Request $request,
        TipoDocumento $tipoDocumento
    ) {
        abort_unless(
            $tipoDocumento->club_id == auth()->user()->club_id,
            403,
            'No tiene permiso para editar este tipo de documento.'
        );

        $datos = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'activo' => 'nullable|boolean',
        ]);

        $datos['activo'] = $request->has('activo');

        $tipoDocumento->update($datos);

        return redirect()
            ->route('tipos-documento.index')
            ->with('success', 'Tipo de documento actualizado correctamente.');
    }


    public function destroy(TipoDocumento $tipoDocumento)
    {
        abort_unless(
            $tipoDocumento->club_id == auth()->user()->club_id,
            403,
            'No tiene permiso para eliminar este tipo de documento.'
        );

        $tipoDocumento->delete();

        return redirect()
            ->route('tipos-documento.index')
            ->with('success', 'Tipo de documento eliminado correctamente.');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\TipoDocumentoClub;
use Illuminate\Http\Request;

class TipoDocumentoController extends Controller
{
    /**
     * Listado de tipos de documentos del club.
     */
    public function index()
    {
        $clubId = auth()->user()->club_id;

        $tipos = TipoDocumentoClub::where('club_id', $clubId)
            ->orderBy('nombre')
            ->get();

        return view(
            'tipos-documento.index',
            compact('tipos')
        );
    }


    /**
     * Formulario para crear tipo.
     */
    public function create()
    {
        return view('tipos-documento.create');
    }


    /**
     * Crear tipo de documento del club.
     */
    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre' =>
                'required|string|max:255',

            'descripcion' =>
                'nullable|string|max:1000',
        ]);


        TipoDocumentoClub::create([

            'club_id' =>
                auth()->user()->club_id,

            'nombre' =>
                $datos['nombre'],

            'descripcion' =>
                $datos['descripcion'] ?? null,

            'activo' =>
                true,

        ]);


        return redirect()
            ->route('tipos-documento.index')
            ->with(
                'success',
                'Tipo de documento creado correctamente.'
            );
    }


    /**
     * Editar tipo.
     */
    public function edit(TipoDocumentoClub $tipoDocumento)
    {
        abort_unless(
            (int) $tipoDocumento->club_id ===
            (int) auth()->user()->club_id,
            403,
            'No tiene permiso para editar este tipo de documento.'
        );

        return view(
            'tipos-documento.edit',
            compact('tipoDocumento')
        );
    }


    /**
     * Actualizar tipo.
     */
    public function update(
        Request $request,
        TipoDocumentoClub $tipoDocumento
    ) {

        abort_unless(
            (int) $tipoDocumento->club_id ===
            (int) auth()->user()->club_id,
            403,
            'No tiene permiso para editar este tipo de documento.'
        );


        $datos = $request->validate([
            'nombre' =>
                'required|string|max:255',

            'descripcion' =>
                'nullable|string|max:1000',

            'activo' =>
                'nullable|boolean',
        ]);


        $tipoDocumento->update($datos);


        return redirect()
            ->route('tipos-documento.index')
            ->with(
                'success',
                'Tipo de documento actualizado correctamente.'
            );
    }


    /**
     * Eliminar tipo.
     */
    public function destroy(
        TipoDocumentoClub $tipoDocumento
    ) {

        abort_unless(
            (int) $tipoDocumento->club_id ===
            (int) auth()->user()->club_id,
            403,
            'No tiene permiso para eliminar este tipo de documento.'
        );


        $tipoDocumento->delete();


        return redirect()
            ->route('tipos-documento.index')
            ->with(
                'success',
                'Tipo de documento eliminado correctamente.'
            );
    }
}
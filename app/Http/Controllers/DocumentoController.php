<?php

namespace App\Http\Controllers;

use App\Models\DocumentoClub;
use App\Models\TipoDocumentoClub;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DocumentoController extends Controller
{
    /**
     * Centro de Documentación del Club
     */
    public function index()
    {
        $clubId = auth()->user()->club_id;

        $configuracion = \App\Models\Configuracion::find($clubId);

        $anio = session(
            'anio_trabajo',
            $configuracion?->anio ?? date('Y')
        );

        $documentos = DocumentoClub::where('club_id', $clubId)
            ->whereYear('fecha', $anio)
            ->with('tipoDocumentoClub')
            ->orderByDesc('fecha')
            ->orderByDesc('id')
            ->get();

        return view(
            'documentos.index',
            compact('documentos')
        );
    }


    /**
     * Formulario para crear documento del club.
     */
    public function create()
    {
        $clubId = auth()->user()->club_id;

        $configuracion = \App\Models\Configuracion::find($clubId);

        $anio = session(
            'anio_trabajo',
            $configuracion?->anio ?? date('Y')
        );

        $tipos = TipoDocumentoClub::where('club_id', $clubId)
            ->where('activo', true)
            ->orderBy('nombre')
            ->get();

        return view(
            'documentos.create',
            compact('tipos', 'anio')
        );
    }


    /**
     * Guardar documento general del club.
     */
    public function store(Request $request)
    {
        $clubId = auth()->user()->club_id;

        $datos = $request->validate([

            'tipo_documento_club_id' => [
                'required',

                Rule::exists(
                    'tipos_documentos_club',
                    'id'
                )->where(function ($query) use ($clubId) {

                    $query->where(
                        'club_id',
                        $clubId
                    );

                }),
            ],

            'titulo' =>
                'required|string|max:255',

            'descripcion' =>
                'nullable|string',

            'fecha' =>
                'nullable|date',

            'archivo' =>
                'required|file|mimes:pdf|max:5120',

        ]);


        /*
        |--------------------------------------------------------------------------
        | Guardar archivo
        |--------------------------------------------------------------------------
        */

        $ruta = $request
            ->file('archivo')
            ->store(
                'documentos_club',
                'public'
            );


        /*
        |--------------------------------------------------------------------------
        | Crear documento
        |--------------------------------------------------------------------------
        */

        DocumentoClub::create([

            'club_id' =>
                $clubId,

            'tipo_documento_club_id' =>
                $datos['tipo_documento_club_id'],

            'titulo' =>
                $datos['titulo'],

            'descripcion' =>
                $datos['descripcion'] ?? null,

            'fecha' =>
                $datos['fecha'] ?? now()->toDateString(),

            'archivo' =>
                $ruta,

            'activo' =>
                true,

        ]);


        return redirect()
            ->route('documentos.index')
            ->with(
                'success',
                'Documento registrado correctamente.'
            );
    }


    /**
     * Mostrar documento.
     */
    public function show(DocumentoClub $documento)
    {
        $clubId = auth()->user()->club_id;

        abort_unless(
            (int) $documento->club_id === (int) $clubId,
            403,
            'No tiene permiso para acceder a este documento.'
        );

        return view(
            'documentos.show',
            compact('documento')
        );
    }


    /**
     * Editar documento.
     */
    public function edit(DocumentoClub $documento)
    {
        $clubId = auth()->user()->club_id;

        abort_unless(
            (int) $documento->club_id === (int) $clubId,
            403,
            'No tiene permiso para editar este documento.'
        );


        $tipos = TipoDocumentoClub::where(
            'club_id',
            $clubId
        )
            ->where('activo', true)
            ->orderBy('nombre')
            ->get();


        return view(
            'documentos.edit',
            compact(
                'documento',
                'tipos'
            )
        );
    }


    /**
     * Actualizar documento.
     */
    public function update(
        Request $request,
        DocumentoClub $documento
    ) {

        $clubId = auth()->user()->club_id;

        abort_unless(
            (int) $documento->club_id === (int) $clubId,
            403,
            'No tiene permiso para editar este documento.'
        );


        $datos = $request->validate([

            'tipo_documento_club_id' => [
                'required',

                Rule::exists(
                    'tipos_documentos_club',
                    'id'
                )->where(function ($query) use ($clubId) {

                    $query->where(
                        'club_id',
                        $clubId
                    );

                }),
            ],

            'titulo' =>
                'required|string|max:255',

            'descripcion' =>
                'nullable|string',

            'fecha' =>
                'nullable|date',

            'activo' =>
                'nullable|boolean',

        ]);


        $documento->update($datos);


        return redirect()
            ->route('documentos.index')
            ->with(
                'success',
                'Documento actualizado correctamente.'
            );
    }


    /**
     * Eliminar documento.
     */
    public function destroy(DocumentoClub $documento)
    {
        $clubId = auth()->user()->club_id;

        abort_unless(
            (int) $documento->club_id === (int) $clubId,
            403,
            'No tiene permiso para eliminar este documento.'
        );


        $documento->delete();


        return redirect()
            ->route('documentos.index')
            ->with(
                'success',
                'Documento eliminado correctamente.'
            );
    }
}
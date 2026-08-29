<?php

namespace App\Http\Controllers;

use App\Models\ConceptoContable;
use Illuminate\Http\Request;

class ConceptoContableController extends Controller
{
    /**
     * Mostrar listado de conceptos contables.
     */
    public function index()
    {
        $clubId = auth()->user()->club_id;

        $conceptos = ConceptoContable::where('club_id', $clubId)
            ->orderBy('tipo')
            ->orderBy('nombre')
            ->get();

        return view(
            'conceptos-contables.index',
            compact('conceptos')
        );
    }


    /**
     * Mostrar formulario para crear concepto.
     */
    public function create()
    {
        return view('conceptos-contables.create');
    }


    /**
     * Guardar nuevo concepto.
     */
    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre' => 'required|string|max:255',

            'tipo' => [
                'required',
                'in:Ingreso,Egreso',
            ],

            'valor_predeterminado' =>
                'nullable|numeric|min:0',

            'descripcion' =>
                'nullable|string',
        ]);


        $datos['club_id'] =
            auth()->user()->club_id;

        $datos['activo'] =
            $request->has('activo');


        ConceptoContable::create($datos);


        return redirect()
            ->route('conceptos-contables.index')
            ->with(
                'success',
                'Concepto creado correctamente.'
            );
    }


    /**
     * Mostrar concepto.
     */
    public function show($conceptos_contable)
    {
        $clubId = auth()->user()->club_id;

        $conceptoContable = ConceptoContable::where(
            'club_id',
            $clubId
        )
        ->where(
            'id',
            $conceptos_contable
        )
        ->firstOrFail();


        return view(
            'conceptos-contables.show',
            compact('conceptoContable')
        );
    }


    /**
     * Mostrar formulario para editar.
     */
    public function edit($conceptos_contable)
    {
        $clubId = auth()->user()->club_id;

        $conceptoContable = ConceptoContable::where(
            'club_id',
            $clubId
        )
        ->where(
            'id',
            $conceptos_contable
        )
        ->firstOrFail();


        return view(
            'conceptos-contables.edit',
            compact('conceptoContable')
        );
    }


    /**
     * Actualizar concepto.
     */
    public function update(
        Request $request,
        $conceptos_contable
    ) {
        $clubId = auth()->user()->club_id;

        $conceptoContable = ConceptoContable::where(
            'club_id',
            $clubId
        )
        ->where(
            'id',
            $conceptos_contable
        )
        ->firstOrFail();


        $datos = $request->validate([
            'nombre' =>
                'required|string|max:255',

            'tipo' => [
                'required',
                'in:Ingreso,Egreso',
            ],

            'valor_predeterminado' =>
                'nullable|numeric|min:0',

            'descripcion' =>
                'nullable|string',
        ]);


        $datos['activo'] =
            $request->has('activo');


        $conceptoContable->update($datos);


        return redirect()
            ->route('conceptos-contables.index')
            ->with(
                'success',
                'Concepto actualizado correctamente.'
            );
    }


    /**
     * Eliminar o desactivar concepto.
     */
    public function destroy($conceptos_contable)
    {
        $clubId = auth()->user()->club_id;

        $conceptoContable = ConceptoContable::where(
            'club_id',
            $clubId
        )
        ->where(
            'id',
            $conceptos_contable
        )
        ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Verificar movimientos asociados
        |--------------------------------------------------------------------------
        */

        $tieneMovimientos =
            $conceptoContable
                ->movimientos()
                ->exists();


        /*
        |--------------------------------------------------------------------------
        | Verificar cargos de jugadores
        |--------------------------------------------------------------------------
        */

        $tieneCargos =
            $conceptoContable
                ->cargos()
                ->exists();


        /*
        |--------------------------------------------------------------------------
        | Si tiene historial, no eliminar
        |--------------------------------------------------------------------------
        */

        if (
            $tieneMovimientos ||
            $tieneCargos
        ) {

            $conceptoContable->update([
                'activo' => false,
            ]);


            return redirect()
                ->route('conceptos-contables.index')
                ->with(
                    'success',
                    'El concepto tiene registros asociados y fue desactivado para conservar el historial.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Si nunca se ha utilizado, eliminar
        |--------------------------------------------------------------------------
        */

        $conceptoContable->delete();


        return redirect()
            ->route('conceptos-contables.index')
            ->with(
                'success',
                'Concepto eliminado correctamente.'
            );
    }
}

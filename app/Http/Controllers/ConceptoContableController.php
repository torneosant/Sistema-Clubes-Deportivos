<?php

namespace App\Http\Controllers;

use App\Models\ConceptoContable;
use Illuminate\Http\Request;


class ConceptoContableController extends Controller
{   
    /**
     * Display a listing of the resource.
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
     * Show the form for creating a new resource.
     */
    public function create()
{
    return view('conceptos-contables.create');
}

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    $datos = $request->validate([
        'nombre' => 'required|string|max:255',
        'tipo' => 'required|string|max:50',
        'descripcion' => 'nullable|string',
    ]);

    $datos['club_id'] = auth()->user()->club_id;
    $datos['activo'] = $request->has('activo');

    ConceptoContable::create($datos);

    return redirect()
        ->route('conceptos-contables.index')
        ->with('success', 'Concepto creado correctamente.');
}

    /**
     * Display the specified resource.
     */
    public function show(ConceptoContable $conceptoContable)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ConceptoContable $conceptoContable)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ConceptoContable $conceptoContable)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ConceptoContable $conceptoContable)
    {
        //
    }
}

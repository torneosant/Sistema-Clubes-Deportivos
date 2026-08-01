<?php

namespace App\Http\Controllers;

use App\Models\Contabilidad;
use App\Models\ConceptoContable;
use App\Models\Jugador;
use Illuminate\Http\Request;

class ContabilidadController extends Controller
{
public function index(Request $request)
{
    $query = Contabilidad::with([
        'concepto',
        'jugador'
    ]);

    if ($request->filled('tipo')) {
        $query->where('tipo', $request->tipo);
    }

    if ($request->filled('concepto')) {
        $query->where('concepto_contable_id', $request->concepto);
    }

    if ($request->filled('desde')) {
        $query->whereDate('fecha','>=',$request->desde);
    }

    if ($request->filled('hasta')) {
        $query->whereDate('fecha','<=',$request->hasta);
    }

    $movimientos = $query
    ->orderBy('fecha', 'desc')
    ->orderBy('id', 'desc')
    ->get();

    $ingresos = Contabilidad::where('tipo','Ingreso')->sum('valor');

    $gastos = Contabilidad::where('tipo','Egreso')->sum('valor');

    $saldo = $ingresos - $gastos;

    $conceptos = ConceptoContable::orderBy('nombre')->get();

    return view(
        'contabilidad.index',
        compact(
            'movimientos',
            'ingresos',
            'gastos',
            'saldo',
            'conceptos'
        )
    );
}

public function create()
{
    $conceptos = ConceptoContable::orderBy('nombre')->get();

    $jugadores = Jugador::orderBy('apellidos')
        ->orderBy('nombres')
        ->get();

    return view(
        'contabilidad.create',
        compact(
            'conceptos',
            'jugadores'
        )
    );
}

public function store(Request $request)
{
    $datos = $request->validate([

    'fecha' => 'required|date',

    'tipo' => 'required',

    'concepto_contable_id' => 'required|exists:concepto_contables,id',

    'jugador_id' => 'nullable|exists:jugadores,id',

    'tercero' => 'nullable|string|max:255',

    'valor' => 'required|numeric|min:1',

    'metodo_pago' => 'nullable|string|max:100',

    'observaciones' => 'nullable|string',

]);

    Contabilidad::create($datos);

    return redirect()
        ->route('contabilidad.index')
        ->with('success','Movimiento registrado correctamente.');
}



public function edit(Contabilidad $contabilidad)
{
    $conceptos = ConceptoContable::orderBy('nombre')->get();

    $jugadores = Jugador::orderBy('apellidos')
        ->orderBy('nombres')
        ->get();

    return view(
        'contabilidad.edit',
        compact(
            'contabilidad',
            'conceptos',
            'jugadores'
        )
    );
}

public function update(Request $request, Contabilidad $contabilidad)
{
    $datos = $request->validate([

        'fecha' => 'required|date',

        'tipo' => 'required',

        'concepto_contable_id' => 'required|exists:concepto_contables,id',

        'jugador_id' => 'nullable|exists:jugadores,id',

        'tercero' => 'nullable|string|max:255',

        'valor' => 'required|numeric|min:1',

        'metodo_pago' => 'nullable|string|max:100',

        'observaciones' => 'nullable|string',

    ]);

    $contabilidad->update($datos);

    return redirect()
        ->route('contabilidad.index')
        ->with('success','Movimiento actualizado correctamente.');
}


public function destroy(Contabilidad $contabilidad)
{
    $contabilidad->delete();

    return redirect()
        ->route('contabilidad.index')
        ->with('success','Movimiento eliminado.');
}   
}
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
    $clubId = auth()->user()->club_id;

    $configuracion = \App\Models\Configuracion::find($clubId);

    $anio = session(
        'anio_trabajo',
        $configuracion?->anio ?? date('Y')
    );

    $query = Contabilidad::with([
        'concepto',
        'jugador'
    ])
    ->where('club_id', $clubId)
    ->whereYear('fecha', $anio);

    if ($request->filled('tipo')) {
        $query->where('tipo', $request->tipo);
    }

    if ($request->filled('concepto')) {
        $query->where('concepto_contable_id', $request->concepto);
    }

    if ($request->filled('desde')) {
        $query->whereDate('fecha', '>=', $request->desde);
    }

    if ($request->filled('hasta')) {
        $query->whereDate('fecha', '<=', $request->hasta);
    }

    $movimientos = $query
        ->orderBy('fecha', 'desc')
        ->orderBy('id', 'desc')
        ->get();

    $ingresos = Contabilidad::where('club_id', $clubId)
        ->whereYear('fecha', $anio)
        ->where('tipo', 'Ingreso')
        ->sum('valor');

    $gastos = Contabilidad::where('club_id', $clubId)
        ->whereYear('fecha', $anio)
        ->where('tipo', 'Egreso')
        ->sum('valor');

    $saldo = $ingresos - $gastos;

    $conceptos = ConceptoContable::where('club_id', $clubId)
        ->where('activo', 1)
        ->orderBy('nombre')
        ->get();

    return view(
        'contabilidad.index',
        compact(
            'movimientos',
            'ingresos',
            'gastos',
            'saldo',
            'conceptos',
            'anio'
        )
    );
}
   public function create()
{
    $clubId = auth()->user()->club_id;

    $conceptos = ConceptoContable::where('club_id', $clubId)
        ->where('activo', 1)
        ->orderBy('nombre')
        ->get();

    $jugadores = Jugador::where('club_id', $clubId)
        ->orderBy('apellidos')
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
    $clubId = auth()->user()->club_id;

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

    $conceptoValido = ConceptoContable::where('id', $datos['concepto_contable_id'])
        ->where('club_id', $clubId)
        ->exists();

    if (!$conceptoValido) {
        return back()
            ->withErrors([
                'concepto_contable_id' => 'El concepto no pertenece a este club.'
            ])
            ->withInput();
    }

    $datos['club_id'] = $clubId;

    Contabilidad::create($datos);

    return redirect()
        ->route('contabilidad.index')
        ->with('success', 'Movimiento registrado correctamente.');
}

    public function edit(Contabilidad $contabilidad)
{
    abort_unless(
        $contabilidad->club_id == auth()->user()->club_id,
        403
    );

    $clubId = auth()->user()->club_id;

    $conceptos = ConceptoContable::where('club_id', $clubId)
        ->where('activo', 1)
        ->orderBy('nombre')
        ->get();

    $jugadores = Jugador::where('club_id', $clubId)
        ->orderBy('apellidos')
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
    $clubId = auth()->user()->club_id;

    // Verificar que el movimiento pertenece al club actual
    abort_unless(
        $contabilidad->club_id == $clubId,
        403
    );

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

    // Verificar que el concepto pertenece al mismo club
    $conceptoValido = ConceptoContable::where('id', $datos['concepto_contable_id'])
        ->where('club_id', $clubId)
        ->exists();

    if (!$conceptoValido) {
        return back()
            ->withErrors([
                'concepto_contable_id' => 'El concepto no pertenece a este club.'
            ])
            ->withInput();
    }

    // Mantener el movimiento asociado al club
    $datos['club_id'] = $clubId;

    $contabilidad->update($datos);

    return redirect()
        ->route('contabilidad.index')
        ->with('success', 'Movimiento actualizado correctamente.');
}


   public function destroy(Contabilidad $contabilidad)
{
    abort_unless(
        $contabilidad->club_id == auth()->user()->club_id,
        403
    );

    $contabilidad->delete();

    return redirect()
        ->route('contabilidad.index')
        ->with('success', 'Movimiento eliminado.');
}
}
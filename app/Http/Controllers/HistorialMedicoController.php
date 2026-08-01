<?php

namespace App\Http\Controllers;

use App\Models\HistorialMedico;
use App\Models\Jugador;
use Illuminate\Http\Request;


class HistorialMedicoController extends Controller
{
public function index(Request $request)
{
    $jugador = null;

    $query = HistorialMedico::with('jugador')
        ->orderByDesc('fecha')
        ->orderByDesc('created_at');

    if($request->filled('jugador')){

        $jugador = Jugador::findOrFail($request->jugador);

        $query->where('jugador_id',$jugador->id);
    }

    if($request->filled('buscar')){
        if($request->filled('estado')){

    $query->where('estado',$request->estado);

}

        $query->whereHas('jugador',function($q) use($request){

            $q->where('nombres','like','%'.$request->buscar.'%')
              ->orWhere('apellidos','like','%'.$request->buscar.'%');

        });

    }

    $historial = $query->get();

  return view('medico.index', compact(
    'historial',
    'jugador'
));
}

  public function create(Request $request)
{
    $jugador = null;

    if($request->filled('jugador')){
        $jugador = Jugador::find($request->jugador);
    }

    $jugadores = Jugador::orderBy('nombres')->get();

  return view('medico.create', compact(
    'jugador',
    'jugadores'
));
}

public function store(Request $request)
{
$datos = $request->validate([
    'jugador_id'   => 'required|exists:jugadores,id',
    'fecha'        => 'required|date',
    'tipo'         =>  'nullable|string',
    'diagnostico'  =>  'nullable|string',

    'zona'              => 'nullable|string',
    'tratamiento'       => 'nullable|string',
    'estado'            => 'nullable|string',
    'dias_incapacidad'  => 'nullable|integer',
    'fecha_alta'        => 'nullable|date',
    'observaciones'     => 'nullable|string',
]);

$jugador = Jugador::findOrFail($datos['jugador_id']);

$datos['club_id'] = $jugador->club_id;

  HistorialMedico::create($datos);

    return redirect()
        ->route('historial-medico.index')
        ->with('success','Registro médico guardado correctamente.');
}

 public function edit(HistorialMedico $historial)
{
    $jugadores = Jugador::orderBy('nombres')->get();

    return view('medico.edit', [
        'historialMedico' => $historial,
        'jugadores' => $jugadores,
    ]);

}

  public function update(Request $request, HistorialMedico $historial)
    {
        $datos = $request->validate([

            'fecha' => 'required|date',

            'tipo' => 'required|max:100',

            'zona' => 'nullable|max:100',

            'diagnostico' => 'nullable',

            'tratamiento' => 'nullable',

            'dias_incapacidad' => 'nullable|integer|min:0',

            'fecha_alta' => 'nullable|date',

            'estado' => 'required',

            'observaciones' => 'nullable',

        ]);

        $historial->update($datos);

       return redirect()
    ->route('historial-medico.index')
    ->with('success', 'Registro actualizado correctamente.');
    }

    public function destroy(HistorialMedico $historialMedico)
    {
        $jugador = $historialMedico->jugador_id;

        $historialMedico->delete();


return redirect()
        ->route('historial-medico.index')
        ->with('success','Registro eliminado.');
    }
}

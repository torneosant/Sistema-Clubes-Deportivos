<?php

namespace App\Http\Controllers;

use App\Models\Entrenamiento;
use Illuminate\Http\Request;
use App\Models\Equipo;
use App\Models\Entrenador;
use App\Models\Categoria;

class EntrenamientoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index()
{
    $entrenamientos = Entrenamiento::with([
    'equipo',
    'entrenador',
    'categorias'

    ])
    
    ->orderBy('fecha','desc')
    ->paginate(10);

    return view('entrenamientos.index', compact(
        'entrenamientos'
    ));
}

    /**
     * Show the form for creating a new resource.
     */
   public function create()
{
    $equipos = Equipo::where('activo',1)
        ->orderBy('nombre')
        ->get();

    $entrenadores = Entrenador::where('activo',1)
        ->orderBy('nombres')
        ->get();

    $categorias = Categoria::where('activo',1)
        ->orderBy('nombre')
        ->get();

    return view('entrenamientos.create', compact(
        'equipos',
        'entrenadores',
        'categorias'
    ));
}

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
{
    $datos = $request->validate([
        'equipo_id'      => 'required|exists:equipos,id',
        'entrenador_id'  => 'required|exists:entrenadors,id',
        'fecha'          => 'required|date',
        'hora_inicio'    => 'required',
        'hora_fin'       => 'nullable',
        'lugar'          => 'nullable|max:255',
        'tipo'           => 'nullable|max:100',
        'estado'         => 'required|max:50',
        'observaciones'  => 'nullable',
        'recurrente' => 'nullable|boolean',
    ],[
        'equipo_id.required' => 'Debe seleccionar un equipo.',
        'entrenador_id.required' => 'Debe seleccionar un entrenador.',
        'fecha.required' => 'Debe seleccionar la fecha del entrenamiento.',
        'hora_inicio.required' => 'Debe ingresar la hora de inicio.',
    ]);
    $datos['es_recurrente'] = $request->has('recurrente');

    $datos['dias_semana'] = $request->dias_semana
    ? json_encode($request->dias_semana)
    : null;

$datos['fecha_fin'] = $request->fecha_fin;



    $datos['club_id'] = 1;

    $entrenamiento = Entrenamiento::create($datos);

    if ($request->has('categorias')) {
        $entrenamiento->categorias()->sync($request->categorias);
    }

    return redirect()
        ->route('entrenamientos.index')
        ->with('success','Entrenamiento registrado correctamente.');
}
    /**
     * Display the specified resource.
     */
    public function show(Entrenamiento $entrenamiento)
{
    return view('entrenamientos.show', compact(
        'entrenamiento'
    ));
}

    /**
     * Show the form for editing the specified resource.
     */
public function edit(Entrenamiento $entrenamiento)
{
    $equipos = Equipo::where('activo',1)
        ->orderBy('nombre')
        ->get();

    $entrenadores = Entrenador::where('activo',1)
        ->orderBy('nombres')
        ->get();

    $categorias = Categoria::where('activo',1)
        ->orderBy('nombre')
        ->get();

    return view('entrenamientos.edit', compact(
        'entrenamiento',
        'equipos',
        'entrenadores',
        'categorias'
    ));
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Entrenamiento $entrenamiento)
{
    $datos = $request->validate([

        'equipo_id'      => 'required|exists:equipos,id',

        'entrenador_id'  => 'required|exists:entrenadors,id',

        'fecha'          => 'required|date',

        'hora_inicio'    => 'required',

        'hora_fin'       => 'nullable',

        'lugar'          => 'nullable|max:255',

        'tipo'           => 'nullable|max:100',

        'estado'         => 'required|max:50',

        'observaciones'  => 'nullable',

        'recurrente' => 'nullable|boolean',

    ]);

    $datos['es_recurrente'] = $request->has('recurrente');


    $datos['dias_semana'] = $request->dias_semana
    ? json_encode($request->dias_semana)
    : null;

$datos['fecha_fin'] = $request->fecha_fin;


  $entrenamiento->update($datos);

if ($request->has('categorias')) {
    $entrenamiento->categorias()->sync($request->categorias);
} else {
    $entrenamiento->categorias()->detach();
}

return redirect()
    ->route('entrenamientos.index')
    ->with('success','Entrenamiento actualizado correctamente.');
}

    /**
     * Remove the specified resource from storage.
     */
   public function destroy(Entrenamiento $entrenamiento)
{
    $entrenamiento->delete();

    return redirect()
        ->route('entrenamientos.index')
        ->with('success','Entrenamiento eliminado correctamente.');
}
public function cambiarEstado(Request $request, Entrenamiento $entrenamiento)
{
    $request->validate([
        'estado' => 'required|in:Programado,Realizado,Cancelado',
    ]);

    $entrenamiento->update([
        'estado' => $request->estado
    ]);

    return back()->with('success', 'Estado actualizado correctamente.');
}
}

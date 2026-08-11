<?php

namespace App\Http\Controllers;

use App\Models\Partido;
use Illuminate\Http\Request;
use App\Models\Equipo;
use App\Models\Categoria;


class PartidoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index()
{
    $clubId = auth()->user()->club_id;

    $partidos = Partido::where('club_id', $clubId)
        ->with(['equipo', 'categoria'])
        ->orderByDesc('fecha')
        ->orderByDesc('hora')
        ->get();

    return view('partidos.index', compact('partidos'));
}
    /**
     * Show the form for creating a new resource.
     */
   public function create()
{
    $equipos = Equipo::where('activo',1)
        ->orderBy('nombre')
        ->get();

    $categorias = Categoria::where('activo',1)
        ->orderBy('nombre')
        ->get();

    return view('partidos.create', compact(
        'equipos',
        'categorias'
    ));
}

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
{
    $datos = $request->validate([

        'equipo_id' => 'required|exists:equipos,id',

        'categoria_id' => 'required|exists:categorias,id',

        'competencia' => 'nullable|string|max:100',

        'rival' => 'required|string|max:100',

        'fecha' => 'required|date',

        'hora' => 'required',

        'lugar' => 'nullable|string|max:100',

        'condicion' => 'required'

    ]);

    $datos['club_id'] = auth()->user()->club_id;

    Partido::create($datos);

    return redirect()
        ->route('partidos.index')
        ->with('success','Partido creado correctamente.');
}

    /**
     * Display the specified resource.
     */
    public function show(Partido $partido)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
   public function edit(Partido $partido)
{
    $equipos = Equipo::where('activo',1)
        ->orderBy('nombre')
        ->get();

    $categorias = Categoria::where('activo',1)
        ->orderBy('nombre')
        ->get();

    return view(
        'partidos.edit',
        compact(
            'partido',
            'equipos',
            'categorias'
        )
    );
}

    /**
     * Update the specified resource in storage.
     */
public function update(Request $request, Partido $partido)
{
    $datos = $request->validate([

        'equipo_id'      => 'required|exists:equipos,id',

        'categoria_id'   => 'required|exists:categorias,id',

        'competencia'    => 'nullable|string|max:100',

        'rival'          => 'required|string|max:100',

        'fecha'          => 'required|date',

        'hora'           => 'required',

        'lugar'          => 'nullable|string|max:100',

        'condicion'      => 'required|in:Local,Visitante',

        'estado'         => 'required|in:Programado,Jugado,Aplazado,Suspendido,Cancelado',

    ]);

    $partido->update($datos);

    return redirect()
        ->route('partidos.index')
        ->with('success', 'Partido actualizado correctamente.');
}
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Partido $partido)
{
    $partido->delete();

    return redirect()
        ->route('partidos.index')
        ->with('success', 'Partido eliminado correctamente.');
}
    


public function resultado(Partido $partido)
{
    return view('partidos.resultado', compact('partido'));
}

public function guardarResultado(Request $request, Partido $partido)
{
    $datos = $request->validate([
        'goles_favor'   => 'required|integer|min:0',
        'goles_contra'  => 'required|integer|min:0',
        'observaciones' => 'nullable|string',
    ]);

    $datos['estado'] = 'Jugado';

    $partido->update($datos);

    return redirect()
        ->route('partidos.index')
        ->with('success', 'Resultado registrado correctamente.');
}
}

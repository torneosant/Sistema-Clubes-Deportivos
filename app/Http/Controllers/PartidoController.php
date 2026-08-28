<?php

namespace App\Http\Controllers;

use App\Models\Partido;
use App\Models\Equipo;
use App\Models\Categoria;
use App\Models\Configuracion;
use App\Models\Competencia;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Jugador;
use App\Models\PartidoJugador;

class PartidoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clubId = auth()->user()->club_id;

        $configuracion = Configuracion::find($clubId);

        $anio = session(
            'anio_trabajo',
            $configuracion?->anio ?? date('Y')
        );

        $partidos = Partido::where('club_id', $clubId)
            ->whereYear('fecha', $anio)
            ->with(['equipo', 'categoria', 'competencia'])
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
        $clubId = auth()->user()->club_id;

        $equipos = Equipo::where('activo', 1)
            ->orderBy('nombre')
            ->get();

        $categorias = Categoria::where('activo', 1)
            ->orderBy('nombre')
            ->get();

        $competencias = Competencia::where('club_id', $clubId)
            ->where('activo', 1)
            ->orderBy('nombre')
            ->get();

        return view('partidos.create', compact(
            'equipos',
            'categorias',
            'competencias'
        ));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $clubId = auth()->user()->club_id;

        $datos = $request->validate([

            'equipo_id' => 'required|exists:equipos,id',

            'categoria_id' => 'required|exists:categorias,id',

            'competencia_id' => [
                'nullable',
                Rule::exists('competencias', 'id')
                    ->where(fn ($query) => $query->where('club_id', $clubId)),
            ],

            'rival' => 'required|string|max:100',

            'fecha' => 'required|date',

            'hora' => 'required',

            'lugar' => 'nullable|string|max:100',

            'condicion' => 'required|in:Local,Visitante',

        ]);

        /*
        |--------------------------------------------------------------------------
        | Mantener compatibilidad con el campo competencia anterior
        |--------------------------------------------------------------------------
        */

        if (!empty($datos['competencia_id'])) {

            $competencia = Competencia::where('club_id', $clubId)
                ->findOrFail($datos['competencia_id']);

            $datos['competencia'] = $competencia->nombre;

        } else {

            $datos['competencia'] = null;

        }


        $datos['club_id'] = $clubId;

        Partido::create($datos);

        return redirect()
            ->route('partidos.index')
            ->with('success', 'Partido creado correctamente.');
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
        $clubId = auth()->user()->club_id;

        $equipos = Equipo::where('activo', 1)
            ->orderBy('nombre')
            ->get();

        $categorias = Categoria::where('activo', 1)
            ->orderBy('nombre')
            ->get();

        $competencias = Competencia::where('club_id', $clubId)
            ->where('activo', 1)
            ->orderBy('nombre')
            ->get();

        return view(
            'partidos.edit',
            compact(
                'partido',
                'equipos',
                'categorias',
                'competencias'
            )
        );
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Partido $partido)
    {
        $clubId = auth()->user()->club_id;

        $datos = $request->validate([

            'equipo_id' => 'required|exists:equipos,id',

            'categoria_id' => 'required|exists:categorias,id',

            'competencia_id' => [
                'nullable',
                Rule::exists('competencias', 'id')
                    ->where(fn ($query) => $query->where('club_id', $clubId)),
            ],

            'rival' => 'required|string|max:100',

            'fecha' => 'required|date',

            'hora' => 'required',

            'lugar' => 'nullable|string|max:100',

            'condicion' => 'required|in:Local,Visitante',

            'estado' => 'required|in:Programado,Jugado,Aplazado,Suspendido,Cancelado',

        ]);


        /*
        |--------------------------------------------------------------------------
        | Mantener actualizado el campo competencia anterior
        |--------------------------------------------------------------------------
        */

        if (!empty($datos['competencia_id'])) {

            $competencia = Competencia::where('club_id', $clubId)
                ->findOrFail($datos['competencia_id']);

            $datos['competencia'] = $competencia->nombre;

        } else {

            $datos['competencia'] = null;

        }


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


    /**
     * Mostrar resultado.
     */
    public function resultado(Partido $partido)
    {
        return view('partidos.resultado', compact('partido'));
    }


    /**
     * Guardar resultado.
     */
    public function guardarResultado(Request $request, Partido $partido)
    {
        $datos = $request->validate([

            'goles_favor' => 'required|integer|min:0',

            'goles_contra' => 'required|integer|min:0',

            'observaciones' => 'nullable|string',

        ]);

        $datos['estado'] = 'Jugado';

        $partido->update($datos);

        return redirect()
            ->route('partidos.index')
            ->with('success', 'Resultado registrado correctamente.');
    }
/*
|--------------------------------------------------------------------------
| Estadísticas del partido
|--------------------------------------------------------------------------
*/

public function estadisticas(Partido $partido)
{
    $clubId = auth()->user()->club_id;

    /*
    |--------------------------------------------------------------------------
    | Seguridad
    |--------------------------------------------------------------------------
    */

    abort_unless(
        $partido->club_id === $clubId,
        403
    );


    /*
    |--------------------------------------------------------------------------
    | Cargar relaciones
    |--------------------------------------------------------------------------
    */

    $partido->load([
        'equipo',
        'categoria',
        'competencia',
    ]);


    /*
    |--------------------------------------------------------------------------
    | Jugadoras disponibles
    |--------------------------------------------------------------------------
    |
    | Si el partido pertenece a una competencia:
    | usamos las jugadoras inscritas en la planilla.
    |
    | Si es amistoso/sin competencia:
    | usamos las jugadoras del equipo y categoría.
    |
    */

    if ($partido->competencia_id) {

        $jugadores = $partido->competencia
            ->jugadores()
            ->where('jugadores.club_id', $clubId)
            ->where('jugadores.activo', 1)
            ->orderBy('apellidos')
            ->orderBy('nombres')
            ->get();

    } else {

        $jugadores = Jugador::where('club_id', $clubId)
            ->where('equipo_id', $partido->equipo_id)
            ->where('categoria_id', $partido->categoria_id)
            ->where('activo', 1)
            ->orderBy('apellidos')
            ->orderBy('nombres')
            ->get();

    }


    /*
    |--------------------------------------------------------------------------
    | Estadísticas ya registradas
    |--------------------------------------------------------------------------
    */

    $estadisticas = PartidoJugador::where(
        'partido_id',
        $partido->id
    )
        ->get()
        ->keyBy('jugador_id');


    return view(
        'partidos.estadisticas',
        compact(
            'partido',
            'jugadores',
            'estadisticas'
        )
    );
}


/*
|--------------------------------------------------------------------------
| Guardar estadísticas del partido
|--------------------------------------------------------------------------
*/

public function guardarEstadisticas(
    Request $request,
    Partido $partido
) {

    $clubId = auth()->user()->club_id;

    /*
    |--------------------------------------------------------------------------
    | Seguridad
    |--------------------------------------------------------------------------
    */

    abort_unless(
        $partido->club_id === $clubId,
        403
    );


    /*
    |--------------------------------------------------------------------------
    | Validación
    |--------------------------------------------------------------------------
    */

    $datos = $request->validate([

        'participacion' => 'nullable|array',

        'participacion.*' =>
            'nullable|in:No jugó,Suplente,Titular',

        'minutos' => 'nullable|array',

        'minutos.*' =>
            'nullable|integer|min:0|max:120',

        'goles' => 'nullable|array',

        'goles.*' =>
            'nullable|integer|min:0',

        'asistencias' => 'nullable|array',

        'asistencias.*' =>
            'nullable|integer|min:0',

        'amarillas' => 'nullable|array',

        'amarillas.*' =>
            'nullable|integer|min:0',

        'rojas' => 'nullable|array',

        'rojas.*' =>
            'nullable|integer|min:0',

        'figura' => 'nullable|array',

        'figura.*' =>
            'nullable|boolean',

    ]);


    /*
    |--------------------------------------------------------------------------
    | Jugadoras válidas para este partido
    |--------------------------------------------------------------------------
    */

    if ($partido->competencia_id) {

        $jugadoresValidos = $partido->competencia
            ->jugadores()
            ->where('jugadores.club_id', $clubId)
            ->pluck('jugadores.id');

    } else {

        $jugadoresValidos = Jugador::where('club_id', $clubId)
            ->where('equipo_id', $partido->equipo_id)
            ->where('categoria_id', $partido->categoria_id)
            ->pluck('id');

    }


    /*
    |--------------------------------------------------------------------------
    | Guardar cada jugadora
    |--------------------------------------------------------------------------
    */

    foreach ($jugadoresValidos as $jugadorId) {

        $participacion =
            $datos['participacion'][$jugadorId] ?? 'No jugó';

        $minutos =
            $datos['minutos'][$jugadorId] ?? 0;

        $goles =
            $datos['goles'][$jugadorId] ?? 0;

        $asistencias =
            $datos['asistencias'][$jugadorId] ?? 0;

        $amarillas =
            $datos['amarillas'][$jugadorId] ?? 0;

        $rojas =
            $datos['rojas'][$jugadorId] ?? 0;

        $figura =
            isset($datos['figura'][$jugadorId]) &&
            $datos['figura'][$jugadorId];


        /*
        |--------------------------------------------------------------------------
        | Guardar / actualizar
        |--------------------------------------------------------------------------
        */

        PartidoJugador::updateOrCreate(

            [
                'partido_id' => $partido->id,
                'jugador_id' => $jugadorId,
            ],

            [
                'participacion' => $participacion,
                'minutos' => $minutos,
                'goles' => $goles,
                'asistencias' => $asistencias,
                'amarillas' => $amarillas,
                'rojas' => $rojas,
                'figura' => $figura,
            ]

        );

    }


    return redirect()
        ->route(
            'partidos.estadisticas',
            $partido
        )
        ->with(
            'success',
            'Estadísticas del partido guardadas correctamente.'
        );
}

}
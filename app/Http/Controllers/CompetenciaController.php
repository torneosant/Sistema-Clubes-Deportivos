<?php

namespace App\Http\Controllers;

use App\Models\Competencia;
use App\Models\Categoria;
use Illuminate\Http\Request;

class CompetenciaController extends Controller
{
    public function index()
    {
        $clubId = auth()->user()->club_id;

        $competencias = Competencia::with('categoria')
            ->where('club_id', $clubId)
            ->orderByDesc('fecha_inicio')
            ->orderByDesc('id')
            ->get();

        return view(
            'competencias.index',
            compact('competencias')
        );
    }

    public function create()
    {
        $clubId = auth()->user()->club_id;

        $categorias = Categoria::where('club_id', $clubId)
            ->orderBy('nombre')
            ->get();

        return view(
            'competencias.create',
            compact('categorias')
        );
    }

    public function store(Request $request)
    {
        $clubId = auth()->user()->club_id;

        $datos = $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|in:campeonato,festival,evento',
            'estado' => 'required|in:proximo,en_curso,finalizado,cancelado',
            'categoria_id' => 'nullable|exists:categorias,id',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'lugar' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        $datos['club_id'] = $clubId;
        $datos['activo'] = true;

        Competencia::create($datos);

        return redirect()
            ->route('competencias.index')
            ->with('success', 'Competencia creada correctamente.');
    }

    public function show(Competencia $competencia)
    {
        $this->validarClub($competencia);

        $competencia->load('categoria');

        return view(
            'competencias.show',
            compact('competencia')
        );
    }

    public function edit(Competencia $competencia)
    {
        $this->validarClub($competencia);

        $clubId = auth()->user()->club_id;

        $categorias = Categoria::where('club_id', $clubId)
            ->orderBy('nombre')
            ->get();

        return view(
            'competencias.edit',
            compact('competencia', 'categorias')
        );
    }

    public function update(Request $request, Competencia $competencia)
    {
        $this->validarClub($competencia);

      

        $datos = $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|in:campeonato,festival,evento',
            'estado' => 'required|in:proximo,en_curso,finalizado,cancelado',
            'categoria_id' => 'nullable|exists:categorias,id',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'lugar' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

       

        $competencia->update($datos);

        return redirect()
            ->route('competencias.index')
            ->with('success', 'Competencia actualizada correctamente.');
    }

    public function destroy(Competencia $competencia)
    {
        $this->validarClub($competencia);

        $competencia->delete();

        return redirect()
            ->route('competencias.index')
            ->with('success', 'Competencia eliminada correctamente.');
    }

    private function validarClub(Competencia $competencia)
    {
        abort_unless(
            $competencia->club_id === auth()->user()->club_id,
            403
        );
    }

    public function estadisticas(Competencia $competencia)
{
    $this->validarClub($competencia);

    /*
    |--------------------------------------------------------------------------
    | Partidos de esta competencia
    |--------------------------------------------------------------------------
    */

    $partidos = $competencia->partidos()
        ->where('estado', 'Jugado')
        ->get();


    /*
    |--------------------------------------------------------------------------
    | Estadísticas básicas
    |--------------------------------------------------------------------------
    */

    $pj = $partidos->count();

    $pg = $partidos->where(
        fn ($partido) =>
            $partido->goles_favor > $partido->goles_contra
    )->count();

    $pe = $partidos->where(
        fn ($partido) =>
            $partido->goles_favor == $partido->goles_contra
    )->count();

    $pp = $partidos->where(
        fn ($partido) =>
            $partido->goles_favor < $partido->goles_contra
    )->count();


    /*
    |--------------------------------------------------------------------------
    | Goles
    |--------------------------------------------------------------------------
    */

    $gf = $partidos->sum('goles_favor');

    $gc = $partidos->sum('goles_contra');

    $dg = $gf - $gc;


    /*
    |--------------------------------------------------------------------------
    | Puntos
    |--------------------------------------------------------------------------
    |
    | Victoria = 3
    | Empate   = 1
    | Derrota  = 0
    |
    */

    $puntos = ($pg * 3) + $pe;

    $puntosAdicionales = (int) ($competencia->puntos_adicionales ?? 0);

    $puntosTotales = $puntos + $puntosAdicionales;


    /*
    |--------------------------------------------------------------------------
    | Rendimiento
    |--------------------------------------------------------------------------
    |
    | Se calcula sobre los puntos posibles de los partidos jugados.
    | Los puntos adicionales NO influyen en este porcentaje.
    |
    */

    $puntosPosibles = $pj * 3;

    $rendimiento = $puntosPosibles > 0
        ? round(($puntos / $puntosPosibles) * 100, 2)
        : 0;


    /*
    |--------------------------------------------------------------------------
    | Promedios
    |--------------------------------------------------------------------------
    */

    $promedioGf = $pj > 0
        ? round($gf / $pj, 2)
        : 0;

    $promedioGc = $pj > 0
        ? round($gc / $pj, 2)
        : 0;


    /*
    |--------------------------------------------------------------------------
    | Efectividad goleadora
    |--------------------------------------------------------------------------
    |
    | Goles a favor / (goles a favor + goles en contra)
    |
    */

    $totalGoles = $gf + $gc;

    $efectividadGoleadora = $totalGoles > 0
        ? round(($gf / $totalGoles) * 100, 2)
        : 0;


    /*
    |--------------------------------------------------------------------------
    | Partidos marcando
    |--------------------------------------------------------------------------
    */

    $partidosMarcando = $partidos
        ->where('goles_favor', '>', 0)
        ->count();

    $porcentajePartidosMarcando = $pj > 0
        ? round(($partidosMarcando / $pj) * 100, 2)
        : 0;


    /*
    |--------------------------------------------------------------------------
    | Porterías en cero
    |--------------------------------------------------------------------------
    */

    $porteriasCero = $partidos
        ->where('goles_contra', 0)
        ->count();

    $porcentajePorteriasCero = $pj > 0
        ? round(($porteriasCero / $pj) * 100, 2)
        : 0;


    /*
    |--------------------------------------------------------------------------
    | Goleadoras
    |--------------------------------------------------------------------------
    */

    $goleadoras = \App\Models\PartidoJugador::query()
        ->whereIn('partido_id', $partidos->pluck('id'))
        ->with('jugador')
        ->get()
        ->groupBy('jugador_id')
        ->map(function ($registros) {

            $primerRegistro = $registros->first();

            return (object) [
                'jugador' => $primerRegistro->jugador,
                'partidos' => $registros->count(),
                'goles' => $registros->sum('goles'),
                'asistencias' => $registros->sum('asistencias'),
            ];

        })
        ->sortByDesc('goles')
        ->values();


    /*
    |--------------------------------------------------------------------------
    | Vista
    |--------------------------------------------------------------------------
    */

    return view(
        'competencias.estadisticas',
        compact(
            'competencia',
            'partidos',
            'pj',
            'pg',
            'pe',
            'pp',
            'gf',
            'gc',
            'dg',
            'puntos',
            'puntosAdicionales',
            'puntosTotales',
            'rendimiento',
            'promedioGf',
            'promedioGc',
            'efectividadGoleadora',
            'porcentajePartidosMarcando',
            'porteriasCero',
            'porcentajePorteriasCero',
            'goleadoras'
        )
    );
}
}
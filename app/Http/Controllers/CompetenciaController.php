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
}
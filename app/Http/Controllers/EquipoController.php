<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EquipoController extends Controller
{
    public function index(Request $request)
    {
        $clubId = auth()->user()->club_id;

        $buscar = trim($request->buscar);

        $equipos = Equipo::with('categorias')
            ->where('club_id', $clubId)
            ->when($buscar, function ($query) use ($buscar) {
                $query->where('nombre', 'like', "%{$buscar}%");
            })
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();

        $totalEquipos = Equipo::where('club_id', $clubId)->count();

        $totalActivos = Equipo::where('club_id', $clubId)
            ->where('activo', true)
            ->count();

        return view('equipos.index', compact(
            'equipos',
            'buscar',
            'totalEquipos',
            'totalActivos'
        ));
    }

    public function create()
    {
        $clubId = auth()->user()->club_id;

        $categorias = Categoria::where('club_id', $clubId)
            ->where('activo', true)
            ->orderBy('nombre')
            ->get();

        return view(
            'equipos.create',
            compact('categorias')
        );
    }

    public function store(Request $request)
    {
        $clubId = auth()->user()->club_id;

        $datos = $request->validate([
            'nombre' => 'required|max:150',
            'categorias' => 'required|array',
            'categorias.*' => 'exists:categorias,id',
            'color_principal' => 'nullable|max:50',
            'color_secundario' => 'nullable|max:50',
            'descripcion' => 'nullable',
            'escudo' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
        ]);

        // Verificar que las categorías pertenezcan al club
        $categoriasValidas = Categoria::where('club_id', $clubId)
            ->whereIn('id', $request->categorias)
            ->count();

        if ($categoriasValidas != count($request->categorias)) {
            abort(403);
        }

        if ($request->hasFile('escudo')) {
            $datos['escudo'] = $request
                ->file('escudo')
                ->store('equipos', 'public');
        }

        $datos['club_id'] = $clubId;
        $datos['activo'] = true;

        $equipo = Equipo::create($datos);

        $equipo->categorias()->sync(
            $request->categorias ?? []
        );

        return redirect()
            ->route('equipos.index')
            ->with('success', 'Equipo creado correctamente.');
    }

    public function edit(Equipo $equipo)
    {
        $this->verificarClub($equipo);

        $clubId = auth()->user()->club_id;

        $categorias = Categoria::where('club_id', $clubId)
            ->where('activo', true)
            ->orderBy('nombre')
            ->get();

        $equipo->load('categorias');

        return view('equipos.edit', compact(
            'equipo',
            'categorias'
        ));
    }

    public function update(Request $request, Equipo $equipo)
    {
        $this->verificarClub($equipo);

        $clubId = auth()->user()->club_id;

        $datos = $request->validate([
            'nombre' => 'required|max:150',
            'categorias' => 'required|array',
            'categorias.*' => 'exists:categorias,id',
            'color_principal' => 'nullable|max:50',
            'color_secundario' => 'nullable|max:50',
            'descripcion' => 'nullable',
            'escudo' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
        ]);

        // Verificar que las categorías pertenezcan al mismo club
        $categoriasValidas = Categoria::where('club_id', $clubId)
            ->whereIn('id', $request->categorias)
            ->count();

        if ($categoriasValidas != count($request->categorias)) {
            abort(403);
        }

        if ($request->hasFile('escudo')) {

            if (
                $equipo->escudo &&
                Storage::disk('public')->exists($equipo->escudo)
            ) {
                Storage::disk('public')->delete($equipo->escudo);
            }

            $datos['escudo'] = $request
                ->file('escudo')
                ->store('equipos', 'public');
        }

        $equipo->update($datos);

        $equipo->categorias()->sync(
            $request->categorias ?? []
        );

        return redirect()
            ->route('equipos.index')
            ->with('success', 'Equipo actualizado correctamente.');
    }

    public function cambiarEstado(Equipo $equipo)
    {
        $this->verificarClub($equipo);

        $equipo->activo = !$equipo->activo;

        $equipo->save();

        return back()->with(
            'success',
            $equipo->activo
                ? 'Equipo activado correctamente.'
                : 'Equipo inactivado correctamente.'
        );
    }

    public function destroy(Equipo $equipo)
    {
        $this->verificarClub($equipo);

        if (
            $equipo->escudo &&
            Storage::disk('public')->exists($equipo->escudo)
        ) {
            Storage::disk('public')->delete($equipo->escudo);
        }

        $equipo->delete();

        return back()->with(
            'success',
            'Equipo eliminado correctamente.'
        );
    }

    public function porCategoria(Categoria $categoria)
    {
        $clubId = auth()->user()->club_id;

        // La categoría también debe pertenecer al club
        if ($categoria->club_id != $clubId) {
            abort(403);
        }

        return response()->json(

            Equipo::where('club_id', $clubId)
                ->whereHas('categorias', function ($q) use ($categoria) {

                    $q->where('categorias.id', $categoria->id);

                })
                ->where('activo', true)
                ->orderBy('nombre')
                ->get([
                    'id',
                    'nombre'
                ])

        );
    }

    private function verificarClub(Equipo $equipo)
    {
        if ($equipo->club_id != auth()->user()->club_id) {
            abort(403);
        }
    }
}
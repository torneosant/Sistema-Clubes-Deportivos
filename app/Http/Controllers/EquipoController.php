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
        $buscar = trim($request->buscar);

        $equipos = Equipo::with('categorias')
            ->when($buscar, function ($query) use ($buscar) {
                $query->where('nombre', 'like', "%{$buscar}%");
            })
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();

        $totalEquipos = Equipo::count();

        $totalActivos = Equipo::where('activo', true)->count();

       return view('equipos.index', compact(
    'equipos',
    'buscar',
    'totalEquipos',
    'totalActivos'
));
    }

    public function create()
    {
        $categorias = Categoria::where('activo',1)
    ->orderBy('nombre')
    ->get();

return view(
    'equipos.create',
    compact('categorias')
);
    }
       

    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre' => 'required|max:150',
            'categorias' => 'required|array',
                'categorias.*' => 'exists:categorias,id',
            'color_principal' => 'nullable|max:50',
            'color_secundario' => 'nullable|max:50',
            'descripcion' => 'nullable',
            'escudo' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
        ]);

        if ($request->hasFile('escudo')) {

            $datos['escudo'] = $request
                ->file('escudo')
                ->store('equipos', 'public');
        }

        $datos['club_id'] = 1;

        $datos['activo'] = true;

        $equipo = Equipo::create($datos);

$equipo->categorias()->sync(
    $request->categorias ?? []
);

return redirect()
    ->route('equipos.index')
    ->with('success', 'Equipo creado correctamente.');

$equipo->categorias()->sync(
    $request->categorias ?? []
);
    }

    public function edit(Equipo $equipo)
{
    $categorias = Categoria::where('activo', true)
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
        $datos = $request->validate([
            'nombre' => 'required|max:150',
            'categorias' => 'required|array',
            'categorias.*' => 'exists:categorias,id',
            'color_principal' => 'nullable|max:50',
            'color_secundario' => 'nullable|max:50',
            'descripcion' => 'nullable',
            'escudo' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
        ]);

        if ($request->hasFile('escudo')) {

            if ($equipo->escudo &&
                Storage::disk('public')->exists($equipo->escudo)) {

                Storage::disk('public')->delete($equipo->escudo);
            }

            $datos['escudo'] = $request
                ->file('escudo')
                ->store('equipos', 'public');
        }

        $equipo->update($datos);

     if ($request->has('categorias')) {

    $equipo->categorias()->sync($request->categorias);

} else {

    $equipo->categorias()->detach();

}

        return redirect()
            ->route('equipos.index')
            ->with('success', 'Equipo actualizado correctamente.');
    }

    public function cambiarEstado(Equipo $equipo)
    {
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
        if ($equipo->escudo &&
            Storage::disk('public')->exists($equipo->escudo)) {

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
    return response()->json(

        Equipo::whereHas('categorias', function ($q) use ($categoria) {

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
    }

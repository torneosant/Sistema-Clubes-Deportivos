<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index(Request $request)
    {
        $buscar = trim($request->get('buscar', ''));

        if ($buscar != '') {

            $categorias = Categoria::where('nombre', 'like', "%{$buscar}%")
                ->orderBy('nombre')
                ->paginate(10)
                ->withQueryString();

        } else {

            $categorias = Categoria::orderBy('nombre')
                ->paginate(10);

        }

        $totalCategorias = Categoria::count();

        $totalActivas = Categoria::where('activo', true)->count();

        return view('categorias.index', compact(
            'categorias',
            'buscar',
            'totalCategorias',
            'totalActivas'
        ));
    }

public function create()
{
    return view('categorias.create');
}

public function store(Request $request)
{
    $datos = $request->validate([
        'nombre' => 'required|max:100'
    ]);

    $datos['club_id'] = 1;
    $datos['activo'] = true;

    Categoria::create($datos);

    return redirect()->route('categorias.index')
        ->with('success', 'Categoría creada correctamente.');
}
public function edit(Categoria $categoria)
{
    return view('categorias.edit', compact('categoria'));
}

public function update(Request $request, Categoria $categoria)
{
    $datos = $request->validate([
        'nombre' => 'required|max:100'
    ]);

    $categoria->update($datos);

    return redirect()->route('categorias.index')
        ->with('success','Categoría actualizada correctamente.');
}
public function cambiarEstado(Categoria $categoria)
{
    $categoria->activo = !$categoria->activo;

    $categoria->save();

    return back()->with(
        'success',
        $categoria->activo
            ? 'Categoría activada correctamente.'
            : 'Categoría inactivada correctamente.'
    );
}
public function destroy(Categoria $categoria)
{
    $categoria->delete();

    return redirect()->route('categorias.index')
        ->with('success', 'Categoría eliminada correctamente.');
}

public function porEquipo(\App\Models\Equipo $equipo)
{
    return response()->json(

        $equipo->categorias()
            ->orderBy('nombre')
            ->get(['categorias.id','categorias.nombre'])

    );
}

}
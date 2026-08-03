<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventario;
use App\Models\TipoArticulo;    
use App\Models\MovimientoInventario;
use App\Exports\InventarioExport;
use Maatwebsite\Excel\Facades\Excel;

class InventarioController extends Controller
{
public function index()
{
    $articulos = Inventario::with('tipoArticulo')
                    ->orderBy('nombre')
                    ->get();

    foreach ($articulos as $articulo) {

        $asignado = $articulo->asignaciones()
            ->where('estado','Activa')
            ->sum('cantidad');

        $articulo->asignado = $asignado;

        $articulo->disponible = $articulo->cantidad - $asignado;
    }

    return view('inventario.articulos.index', compact('articulos'));
}



public function create()
{
    $tipos = TipoArticulo::where('activo',1)
                ->orderBy('nombre')
                ->get();

    return view('inventario.articulos.form',[
        'articulo'=>new Inventario(),
        'tipos'=>$tipos,
        'modo'=>'crear'
    ]);
}

public function store(Request $request)
{
    $request->validate([
        'nombre' => 'required|max:255',
        'tipo_articulo_id' => 'required|exists:tipo_articulos,id',
        'cantidad' => 'required|integer|min:0',
    ]);

    Inventario::create([
        'nombre' => $request->nombre,
        'codigo' => $request->codigo,
        'tipo_articulo_id' => $request->tipo_articulo_id,
        'marca' => $request->marca,
        'cantidad' => $request->cantidad,
        'estado' => $request->estado,
        'ubicacion' => $request->ubicacion,
        'observaciones' => $request->observaciones,
        'activo' => $request->has('activo'),
    ]);

    return redirect()->route('inventario.index')
        ->with('success', 'Artículo creado correctamente.');
}

public function edit(Inventario $inventario)
{
    $tipos = TipoArticulo::where('activo',1)
                ->orderBy('nombre')
                ->get();

    return view('inventario.articulos.form',[
        'articulo' => $inventario,
        'tipos'    => $tipos,
        'modo'     => 'editar'
    ]);
}

public function update(Request $request, Inventario $inventario)
{
    $request->validate([
        'nombre' => 'required|max:255',
        'tipo_articulo_id' => 'required|exists:tipo_articulos,id',
        'cantidad' => 'required|integer|min:0',
    ]);

    $inventario->update([

        'nombre' => $request->nombre,
        'codigo' => $request->codigo,
        'tipo_articulo_id' => $request->tipo_articulo_id,
        'marca' => $request->marca,
        'cantidad' => $request->cantidad,
        'estado' => $request->estado,
        'ubicacion' => $request->ubicacion,
        'observaciones' => $request->observaciones,
        'activo' => $request->has('activo'),

    ]);

    return redirect()
        ->route('inventario.index')
        ->with('success','Artículo actualizado correctamente.');
}



public function destroy(Inventario $inventario)
{
    $inventario->delete();

    return redirect()
        ->route('inventario.index')
        ->with('success', 'Artículo eliminado correctamente.');
}

public function trazabilidad(Request $request, Inventario $inventario)
{
    $query = MovimientoInventario::where('inventario_id', $inventario->id);

    if ($request->filled('desde')) {
        $query->whereDate('fecha', '>=', $request->desde);
    }

    if ($request->filled('hasta')) {
        $query->whereDate('fecha', '<=', $request->hasta);
    }

    if ($request->filled('responsable')) {
        $query->where('responsable', $request->responsable);
    }

    $movimientos = $query
        ->orderBy('fecha', 'desc')
        ->orderBy('id', 'desc')
        ->get();

    $responsables = MovimientoInventario::where('inventario_id', $inventario->id)
        ->select('responsable')
        ->distinct()
        ->orderBy('responsable')
        ->pluck('responsable');

    return view('inventario.trazabilidad', compact(
        'inventario',
        'movimientos',
        'responsables'
    ));
}

public function excel()
{
    return Excel::download(
        new InventarioExport,
        'inventario.xlsx'
    );
}

}
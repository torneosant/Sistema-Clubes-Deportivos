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
        $clubId = auth()->user()->club_id;

        $articulos = Inventario::with('tipoArticulo')
            ->where('club_id', $clubId)
            ->orderBy('nombre')
            ->get();

        foreach ($articulos as $articulo) {

            $asignado = $articulo->asignaciones()
                ->where('estado', 'Activa')
                ->sum('cantidad');

            $articulo->asignado = $asignado;
            $articulo->disponible = $articulo->cantidad - $asignado;
        }

        return view(
            'inventario.articulos.index',
            compact('articulos')
        );
    }


    public function create()
    {
        $clubId = auth()->user()->club_id;

        $tipos = TipoArticulo::where('activo', 1)
            ->where(function ($query) use ($clubId) {
                $query->whereNull('club_id')
                      ->orWhere('club_id', $clubId);
            })
            ->orderBy('nombre')
            ->get();

        return view('inventario.articulos.form', [
            'articulo' => new Inventario(),
            'tipos' => $tipos,
            'modo' => 'crear'
        ]);
    }


    public function store(Request $request)
    {
        $clubId = auth()->user()->club_id;

        $request->validate([
            'nombre' => 'required|max:255',
            'tipo_articulo_id' => 'required|exists:tipo_articulos,id',
            'cantidad' => 'required|integer|min:0',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Verificar que el tipo sea genérico o pertenezca al club
        |--------------------------------------------------------------------------
        */

        $tipo = TipoArticulo::where('id', $request->tipo_articulo_id)
            ->where(function ($query) use ($clubId) {
                $query->whereNull('club_id')
                      ->orWhere('club_id', $clubId);
            })
            ->first();

        if (!$tipo) {
            abort(
                403,
                'No tiene permiso para utilizar este tipo de artículo.'
            );
        }

        Inventario::create([
            'club_id' => $clubId,
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
            ->with('success', 'Artículo creado correctamente.');
    }


    public function edit(Inventario $inventario)
    {
        $clubId = auth()->user()->club_id;

        /*
        |--------------------------------------------------------------------------
        | El artículo debe pertenecer al club actual
        |--------------------------------------------------------------------------
        */

        if ($inventario->club_id != $clubId) {
            abort(
                403,
                'No tiene permiso para editar este artículo.'
            );
        }

        $tipos = TipoArticulo::where('activo', 1)
            ->where(function ($query) use ($clubId) {
                $query->whereNull('club_id')
                      ->orWhere('club_id', $clubId);
            })
            ->orderBy('nombre')
            ->get();

        return view('inventario.articulos.form', [
            'articulo' => $inventario,
            'tipos' => $tipos,
            'modo' => 'editar'
        ]);
    }


    public function update(
        Request $request,
        Inventario $inventario
    ) {
        $clubId = auth()->user()->club_id;

        /*
        |--------------------------------------------------------------------------
        | Seguridad: solo puede modificar inventario propio
        |--------------------------------------------------------------------------
        */

        if ($inventario->club_id != $clubId) {
            abort(
                403,
                'No tiene permiso para modificar este artículo.'
            );
        }

        $request->validate([
            'nombre' => 'required|max:255',
            'tipo_articulo_id' => 'required|exists:tipo_articulos,id',
            'cantidad' => 'required|integer|min:0',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Verificar tipo de artículo
        |--------------------------------------------------------------------------
        */

        $tipo = TipoArticulo::where('id', $request->tipo_articulo_id)
            ->where(function ($query) use ($clubId) {
                $query->whereNull('club_id')
                      ->orWhere('club_id', $clubId);
            })
            ->first();

        if (!$tipo) {
            abort(
                403,
                'No tiene permiso para utilizar este tipo de artículo.'
            );
        }

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
            ->with('success', 'Artículo actualizado correctamente.');
    }


    public function destroy(Inventario $inventario)
    {
        $clubId = auth()->user()->club_id;

        /*
        |--------------------------------------------------------------------------
        | Seguridad: solo puede eliminar inventario propio
        |--------------------------------------------------------------------------
        */

        if ($inventario->club_id != $clubId) {
            abort(
                403,
                'No tiene permiso para eliminar este artículo.'
            );
        }

        $inventario->delete();

        return redirect()
            ->route('inventario.index')
            ->with('success', 'Artículo eliminado correctamente.');
    }


    public function trazabilidad(
        Request $request,
        Inventario $inventario
    ) {
        $clubId = auth()->user()->club_id;

        /*
        |--------------------------------------------------------------------------
        | Seguridad: el inventario debe pertenecer al club
        |--------------------------------------------------------------------------
        */

        if ($inventario->club_id != $clubId) {
            abort(
                403,
                'No tiene permiso para consultar este inventario.'
            );
        }

        $query = MovimientoInventario::where(
            'inventario_id',
            $inventario->id
        );

        if ($request->filled('desde')) {
            $query->whereDate(
                'fecha',
                '>=',
                $request->desde
            );
        }

        if ($request->filled('hasta')) {
            $query->whereDate(
                'fecha',
                '<=',
                $request->hasta
            );
        }

        if ($request->filled('responsable')) {
            $query->where(
                'responsable',
                $request->responsable
            );
        }

        $movimientos = $query
            ->orderBy('fecha', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        $responsables = MovimientoInventario::where(
                'inventario_id',
                $inventario->id
            )
            ->select('responsable')
            ->distinct()
            ->orderBy('responsable')
            ->pluck('responsable');

        return view(
            'inventario.trazabilidad',
            compact(
                'inventario',
                'movimientos',
                'responsables'
            )
        );
    }


    public function excel()
    {
        return Excel::download(
            new InventarioExport,
            'inventario.xlsx'
        );
    }
}
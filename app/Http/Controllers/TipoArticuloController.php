<?php

namespace App\Http\Controllers;

use App\Models\TipoArticulo;
use Illuminate\Http\Request;

class TipoArticuloController extends Controller
{
    public function index()
    {
        $clubId = auth()->user()->club_id;

        $tipos = TipoArticulo::where(function ($query) use ($clubId) {

            // Tipos genéricos del sistema
            $query->whereNull('club_id')

                  // Tipos propios del club
                  ->orWhere('club_id', $clubId);

        })
        ->orderBy('nombre')
        ->get();

        return view(
            'inventario.tipos.index',
            compact('tipos')
        );
    }


    public function create()
    {
        return view('inventario.tipos.form', [
            'tipo' => new TipoArticulo(),
            'modo' => 'crear',
        ]);
    }


    public function store(Request $request)
{
    $clubId = auth()->user()->club_id;

    $request->validate([
        'nombre' => 'required|max:100',
    ]);

    $tipo = new TipoArticulo();

    $tipo->club_id = $clubId;
    $tipo->nombre = $request->nombre;
    $tipo->activo = $request->has('activo');

    $tipo->save();

    return redirect()
        ->route('tipos-articulo.index')
        ->with(
            'success',
            'Tipo de artículo creado correctamente.'
        );
}


    public function edit(TipoArticulo $tipos_articulo)
    {
        $clubId = auth()->user()->club_id;

        // No permitir editar tipos genéricos
        if ($tipos_articulo->club_id === null) {
            abort(
                403,
                'Los tipos del sistema no se pueden editar.'
            );
        }

        // No permitir editar tipos de otro club
        if ($tipos_articulo->club_id != $clubId) {
            abort(
                403,
                'No tiene permiso para editar este tipo de artículo.'
            );
        }

        return view('inventario.tipos.form', [
            'tipo' => $tipos_articulo,
            'modo' => 'editar',
        ]);
    }


    public function update(
        Request $request,
        TipoArticulo $tipos_articulo
    ) {
        $clubId = auth()->user()->club_id;

        if ($tipos_articulo->club_id === null) {
            abort(
                403,
                'Los tipos del sistema no se pueden modificar.'
            );
        }

        if ($tipos_articulo->club_id != $clubId) {
            abort(
                403,
                'No tiene permiso para modificar este tipo de artículo.'
            );
        }

        $request->validate([
            'nombre' => 'required|max:100',
        ]);

        $tipos_articulo->update([
            'nombre' => $request->nombre,
            'activo' => $request->has('activo'),
        ]);

        return redirect()
            ->route('tipos-articulo.index')
            ->with(
                'success',
                'Tipo de artículo actualizado correctamente.'
            );
    }


    public function destroy(TipoArticulo $tipos_articulo)
    {
        $clubId = auth()->user()->club_id;

        // Los genéricos NO se pueden eliminar
        if ($tipos_articulo->club_id === null) {
            abort(
                403,
                'Los tipos del sistema no se pueden eliminar.'
            );
        }

        // Un club no puede eliminar tipos de otro club
        if ($tipos_articulo->club_id != $clubId) {
            abort(
                403,
                'No tiene permiso para eliminar este tipo de artículo.'
            );
        }

        $tipos_articulo->delete();

        return redirect()
            ->route('tipos-articulo.index')
            ->with(
                'success',
                'Tipo de artículo eliminado correctamente.'
            );
    }
}
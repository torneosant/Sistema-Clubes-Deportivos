<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use App\Models\AsignacionInventario;
use Illuminate\Http\Request;
use App\Models\Entrenador;
use App\Models\MovimientoInventario;
use App\Exports\AsignacionInventarioExport;
use Maatwebsite\Excel\Facades\Excel;

class AsignacionInventarioController extends Controller
{
 public function index()
{
    $clubId = auth()->user()->club_id;

    $configuracion = \App\Models\Configuracion::find($clubId);

    $anio = session(
        'anio_trabajo',
        $configuracion?->anio ?? date('Y')
    );

    $asignaciones = AsignacionInventario::with([
            'inventario',
            'entrenador'
        ])
        ->whereHas('inventario', function ($query) use ($clubId) {
            $query->where('club_id', $clubId);
        })
        ->whereYear('fecha', $anio)
        ->orderByDesc('fecha')
        ->orderByDesc('id')
        ->get();

    return view(
        'inventario.asignaciones.index',
        compact('asignaciones')
    );
}

   public function create()
{
    $clubId = auth()->user()->club_id;

    $configuracion = \App\Models\Configuracion::find($clubId);

    $anio = session(
        'anio_trabajo',
        $configuracion?->anio ?? date('Y')
    );

    $articulos = Inventario::where('activo', 1)
        ->where('club_id', $clubId)
        ->orderBy('nombre')
        ->get();

    $entrenadores = Entrenador::where('club_id', $clubId)
        ->orderBy('nombres')
        ->get();

    $inventarioSeleccionado = request('inventario');

    return view('inventario.asignaciones.form', [
        'articulos' => $articulos,
        'entrenadores' => $entrenadores,
        'inventarioSeleccionado' => $inventarioSeleccionado,
        'anio' => $anio,
        'modo' => 'crear'
    ]);
}
    public function edit(
        AsignacionInventario $asignaciones_inventario
    ) {
        $clubId = auth()->user()->club_id;

        /*
        |----------------------------------------------------------------------
        | La asignación debe pertenecer al club actual
        |----------------------------------------------------------------------
        */

        if (
            !$asignaciones_inventario->inventario ||
            $asignaciones_inventario->inventario->club_id != $clubId
        ) {
            abort(
                403,
                'No tiene permiso para editar esta asignación.'
            );
        }

        $articulos = Inventario::where('activo', 1)
            ->where('club_id', $clubId)
            ->orderBy('nombre')
            ->get();

        $clubId = auth()->user()->club_id;

$entrenadores = Entrenador::where('club_id', $clubId)
    ->orderBy('nombres')
    ->get();

        return view('inventario.asignaciones.form', [
            'articulos' => $articulos,
            'entrenadores' => $entrenadores,
            'asignacion' => $asignaciones_inventario,
            'modo' => 'editar'
        ]);
    }


    public function update(
        Request $request,
        AsignacionInventario $asignaciones_inventario
    ) {
        $clubId = auth()->user()->club_id;

        /*
        |----------------------------------------------------------------------
        | Seguridad
        |----------------------------------------------------------------------
        */

        if (
            !$asignaciones_inventario->inventario ||
            $asignaciones_inventario->inventario->club_id != $clubId
        ) {
            abort(
                403,
                'No tiene permiso para modificar esta asignación.'
            );
        }

        $request->validate([
            'inventario_id' => 'required|exists:inventarios,id',
            'tipo_destino' => 'required',
            'cantidad' => 'required|integer|min:1',
            'fecha' => 'required|date',
        ]);

        /*
        |----------------------------------------------------------------------
        | El nuevo artículo también debe pertenecer al club
        |----------------------------------------------------------------------
        */

        $inventario = Inventario::where('id', $request->inventario_id)
            ->where('club_id', $clubId)
            ->first();

        if (!$inventario) {
            abort(
                403,
                'No tiene permiso para utilizar este artículo.'
            );
        }

        $asignaciones_inventario->update([

            'inventario_id' => $request->inventario_id,

            'tipo_destino' => $request->tipo_destino,

            'entrenador_id' => $request->tipo_destino == 'Entrenador'
                ? $request->entrenador_id
                : null,

            'destino_otro' => $request->tipo_destino == 'Otro'
                ? $request->destino_otro
                : null,

            'cantidad' => $request->cantidad,

            'fecha' => $request->fecha,

            'observaciones' => $request->observaciones,

        ]);

        return redirect()
            ->route('asignaciones-inventario.index')
            ->with(
                'success',
                'Asignación actualizada correctamente.'
            );
    }


    public function store(Request $request)
    {
        $clubId = auth()->user()->club_id;

        $request->validate([
            'inventario_id' => 'required|exists:inventarios,id',
            'tipo_destino' => 'required',
            'cantidad' => 'required|integer|min:1',
            'fecha' => 'required|date',
        ]);

        /*
        |----------------------------------------------------------------------
        | Buscar artículo únicamente dentro del club actual
        |----------------------------------------------------------------------
        */

        $inventario = Inventario::where('id', $request->inventario_id)
            ->where('club_id', $clubId)
            ->first();

        if (!$inventario) {
            abort(
                403,
                'No tiene permiso para utilizar este artículo.'
            );
        }

        if ($request->cantidad > $inventario->disponible) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'No hay suficientes unidades disponibles.'
                );
        }


        $asignacion = AsignacionInventario::create([

            'inventario_id' => $inventario->id,

            'tipo_destino' => $request->tipo_destino,

            'entrenador_id' => $request->tipo_destino == 'Entrenador'
                ? $request->entrenador_id
                : null,

            'destino_otro' => $request->tipo_destino == 'Otro'
                ? $request->destino_otro
                : null,

            'cantidad' => $request->cantidad,

            'fecha' => $request->fecha,

            'observaciones' => $request->observaciones,

        ]);


        MovimientoInventario::create([

            'inventario_id' => $asignacion->inventario_id,

            'asignacion_id' => $asignacion->id,

            'tipo' => 'Entrega',

            'cantidad' => $asignacion->cantidad,

            'fecha' => $asignacion->fecha,

            'responsable' => $asignacion->tipo_destino == 'Entrenador'
                ? $asignacion->entrenador?->nombres . ' ' .
                  $asignacion->entrenador?->apellidos
                : $asignacion->tipo_destino,

            'observaciones' => $asignacion->observaciones,

        ]);


        return redirect()
            ->route('asignaciones-inventario.index')
            ->with(
                'success',
                'Asignación registrada correctamente.'
            );
    }


    public function devolver(
        Request $request,
        AsignacionInventario $asignaciones_inventario
    ) {
        $clubId = auth()->user()->club_id;

        /*
        |----------------------------------------------------------------------
        | Seguridad
        |----------------------------------------------------------------------
        */

        if (
            !$asignaciones_inventario->inventario ||
            $asignaciones_inventario->inventario->club_id != $clubId
        ) {
            abort(
                403,
                'No tiene permiso para modificar esta asignación.'
            );
        }

        $request->validate([
            'cantidad' => 'required|integer|min:1'
        ]);

        $pendiente =
            $asignaciones_inventario->cantidad
            - $asignaciones_inventario->cantidad_devuelta;

        if ($request->cantidad > $pendiente) {

            return back()->with(
                'error',
                'La cantidad supera lo pendiente por devolver.'
            );
        }

        $asignaciones_inventario->cantidad_devuelta +=
            $request->cantidad;

        if (
            $asignaciones_inventario->cantidad_devuelta
            >= $asignaciones_inventario->cantidad
        ) {
            $asignaciones_inventario->estado = 'Devuelta';
        }

        $asignaciones_inventario->save();


        MovimientoInventario::create([

            'inventario_id' =>
                $asignaciones_inventario->inventario_id,

            'asignacion_id' =>
                $asignaciones_inventario->id,

            'tipo' => 'Devolucion',

            'cantidad' => $request->cantidad,

            'fecha' => now()->format('Y-m-d'),

            'responsable' =>
                $asignaciones_inventario->tipo_destino == 'Entrenador'
                    ? $asignaciones_inventario->entrenador?->nombres . ' ' .
                      $asignaciones_inventario->entrenador?->apellidos
                    : $asignaciones_inventario->tipo_destino,

            'observaciones' =>
                'Devolución de implementos',

        ]);


        return back()->with(
            'success',
            'Implementos devueltos correctamente.'
        );
    }


    public function destroy(
        AsignacionInventario $asignaciones_inventario
    ) {
        $clubId = auth()->user()->club_id;

        /*
        |----------------------------------------------------------------------
        | Seguridad
        |----------------------------------------------------------------------
        */

        if (
            !$asignaciones_inventario->inventario ||
            $asignaciones_inventario->inventario->club_id != $clubId
        ) {
            abort(
                403,
                'No tiene permiso para eliminar esta asignación.'
            );
        }

        /*
        |----------------------------------------------------------------------
        | Eliminar movimientos relacionados
        |----------------------------------------------------------------------
        */

        MovimientoInventario::where(
            'asignacion_id',
            $asignaciones_inventario->id
        )->delete();

        $asignaciones_inventario->delete();

        return redirect()
            ->route('asignaciones-inventario.index')
            ->with(
                'success',
                'Asignación eliminada correctamente.'
            );
    }


    public function excel()
    {
        return Excel::download(
            new AsignacionInventarioExport,
            'asignaciones_inventario.xlsx'
        );
    }
}
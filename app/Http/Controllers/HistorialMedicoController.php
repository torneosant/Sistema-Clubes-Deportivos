<?php

namespace App\Http\Controllers;

use App\Models\HistorialMedico;
use App\Models\Jugador;
use Illuminate\Http\Request;

class HistorialMedicoController extends Controller
{
    public function index(Request $request)
    {
        $clubId = auth()->user()->club_id;

        $jugador = null;

        $query = HistorialMedico::where('club_id', $clubId)
            ->with('jugador')
            ->orderByDesc('fecha')
            ->orderByDesc('created_at');

        /*
        |--------------------------------------------------------------------------
        | Filtrar por jugador
        |--------------------------------------------------------------------------
        */

        if ($request->filled('jugador')) {

            $jugador = Jugador::where('id', $request->jugador)
                ->where('club_id', $clubId)
                ->firstOrFail();

            $query->where('jugador_id', $jugador->id);
        }

        /*
        |--------------------------------------------------------------------------
        | Buscar por nombre y estado
        |--------------------------------------------------------------------------
        */

        if ($request->filled('buscar')) {

            $query->whereHas('jugador', function ($q) use ($request, $clubId) {

                $q->where('club_id', $clubId)
                    ->where(function ($q) use ($request) {

                        $q->where(
                            'nombres',
                            'like',
                            '%' . $request->buscar . '%'
                        )
                        ->orWhere(
                            'apellidos',
                            'like',
                            '%' . $request->buscar . '%'
                        );
                    });
            });
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $historial = $query->get();

        /*
        |--------------------------------------------------------------------------
        | Jugadoras del club para filtros
        |--------------------------------------------------------------------------
        */

        $jugadores = Jugador::where('club_id', $clubId)
            ->orderBy('nombres')
            ->orderBy('apellidos')
            ->get();

        return view('medico.index', compact(
            'historial',
            'jugador',
            'jugadores'
        ));
    }


    public function create(Request $request)
    {
        $clubId = auth()->user()->club_id;

        $jugador = null;

        /*
        | Si viene un jugador seleccionado,
        | verificamos que pertenezca al club.
        */

        if ($request->filled('jugador')) {

            $jugador = Jugador::where('id', $request->jugador)
                ->where('club_id', $clubId)
                ->firstOrFail();
        }

        /*
        | Solo mostrar jugadores del club actual.
        */

        $jugadores = Jugador::where('club_id', $clubId)
            ->orderBy('nombres')
            ->orderBy('apellidos')
            ->get();

        return view('medico.create', compact(
            'jugador',
            'jugadores'
        ));
    }


    public function store(Request $request)
    {
        $clubId = auth()->user()->club_id;

        $datos = $request->validate([

            'jugador_id' => 'required|exists:jugadores,id',

            'fecha' => 'required|date',

            'tipo' => 'required|string|max:100',

            'zona' => 'nullable|string|max:100',

            'diagnostico' => 'nullable|string',

            'tratamiento' => 'nullable|string',

            'estado' => 'nullable|string',

            'dias_incapacidad' => 'nullable|integer|min:0',

            'fecha_alta' => 'nullable|date',

            'observaciones' => 'nullable|string',

        ]);

        /*
        |--------------------------------------------------------------------------
        | Verificar que la jugadora pertenece al club
        |--------------------------------------------------------------------------
        */

        $jugador = Jugador::where('id', $datos['jugador_id'])
            ->where('club_id', $clubId)
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Asignar club automáticamente
        |--------------------------------------------------------------------------
        */

        $datos['club_id'] = $clubId;

        HistorialMedico::create($datos);

        return redirect()
            ->route('historial-medico.index')
            ->with(
                'success',
                'Registro médico guardado correctamente.'
            );
    }


    public function edit(HistorialMedico $historial)
    {
        $clubId = auth()->user()->club_id;

        /*
        |--------------------------------------------------------------------------
        | Impedir editar registros de otro club
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $historial->club_id == $clubId,
            404
        );

        /*
        | Solo jugadores del club actual.
        */

        $jugadores = Jugador::where('club_id', $clubId)
            ->orderBy('nombres')
            ->orderBy('apellidos')
            ->get();

        return view('medico.edit', [
            'historialMedico' => $historial,
            'jugadores' => $jugadores,
        ]);
    }


    public function update(
        Request $request,
        HistorialMedico $historial
    ) {

        $clubId = auth()->user()->club_id;

        /*
        |--------------------------------------------------------------------------
        | Seguridad: el registro debe pertenecer al club
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $historial->club_id == $clubId,
            404
        );

        $datos = $request->validate([

            'jugador_id' => 'required|exists:jugadores,id',

            'fecha' => 'required|date',

            'tipo' => 'required|max:100',

            'zona' => 'nullable|max:100',

            'diagnostico' => 'nullable',

            'tratamiento' => 'nullable',

            'dias_incapacidad' => 'nullable|integer|min:0',

            'fecha_alta' => 'nullable|date',

            'estado' => 'required',

            'observaciones' => 'nullable',

        ]);

        /*
        |--------------------------------------------------------------------------
        | Verificar que el nuevo jugador también pertenece al club
        |--------------------------------------------------------------------------
        */

        Jugador::where('id', $datos['jugador_id'])
            ->where('club_id', $clubId)
            ->firstOrFail();

        /*
        | Nunca permitir cambiar el club desde el formulario.
        */

        $datos['club_id'] = $clubId;

        $historial->update($datos);

        return redirect()
            ->route('historial-medico.index')
            ->with(
                'success',
                'Registro actualizado correctamente.'
            );
    }


    public function destroy(HistorialMedico $historial)
    {
        $clubId = auth()->user()->club_id;

        /*
        |--------------------------------------------------------------------------
        | Seguridad: solo puede eliminar registros de su club
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $historial->club_id == $clubId,
            404
        );

        $historial->delete();

        return redirect()
            ->route('historial-medico.index')
            ->with(
                'success',
                'Registro eliminado.'
            );
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Inscripcion;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InscripcionController extends Controller
{
    /**
     * Lista las solicitudes de inscripción del club.
     */
    public function index(Request $request)
    {
        $clubId = auth()->user()->club_id;

        $query = Inscripcion::where('club_id', $clubId)
            ->with(['categoria', 'jugador'])
            ->orderByDesc('created_at');

        /*
        |--------------------------------------------------------------------------
        | Filtro por estado
        |--------------------------------------------------------------------------
        */

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        /*
        |--------------------------------------------------------------------------
        | Búsqueda
        |--------------------------------------------------------------------------
        */

        if ($request->filled('buscar')) {

            $buscar = $request->buscar;

            $query->where(function ($q) use ($buscar) {

                $q->where('nombres', 'like', "%{$buscar}%")
                    ->orWhere('apellidos', 'like', "%{$buscar}%")
                    ->orWhere('documento', 'like', "%{$buscar}%")
                    ->orWhere('telefono', 'like', "%{$buscar}%");
            });
        }

        $inscripciones = $query->get();

        /*
        |--------------------------------------------------------------------------
        | Contadores
        |--------------------------------------------------------------------------
        */

        $pendientes = Inscripcion::where('club_id', $clubId)
            ->where('estado', 'Pendiente')
            ->count();

        $aceptadas = Inscripcion::where('club_id', $clubId)
            ->where('estado', 'Aceptada')
            ->count();

        $denegadas = Inscripcion::where('club_id', $clubId)
            ->where('estado', 'Denegada')
            ->count();

        return view(
            'inscripciones.index',
            compact(
                'inscripciones',
                'pendientes',
                'aceptadas',
                'denegadas'
            )
        );
    }


    /**
     * Formulario para crear un enlace de inscripción.
     */
    public function create()
    {
        $clubId = auth()->user()->club_id;

        $categorias = Categoria::where('club_id', $clubId)
            ->where('activo', 1)
            ->orderBy('nombre')
            ->get();

        return view(
            'inscripciones.create',
            compact('categorias')
        );
    }


    /**
     * Crear un nuevo enlace de inscripción.
     */
    public function store(Request $request)
    {
        $clubId = auth()->user()->club_id;

        $datos = $request->validate([
            'categoria_id' => [
                'nullable',
                'exists:categorias,id'
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Verificar que la categoría pertenezca al club
        |--------------------------------------------------------------------------
        */

        if (!empty($datos['categoria_id'])) {

            $categoriaValida = Categoria::where('id', $datos['categoria_id'])
                ->where('club_id', $clubId)
                ->exists();

            if (!$categoriaValida) {
                abort(
                    403,
                    'La categoría no pertenece a este club.'
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Generar token único
        |--------------------------------------------------------------------------
        */

        $token = Str::random(40);

        while (Inscripcion::where('token', $token)->exists()) {
            $token = Str::random(40);
        }

        Inscripcion::create([
            'club_id' => $clubId,
            'categoria_id' => $datos['categoria_id'] ?? null,
            'token' => $token,
            'estado' => 'Pendiente',
        ]);

        return redirect()
            ->route('inscripciones.index')
            ->with(
                'success',
                'Enlace de inscripción generado correctamente.'
            );
    }


    /**
     * Mostrar una solicitud.
     */
    public function show(Inscripcion $inscripcion)
    {
        $clubId = auth()->user()->club_id;

        abort_unless(
            $inscripcion->club_id == $clubId,
            403
        );

        $inscripcion->load([
            'categoria',
            'jugador',
            'revisor'
        ]);

        return view(
            'inscripciones.show',
            compact('inscripcion')
        );
    }


    /**
     * Editar una solicitud.
     *
     * Por ahora no la utilizaremos.
     */
    public function edit(Inscripcion $inscripcion)
    {
        $clubId = auth()->user()->club_id;

        abort_unless(
            $inscripcion->club_id == $clubId,
            403
        );

        return view(
            'inscripciones.edit',
            compact('inscripcion')
        );
    }


    /**
     * Actualizar una solicitud.
     */
    public function update(
        Request $request,
        Inscripcion $inscripcion
    ) {
        $clubId = auth()->user()->club_id;

        abort_unless(
            $inscripcion->club_id == $clubId,
            403
        );

        $datos = $request->validate([
            'observaciones' => 'nullable|string',
        ]);

        $inscripcion->update($datos);

        return redirect()
            ->route(
                'inscripciones.show',
                $inscripcion
            )
            ->with(
                'success',
                'Solicitud actualizada correctamente.'
            );
    }


    /**
     * Eliminar una solicitud.
     */
    public function destroy(Inscripcion $inscripcion)
    {
        $clubId = auth()->user()->club_id;

        abort_unless(
            $inscripcion->club_id == $clubId,
            403
        );

        $inscripcion->delete();

        return redirect()
            ->route('inscripciones.index')
            ->with(
                'success',
                'Solicitud eliminada correctamente.'
            );
    }
}
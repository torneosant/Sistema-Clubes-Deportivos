<?php

namespace App\Http\Controllers;

use App\Models\Inscripcion;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Jugador;
use Illuminate\Support\Facades\DB;

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
    | Filtro por categoría
    |--------------------------------------------------------------------------
    */

    if ($request->filled('categoria')) {

        $query->where('categoria_id', $request->categoria);

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

    /*
    |--------------------------------------------------------------------------
    | Categorías del club
    |--------------------------------------------------------------------------
    */

    $categorias = \App\Models\Categoria::where('club_id', $clubId)
        ->where('activo', true)
        ->orderBy('nombre')
        ->get();

    return view(
        'inscripciones.index',
        compact(
            'inscripciones',
            'pendientes',
            'aceptadas',
            'denegadas',
            'categorias'
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
            'exists:categorias,id',
        ],
    ]);

    /*
    |--------------------------------------------------------------------------
    | Verificar categoría
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

    do {

        $token = Str::random(40);

    } while (
        Inscripcion::where('token', $token)->exists()
    );


    /*
    |--------------------------------------------------------------------------
    | Crear enlace
    |--------------------------------------------------------------------------
    */

    $inscripcion = Inscripcion::create([

        'club_id' => $clubId,

        'categoria_id' =>
            $datos['categoria_id'] ?? null,

        'token' => $token,

        'estado' => 'Disponible',

    ]);


    /*
    |--------------------------------------------------------------------------
    | Generar URL pública
    |--------------------------------------------------------------------------
    */

    $url = route(
        'inscripcion.publica',
        $inscripcion->token
    );


    return view(
        'inscripciones.enlace',
        compact(
            'inscripcion',
            'url'
        )
    );
}

    /**
     * Mostrar una solicitud.
     */
  public function show(Inscripcion $inscripcion)
{
    $clubId = auth()->user()->club_id;

    /*
    |--------------------------------------------------------------------------
    | Seguridad por club
    |--------------------------------------------------------------------------
    |
    | El administrador solamente puede consultar inscripciones
    | pertenecientes a su propio club.
    |
    */

    abort_unless(
        (int) $inscripcion->club_id === (int) $clubId,
        403,
        'No tiene permiso para consultar esta inscripción.'
    );

    /*
    |--------------------------------------------------------------------------
    | Cargar relaciones
    |--------------------------------------------------------------------------
    */

    $inscripcion->load([
        'categoria',
        'jugador',
        'revisor',
    ]);

    /*
    |--------------------------------------------------------------------------
    | Mostrar detalle
    |--------------------------------------------------------------------------
    */

    return view(
        'inscripciones.show',
        compact('inscripcion')
    );
}


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
   public function aceptar(Inscripcion $inscripcion)
{
    $clubId = auth()->user()->club_id;

    /*
    |--------------------------------------------------------------------------
    | Seguridad por club
    |--------------------------------------------------------------------------
    */

    abort_unless(
        (int) $inscripcion->club_id === (int) $clubId,
        403,
        'No tiene permiso para aprobar esta inscripción.'
    );

    /*
    |--------------------------------------------------------------------------
    | Verificar estado
    |--------------------------------------------------------------------------
    */

    if ($inscripcion->estado !== 'Pendiente') {

        return back()->with(
            'error',
            'Esta inscripción ya fue procesada.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Crear jugador y aprobar inscripción
    |--------------------------------------------------------------------------
    */

    DB::transaction(function () use ($inscripcion, $clubId) {

        /*
        |----------------------------------------------------------------------
        | Evitar crear el jugador dos veces
        |----------------------------------------------------------------------
        */

        if ($inscripcion->jugador_id) {

            throw new \RuntimeException(
                'Esta inscripción ya tiene un jugador asociado.'
            );
        }

        /*
        |----------------------------------------------------------------------
        | Buscar jugador existente por documento
        |----------------------------------------------------------------------
        */

        $jugador = null;

        if ($inscripcion->documento) {

            $jugador = Jugador::where(
                'club_id',
                $clubId
            )
            ->where(
                'numero_documento',
                $inscripcion->documento
            )
            ->first();
        }

        /*
        |----------------------------------------------------------------------
        | Si no existe, crear jugador
        |----------------------------------------------------------------------
        */

        if (!$jugador) {

            $jugador = Jugador::create([

                'club_id' => $clubId,

                'nombres' => $inscripcion->nombres,

                'apellidos' => $inscripcion->apellidos,

                'numero_documento' => $inscripcion->documento,

                'fecha_nacimiento' => $inscripcion->fecha_nacimiento,

                'telefono' => $inscripcion->telefono,

                'email' => $inscripcion->email,

                'direccion' => $inscripcion->direccion,

                'categoria_id' => $inscripcion->categoria_id,

                'posicion' => $inscripcion->posicion,

                'activo' => true,

                'estado' => 'Activo',

            ]);
        }

        /*
        |----------------------------------------------------------------------
        | Actualizar inscripción
        |----------------------------------------------------------------------
        */

        $inscripcion->update([

            'estado' => 'Aceptada',

            'jugador_id' => $jugador->id,

            'fecha_revision' => now(),

            'revisado_por' => auth()->id(),

        ]);
    });

    return redirect()
        ->route(
            'inscripciones.show',
            $inscripcion
        )
        ->with(
            'success',
            'Inscripción aceptada. El jugador fue creado correctamente.'
        );
}

public function denegar(Request $request, Inscripcion $inscripcion)
{
    $clubId = auth()->user()->club_id;

    abort_unless(
        $inscripcion->club_id == $clubId,
        403
    );

    if ($inscripcion->estado !== 'Pendiente') {
        return back()->with(
            'error',
            'Esta inscripción ya fue procesada.'
        );
    }

    $datos = $request->validate([
        'motivo_denegacion' => 'nullable|string|max:1000',
    ]);

    $inscripcion->update([

        'estado' => 'Denegada',

        'motivo_denegacion' =>
            $datos['motivo_denegacion'] ?? null,

        'fecha_revision' => now(),

        'revisado_por' => auth()->id(),

    ]);

    return redirect()
        ->route('inscripciones.show', $inscripcion)
        ->with(
            'success',
            'La inscripción fue denegada.'
        );
}
}
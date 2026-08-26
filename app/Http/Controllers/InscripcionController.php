<?php

namespace App\Http\Controllers;

use App\Models\Inscripcion;
use App\Models\Categoria;
use App\Models\Jugador;
use App\Models\Documento;
use App\Models\TipoDocumento;
use App\Models\User;
use App\Models\Rol;
use App\Models\ConfiguracionInscripcion;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

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

        $categorias = Categoria::where('club_id', $clubId)
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
            ->where('activo', true)
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

        return view(
            'inscripciones.show',
            compact('inscripcion')
        );
    }


    /**
     * Editar una solicitud.
     */
    public function edit(Inscripcion $inscripcion)
    {
        $clubId = auth()->user()->club_id;

        abort_unless(
            (int) $inscripcion->club_id === (int) $clubId,
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
            (int) $inscripcion->club_id === (int) $clubId,
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
            (int) $inscripcion->club_id === (int) $clubId,
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


    /**
     * Aprobar una inscripción.
     */
  
public function aceptar(Inscripcion $inscripcion)
{
    $clubId = auth()->user()->club_id;

    /*
    |--------------------------------------------------------------------------
    | SEGURIDAD MULTI-CLUB
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
    | Variables para correo
    |--------------------------------------------------------------------------
    */

    $usuarioCreado = null;
    $passwordTemporal = null;


    /*
    |--------------------------------------------------------------------------
    | PROCESO PRINCIPAL
    |--------------------------------------------------------------------------
    */

    DB::transaction(function () use (
        $inscripcion,
        $clubId,
        &$usuarioCreado,
        &$passwordTemporal
    ) {

        /*
        |--------------------------------------------------------------------------
        | Evitar duplicar jugador
        |--------------------------------------------------------------------------
        */

        if ($inscripcion->jugador_id) {

            throw new \RuntimeException(
                'Esta inscripción ya tiene un jugador asociado.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Buscar jugador existente dentro del MISMO CLUB
        |--------------------------------------------------------------------------
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
        |--------------------------------------------------------------------------
        | Crear jugador
        |--------------------------------------------------------------------------
        */

        if (!$jugador) {

            $jugador = Jugador::create([

                'club_id' =>
                    $clubId,

                'nombres' =>
                    $inscripcion->nombres,

                'apellidos' =>
                    $inscripcion->apellidos,

                'numero_documento' =>
                    $inscripcion->documento,

                'fecha_nacimiento' =>
                    $inscripcion->fecha_nacimiento,

                'telefono' =>
                    $inscripcion->telefono,

                'email' =>
                    $inscripcion->email,

                'direccion' =>
                    $inscripcion->direccion,

                'categoria_id' =>
                    $inscripcion->categoria_id,

                'eps' =>
                    $inscripcion->eps,

                'tipo_sangre' =>
                    $inscripcion->tipo_sangre,

                'acudiente' =>
                    $inscripcion->acudiente,

                'documento_acudiente' =>
                    $inscripcion->documento_acudiente,

                'telefono_acudiente' =>
                    $inscripcion->telefono_acudiente,

                'email_acudiente' =>
                    $inscripcion->email_acudiente,

                'parentesco' =>
                    $inscripcion->parentesco,

                'foto' =>
                    $inscripcion->foto,

                'activo' =>
                    true,

                'estado' =>
                    'Activo',

            ]);

        } else {

            /*
            |--------------------------------------------------------------------------
            | Actualizar jugador existente
            |--------------------------------------------------------------------------
            */

            $jugador->update([

                'nombres' =>
                    $inscripcion->nombres,

                'apellidos' =>
                    $inscripcion->apellidos,

                'fecha_nacimiento' =>
                    $inscripcion->fecha_nacimiento,

                'telefono' =>
                    $inscripcion->telefono,

                'email' =>
                    $inscripcion->email,

                'direccion' =>
                    $inscripcion->direccion,

                'categoria_id' =>
                    $inscripcion->categoria_id,

                'eps' =>
                    $inscripcion->eps,

                'tipo_sangre' =>
                    $inscripcion->tipo_sangre,

                'acudiente' =>
                    $inscripcion->acudiente,

                'documento_acudiente' =>
                    $inscripcion->documento_acudiente,

                'telefono_acudiente' =>
                    $inscripcion->telefono_acudiente,

                'email_acudiente' =>
                    $inscripcion->email_acudiente,

                'parentesco' =>
                    $inscripcion->parentesco,

                'foto' =>
                    $inscripcion->foto,

            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | TIPOS DE DOCUMENTO DEL JUGADOR
        |--------------------------------------------------------------------------
        */

        $tipoFoto = TipoDocumento::where(
            'nombre',
            'Foto del jugador'
        )
            ->where('activo', true)
            ->first();


        $tipoIdentidad = TipoDocumento::where(
            'nombre',
            'Documento de identidad'
        )
            ->where('activo', true)
            ->first();


        if (!$tipoFoto) {

            throw new \RuntimeException(
                'No existe el tipo de documento "Foto del jugador".'
            );
        }


        if (!$tipoIdentidad) {

            throw new \RuntimeException(
                'No existe el tipo de documento "Documento de identidad".'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Crear documento: FOTO
        |--------------------------------------------------------------------------
        */

        if ($inscripcion->foto) {

            Documento::create([

                'jugador_id' =>
                    $jugador->id,

                'tipo_documento_id' =>
                    $tipoFoto->id,

                'titulo' =>
                    'Foto del jugador',

                'descripcion' =>
                    'Foto cargada durante el proceso de inscripción.',

                'archivo' =>
                    $inscripcion->foto,

                'fecha' =>
                    now()->toDateString(),

                'activo' =>
                    true,

            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Crear documento: IDENTIDAD
        |--------------------------------------------------------------------------
        */

        if ($inscripcion->documento_pdf) {

            Documento::create([

                'jugador_id' =>
                    $jugador->id,

                'tipo_documento_id' =>
                    $tipoIdentidad->id,

                'titulo' =>
                    'Documento de identidad',

                'descripcion' =>
                    'Documento de identidad cargado durante el proceso de inscripción.',

                'archivo' =>
                    $inscripcion->documento_pdf,

                'fecha' =>
                    now()->toDateString(),

                'activo' =>
                    true,

            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Buscar rol DEPORTISTA del club
        |--------------------------------------------------------------------------
        */

        $rolDeportista = Rol::where(
            'nombre',
            '[CLUB:' . $clubId . '] Deportista'
        )->first();


        if (!$rolDeportista) {

            throw new \RuntimeException(
                'No existe el rol Deportista para este club.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Verificar correo
        |--------------------------------------------------------------------------
        */

        if (!$inscripcion->email) {

            throw new \RuntimeException(
                'La inscripción no tiene un correo electrónico para crear el usuario.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Verificar si ya existe un usuario con ese correo
        |--------------------------------------------------------------------------
        */

        $usuarioExistente = User::where(
            'email',
            $inscripcion->email
        )->first();


        if ($usuarioExistente) {

            /*
            |--------------------------------------------------------------------------
            | Si ya existe y pertenece al mismo jugador,
            | reutilizamos el usuario.
            |--------------------------------------------------------------------------
            */

            if (
                (int) $usuarioExistente->jugador_id ===
                (int) $jugador->id
            ) {

                $usuarioCreado = $usuarioExistente;

            } else {

                throw new \RuntimeException(
                    'Ya existe un usuario registrado con el correo '
                    . $inscripcion->email
                    . '. No se puede crear otro usuario con el mismo correo.'
                );
            }

        } else {

            /*
            |--------------------------------------------------------------------------
            | Generar contraseña temporal
            |--------------------------------------------------------------------------
            */

            $passwordTemporal = Str::random(10);


            /*
            |--------------------------------------------------------------------------
            | Crear usuario
            |--------------------------------------------------------------------------
            */

            $usuarioCreado = User::create([

                'name' =>
                    trim(
                        $inscripcion->nombres
                        . ' '
                        . $inscripcion->apellidos
                    ),

                'email' =>
                    $inscripcion->email,

                'password' =>
                    $passwordTemporal,

                'rol_id' =>
                    $rolDeportista->id,

                'jugador_id' =>
                    $jugador->id,

                'club_id' =>
                    $clubId,

                'activo' =>
                    true,

            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Actualizar inscripción
        |--------------------------------------------------------------------------
        */

        $inscripcion->update([

            'estado' =>
                'Aceptada',

            'jugador_id' =>
                $jugador->id,

            'fecha_revision' =>
                now(),

            'revisado_por' =>
                auth()->id(),

        ]);
    });


    /*
    |--------------------------------------------------------------------------
    | CONFIGURACIÓN DE INSCRIPCIONES
    |--------------------------------------------------------------------------
    */

    $configuracion = ConfiguracionInscripcion::where(
        'club_id',
        $clubId
    )
        ->with('documentos')
        ->first();


    /*
    |--------------------------------------------------------------------------
    | Enviar correo
    |--------------------------------------------------------------------------
    */

    $correoEnviado = false;

    if (
        $configuracion &&
        $configuracion->enviar_correo &&
        $inscripcion->email
    ) {

        /*
        |--------------------------------------------------------------------------
        | Construir mensaje
        |--------------------------------------------------------------------------
        */

        $mensaje = $configuracion->mensaje_correo
            ?: 'Tu inscripción ha sido aprobada. Bienvenido al club.';


        $cuerpo = $mensaje;

        $cuerpo .= "\n\n";
        $cuerpo .= "Datos de acceso al sistema\n";
        $cuerpo .= "--------------------------------\n";
        $cuerpo .= "Usuario: " . $inscripcion->email . "\n";


        /*
        |--------------------------------------------------------------------------
        | Solo mostramos contraseña si acabamos de crear usuario
        |--------------------------------------------------------------------------
        */

        if ($passwordTemporal) {

            $cuerpo .=
                "Contraseña: "
                . $passwordTemporal
                . "\n";
        } else {

            $cuerpo .=
                "Tu usuario ya estaba registrado en el sistema.\n"
                . "Si no recuerdas tu contraseña, utiliza la opción "
                . "de recuperación de contraseña.\n";
        }


        $cuerpo .= "\n";
        $cuerpo .=
            "Puedes ingresar al sistema con estas credenciales.\n";


        /*
        |--------------------------------------------------------------------------
        | Enviar correo
        |--------------------------------------------------------------------------
        */

        try {

            Mail::raw(
                $cuerpo,
                function ($message) use (
                    $inscripcion,
                    $configuracion
                    
                ) {

                    $message
                        ->to($inscripcion->email)
                        ->subject(
                            $configuracion->asunto_correo
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | Adjuntar documentos seleccionados
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $configuracion->adjuntar_documentos
                    ) {

                        foreach (
                            $configuracion->documentos
                            as $documento
                        ) {

                            $ruta = Storage::disk('public')
                                ->path($documento->archivo);


                            if (file_exists($ruta)) {

                                $message->attach(
                                    $ruta,
                                    [
                                        'as' =>
                                            basename(
                                                $documento->archivo
                                            ),
                                        'mime' =>
                                            'application/pdf',
                                    ]
                                );
                            }
                        }
                    }
                }
            );

            $correoEnviado = true;

        } catch (\Throwable $e) {

            report($e);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Mensaje final
    |--------------------------------------------------------------------------
    */

    $mensajeFinal =
        'Inscripción aceptada. '
        . 'El jugador, sus documentos y su usuario fueron creados correctamente.';


    if ($correoEnviado) {

        $mensajeFinal .=
            ' Las credenciales y los documentos configurados fueron enviados al correo '
            . $inscripcion->email
            . '.';

    } elseif (
        $configuracion &&
        $configuracion->enviar_correo
    ) {

        $mensajeFinal .=
            ' La inscripción fue aprobada, pero no fue posible enviar el correo. '
            . 'Revisa la configuración de correo del sistema.';
    }


    return redirect()
        ->route(
            'inscripciones.show',
            $inscripcion
        )
        ->with(
            'success',
            $mensajeFinal
        );
}
    /**
     * Denegar una inscripción.
     */
    public function denegar(
        Request $request,
        Inscripcion $inscripcion
    ) {

        $clubId = auth()->user()->club_id;

        abort_unless(
            (int) $inscripcion->club_id === (int) $clubId,
            403,
            'No tiene permiso para denegar esta inscripción.'
        );


        if ($inscripcion->estado !== 'Pendiente') {

            return back()->with(
                'error',
                'Esta inscripción ya fue procesada.'
            );
        }


        $datos = $request->validate([
            'motivo_denegacion' =>
                'required|string|max:1000',
        ]);


        $inscripcion->update([

            'estado' =>
                'Denegada',

            'motivo_denegacion' =>
                $datos['motivo_denegacion'],

            'fecha_revision' =>
                now(),

            'revisado_por' =>
                auth()->id(),

        ]);


        return redirect()
            ->route(
                'inscripciones.show',
                $inscripcion
            )
            ->with(
                'success',
                'La inscripción fue denegada.'
            );
    }
}
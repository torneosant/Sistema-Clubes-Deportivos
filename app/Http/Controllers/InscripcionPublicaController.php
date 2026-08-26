<?php

namespace App\Http\Controllers;

use App\Models\Inscripcion;
use Illuminate\Http\Request;
use Carbon\Carbon;

class InscripcionPublicaController extends Controller
{
    /**
     * Mostrar formulario público de inscripción.
     */
    public function create($token)
    {
        $inscripcion = Inscripcion::where('token', $token)
            ->with('categoria')
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | El enlace debe estar disponible
        |--------------------------------------------------------------------------
        */

        if ($inscripcion->estado !== 'Disponible') {
            abort(
                403,
                'Este enlace de inscripción ya no está disponible.'
            );
        }

        return view(
            'inscripciones.publica',
            compact('inscripcion')
        );
    }


    /**
     * Recibir solicitud de inscripción.
     */
    public function store(Request $request, $token)
    {
        $inscripcion = Inscripcion::where('token', $token)
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Verificar que el enlace siga disponible
        |--------------------------------------------------------------------------
        */

        if ($inscripcion->estado !== 'Disponible') {
            abort(
                403,
                'Este enlace de inscripción ya no está disponible.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Validar información
        |--------------------------------------------------------------------------
        */

        $datos = $request->validate([

            /*
            |--------------------------------------------------------------------------
            | Datos del jugador
            |--------------------------------------------------------------------------
            */

            'nombres' =>
                'required|string|max:100',

            'apellidos' =>
                'required|string|max:100',

            'documento' =>
                'required|string|max:50',

            'fecha_nacimiento' =>
                'required|date',

            'telefono' =>
                'nullable|string|max:50',

            'email' =>
                'required|email|max:255',

            'direccion' =>
                'nullable|string|max:500',

            'club_anterior' =>
                'nullable|string|max:255',

            'observaciones' =>
                'nullable|string|max:2000',


            /*
            |--------------------------------------------------------------------------
            | Información médica
            |--------------------------------------------------------------------------
            */

            'eps' =>
                'required|string|max:255',

            'tipo_sangre' =>
                'required|string|max:10',


            /*
            |--------------------------------------------------------------------------
            | Acudiente
            |--------------------------------------------------------------------------
            */

            'acudiente' =>
                'nullable|string|max:150',

            'documento_acudiente' =>
                'nullable|string|max:50',

            'telefono_acudiente' =>
                'nullable|string|max:50',

            'email_acudiente' =>
                'nullable|email|max:255',

            'parentesco' =>
                'nullable|string|max:100',


            /*
            |--------------------------------------------------------------------------
            | Archivos
            |--------------------------------------------------------------------------
            */

            'foto' =>
                'required|image|mimes:jpg,jpeg|max:3072',

            'documento_pdf' =>
                'required|file|mimes:pdf|max:5120',


            /*
            |--------------------------------------------------------------------------
            | Autorización
            |--------------------------------------------------------------------------
            */

            'autorizacion' =>
                'accepted',

        ], [

            /*
            |--------------------------------------------------------------------------
            | Mensajes jugador
            |--------------------------------------------------------------------------
            */

            'nombres.required' =>
                'Debe ingresar los nombres.',

            'apellidos.required' =>
                'Debe ingresar los apellidos.',

            'documento.required' =>
                'Debe ingresar el documento.',

            'fecha_nacimiento.required' =>
                'Debe ingresar la fecha de nacimiento.',

            'email.required' =>
                'Debe ingresar el correo que utilizará para acceder al sistema.',

            'email.email' =>
                'El correo electrónico no es válido.',


            /*
            |--------------------------------------------------------------------------
            | Mensajes médicos
            |--------------------------------------------------------------------------
            */

            'eps.required' =>
                'Debe ingresar la EPS.',

            'tipo_sangre.required' =>
                'Debe seleccionar el tipo de sangre / RH.',


            /*
            |--------------------------------------------------------------------------
            | Mensajes archivos
            |--------------------------------------------------------------------------
            */

            'foto.required' =>
                'Debe adjuntar una foto del jugador.',

            'foto.image' =>
                'El archivo de foto no es válido.',

            'foto.mimes' =>
                'La foto debe estar en formato JPG o JPEG.',

            'foto.max' =>
                'La foto no puede superar los 3 MB.',

            'documento_pdf.required' =>
                'Debe adjuntar el documento del jugador.',

            'documento_pdf.mimes' =>
                'El documento debe estar en formato PDF.',

            'documento_pdf.max' =>
                'El documento PDF no puede superar los 5 MB.',


            /*
            |--------------------------------------------------------------------------
            | Autorización
            |--------------------------------------------------------------------------
            */

            'autorizacion.accepted' =>
                'Debe aceptar la autorización para continuar.',

        ]);


        /*
        |--------------------------------------------------------------------------
        | Determinar edad
        |--------------------------------------------------------------------------
        */

        $fechaNacimiento = Carbon::parse(
            $datos['fecha_nacimiento']
        );

        $edad = $fechaNacimiento->age;

        $esMenor = $edad < 18;


        /*
        |--------------------------------------------------------------------------
        | Validar acudiente según edad
        |--------------------------------------------------------------------------
        */

        if ($esMenor) {

            if (empty($datos['acudiente'])) {

                return back()
                    ->withErrors([
                        'acudiente' =>
                            'El nombre del acudiente es obligatorio para jugadores menores de edad.'
                    ])
                    ->withInput();

            }


            if (empty($datos['documento_acudiente'])) {

                return back()
                    ->withErrors([
                        'documento_acudiente' =>
                            'El documento del acudiente es obligatorio para jugadores menores de edad.'
                    ])
                    ->withInput();

            }


            if (empty($datos['telefono_acudiente'])) {

                return back()
                    ->withErrors([
                        'telefono_acudiente' =>
                            'El teléfono del acudiente es obligatorio para jugadores menores de edad.'
                    ])
                    ->withInput();

            }


            if (empty($datos['parentesco'])) {

                return back()
                    ->withErrors([
                        'parentesco' =>
                            'Debe indicar el parentesco del acudiente.'
                    ])
                    ->withInput();

            }

        } else {

            /*
            |--------------------------------------------------------------------------
            | Mayor de edad
            |--------------------------------------------------------------------------
            */

            $datos['acudiente'] = null;

            $datos['documento_acudiente'] = null;

            $datos['telefono_acudiente'] = null;

            $datos['email_acudiente'] = null;

            $datos['parentesco'] = null;
        }


        /*
        |--------------------------------------------------------------------------
        | Guardar foto
        |--------------------------------------------------------------------------
        */

        $rutaFoto = $request
            ->file('foto')
            ->store(
                'inscripciones/fotos',
                'public'
            );


        /*
        |--------------------------------------------------------------------------
        | Guardar documento PDF
        |--------------------------------------------------------------------------
        */

        $rutaDocumento = $request
            ->file('documento_pdf')
            ->store(
                'inscripciones/documentos',
                'public'
            );


        /*
        |--------------------------------------------------------------------------
        | Actualizar inscripción
        |--------------------------------------------------------------------------
        */

        $inscripcion->update([

            /*
            | Datos jugador
            */

            'nombres' =>
                $datos['nombres'],

            'apellidos' =>
                $datos['apellidos'],

            'documento' =>
                $datos['documento'],

            'fecha_nacimiento' =>
                $datos['fecha_nacimiento'],

            'telefono' =>
                $datos['telefono'] ?? null,

            'email' =>
                $datos['email'],

            'direccion' =>
                $datos['direccion'] ?? null,

            'club_anterior' =>
                $datos['club_anterior'] ?? null,

            'observaciones' =>
                $datos['observaciones'] ?? null,


            /*
            |--------------------------------------------------------------------------
            | Información médica
            |--------------------------------------------------------------------------
            */

            'eps' =>
                $datos['eps'],

            'tipo_sangre' =>
                $datos['tipo_sangre'],


            /*
            |--------------------------------------------------------------------------
            | Acudiente
            |--------------------------------------------------------------------------
            */

            'acudiente' =>
                $datos['acudiente'] ?? null,

            'documento_acudiente' =>
                $datos['documento_acudiente'] ?? null,

            'telefono_acudiente' =>
                $datos['telefono_acudiente'] ?? null,

            'email_acudiente' =>
                $datos['email_acudiente'] ?? null,

            'parentesco' =>
                $datos['parentesco'] ?? null,


            /*
            |--------------------------------------------------------------------------
            | Archivos
            |--------------------------------------------------------------------------
            */

            'foto' =>
                $rutaFoto,

            'documento_pdf' =>
                $rutaDocumento,


            /*
            |--------------------------------------------------------------------------
            | Estado
            |--------------------------------------------------------------------------
            */

            'estado' =>
                'Pendiente',

        ]);


        /*
        |--------------------------------------------------------------------------
        | Confirmación
        |--------------------------------------------------------------------------
        */

        return view(
            'inscripciones.confirmacion',
            compact('inscripcion')
        );
    }
}
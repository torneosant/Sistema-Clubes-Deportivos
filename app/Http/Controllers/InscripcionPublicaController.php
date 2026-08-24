<?php

namespace App\Http\Controllers;

use App\Models\Inscripcion;
use Illuminate\Http\Request;

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

            'nombres' => 'required|string|max:100',

            'apellidos' => 'required|string|max:100',

            'documento' => 'nullable|string|max:50',

            'fecha_nacimiento' => 'nullable|date',

            'telefono' => 'nullable|string|max:50',

            'email' => 'nullable|email|max:255',

            'direccion' => 'nullable|string',

            'posicion' => 'nullable|string|max:100',

            'club_anterior' => 'nullable|string|max:255',

            'observaciones' => 'nullable|string',

        ], [

            'nombres.required' => 'Debe ingresar los nombres.',

            'apellidos.required' => 'Debe ingresar los apellidos.',

            'email.email' => 'El correo electrónico no es válido.',

        ]);


        /*
        |--------------------------------------------------------------------------
        | Actualizar inscripción
        |--------------------------------------------------------------------------
        */

        $inscripcion->update([

            'nombres' => $datos['nombres'],

            'apellidos' => $datos['apellidos'],

            'documento' => $datos['documento'] ?? null,

            'fecha_nacimiento' =>
                $datos['fecha_nacimiento'] ?? null,

            'telefono' =>
                $datos['telefono'] ?? null,

            'email' =>
                $datos['email'] ?? null,

            'direccion' =>
                $datos['direccion'] ?? null,

            'posicion' =>
                $datos['posicion'] ?? null,

            'club_anterior' =>
                $datos['club_anterior'] ?? null,

            'observaciones' =>
                $datos['observaciones'] ?? null,

            'estado' => 'Pendiente',

        ]);


        return view(
            'inscripciones.confirmacion',
            compact('inscripcion')
        );
    }
}
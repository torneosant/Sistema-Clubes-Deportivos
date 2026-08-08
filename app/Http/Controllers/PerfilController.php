<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class PerfilController extends Controller
{
    /**
     * Mostrar perfil del usuario.
     */
    public function index()
    {
        $usuario = auth()->user();

        return view('perfil.index', compact('usuario'));
    }

    /**
     * Mostrar formulario para cambiar contraseña.
     */
    public function password()
    {
        return view('perfil.password');
    }

    /**
     * Actualizar contraseña.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_actual' => 'required|string',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ], [
            'password_actual.required' => 'Debes ingresar tu contraseña actual.',
            'password.required' => 'Debes ingresar una nueva contraseña.',
            'password.min' => 'La nueva contraseña debe tener mínimo 8 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
        ]);

        $usuario = auth()->user();

        // Verificar contraseña actual
        if (!Hash::check($request->password_actual, $usuario->password)) {
            throw ValidationException::withMessages([
                'password_actual' => 'La contraseña actual no es correcta.',
            ]);
        }

        // Guardar nueva contraseña
        $usuario->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()
            ->route('perfil')
            ->with('success', 'Contraseña cambiada correctamente.');
    }
}
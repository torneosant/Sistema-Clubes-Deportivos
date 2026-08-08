<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\Rol;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RegistroClubController extends Controller
{
    /**
     * Mostrar formulario público de registro.
     */
    public function create()
    {
        return view('auth.registro-club');
    }

    /**
     * Registrar club y usuario administrador.
     */
    public function store(Request $request)
    {
        $request->validate([
            // Club
            'nombre_club' => 'required|string|max:255',
            'email_club' => 'nullable|email|max:255',
            'telefono' => 'nullable|string|max:30',
            'ciudad' => 'nullable|string|max:100',
            'departamento' => 'nullable|string|max:100',
            'direccion' => 'nullable|string|max:255',

            // Administrador
            'nombre_admin' => 'required|string|max:255',
            'email_admin' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        DB::transaction(function () use ($request, &$usuario) {

            // Crear club
            $club = Club::create([
                'nombre' => $request->nombre_club,
                'slug' => Str::slug($request->nombre_club) . '-' . Str::lower(Str::random(6)),
                'email' => $request->email_club,
                'telefono' => $request->telefono,
                'ciudad' => $request->ciudad,
                'departamento' => $request->departamento,
                'pais' => 'Colombia',
                'direccion' => $request->direccion,
                'activo' => true,
            ]);

            // Buscar rol Administrador
            $rolAdministrador = Rol::where('nombre', 'Administrador')->first();

            if (!$rolAdministrador) {
                abort(500, 'No existe el rol Administrador.');
            }

            // Crear usuario administrador
            $usuario = User::create([
                'name' => $request->nombre_admin,
                'email' => $request->email_admin,
                'password' => $request->password,
                'rol_id' => $rolAdministrador->id,
                'club_id' => $club->id,
                'activo' => true,
            ]);
        });

        // Iniciar sesión automáticamente
        Auth::login($usuario);

        $request->session()->regenerate();

        return redirect()
            ->route('dashboard')
            ->with(
                'success',
                '¡Club registrado correctamente! Bienvenido a Gestión de Clubes.'
            );
    }
}
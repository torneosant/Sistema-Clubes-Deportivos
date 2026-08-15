<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Rol;
use App\Models\Jugador;

class UsuarioController extends Controller
{
    /**
     * Usuarios del club actual
     */
    public function index()
    {
        $clubId = auth()->user()->club_id;

        $usuarios = User::with('rol')
            ->where('club_id', $clubId)
            ->orderBy('name')
            ->get();

        return view(
            'configuracion.usuarios.index',
            compact('usuarios')
        );
    }


    /**
     * Formulario crear usuario
     */
    public function create()
    {
        $clubId = auth()->user()->club_id;

        $roles = Rol::all();

        $jugadores = Jugador::where('club_id', $clubId)
            ->whereDoesntHave('user')
            ->orderBy('nombres')
            ->get();

        return view(
            'configuracion.usuarios.create',
            compact('roles', 'jugadores')
        );
    }


    /**
     * Crear usuario
     */
    public function store(Request $request)
    {
        $clubId = auth()->user()->club_id;

        $request->validate([
            'name' => 'required|string|max:255|unique:users,name',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:8',
            'rol_id' => 'required|exists:roles,id',
            'jugador_id' => 'nullable|exists:jugadores,id',
        ], [
            'name.required' => 'Debe escribir el nombre del usuario.',
            'name.unique' => 'Ya existe un usuario con ese nombre.',

            'email.required' => 'Debe escribir un correo electrónico.',
            'email.email' => 'El correo no es válido.',
            'email.unique' => 'Ese correo ya está registrado.',

            'password.required' => 'Debe escribir una contraseña.',
            'password.min' => 'La contraseña debe tener mínimo 8 caracteres.',

            'rol_id.required' => 'Debe seleccionar un rol.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Verificar que el jugador pertenezca al club
        |--------------------------------------------------------------------------
        */

        if ($request->filled('jugador_id')) {

            $jugador = Jugador::where('id', $request->jugador_id)
                ->where('club_id', $clubId)
                ->first();

            if (!$jugador) {
                abort(
                    403,
                    'El jugador no pertenece al club actual.'
                );
            }
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'rol_id' => $request->rol_id,
            'jugador_id' => $request->jugador_id,
            'club_id' => $clubId,
            'activo' => 1,
        ]);

        return redirect()
            ->route('usuarios.index')
            ->with(
                'success',
                'Usuario creado correctamente.'
            );
    }


    /**
     * Mostrar usuario
     */
    public function show(string $id)
    {
        //
    }


    /**
     * Editar usuario
     */
    public function edit($id)
    {
        $clubId = auth()->user()->club_id;

        $usuario = User::where('id', $id)
            ->where('club_id', $clubId)
            ->firstOrFail();

        $roles = Rol::all();

        $jugadores = Jugador::where('club_id', $clubId)
            ->where(function ($query) use ($usuario) {

                $query->whereDoesntHave('user')
                      ->orWhere('id', $usuario->jugador_id);

            })
            ->orderBy('nombres')
            ->get();

        return view(
            'configuracion.usuarios.edit',
            compact(
                'usuario',
                'roles',
                'jugadores'
            )
        );
    }


    /**
     * Actualizar usuario
     */
    public function update(
        Request $request,
        $id
    ) {
        $clubId = auth()->user()->club_id;

        $usuario = User::where('id', $id)
            ->where('club_id', $clubId)
            ->firstOrFail();

        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email,' . $usuario->id,
            'rol_id' => 'required|exists:roles,id',
            'password' => 'nullable|min:8',
            'jugador_id' => 'nullable|exists:jugadores,id',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Verificar jugador
        |--------------------------------------------------------------------------
        */

        if ($request->filled('jugador_id')) {

            $jugador = Jugador::where('id', $request->jugador_id)
                ->where('club_id', $clubId)
                ->first();

            if (!$jugador) {
                abort(
                    403,
                    'El jugador no pertenece al club actual.'
                );
            }
        }

        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->rol_id = $request->rol_id;
        $usuario->jugador_id = $request->jugador_id;

        /*
        |--------------------------------------------------------------------------
        | Mantener el club
        |--------------------------------------------------------------------------
        */

        $usuario->club_id = $clubId;

        /*
        |--------------------------------------------------------------------------
        | Cambiar contraseña solamente si se escribió
        |--------------------------------------------------------------------------
        */

        if ($request->filled('password')) {
            $usuario->password = bcrypt(
                $request->password
            );
        }

        $usuario->save();

        return redirect()
            ->route('usuarios.index')
            ->with(
                'success',
                'Usuario actualizado correctamente.'
            );
    }


    /**
     * Eliminar usuario
     */
    public function destroy($id)
    {
        $clubId = auth()->user()->club_id;

        $usuario = User::where('id', $id)
            ->where('club_id', $clubId)
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | No eliminar usuario actual
        |--------------------------------------------------------------------------
        */

        if ($usuario->id == auth()->id()) {

            return back()->with(
                'error',
                'No puedes eliminar tu propio usuario.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | No eliminar Administradores
        |--------------------------------------------------------------------------
        */

        if (
            $usuario->rol &&
            $usuario->rol->nombre == 'Administrador'
        ) {

            return back()->with(
                'error',
                'No se puede eliminar un usuario Administrador.'
            );
        }

        $usuario->delete();

        return back()->with(
            'success',
            'Usuario eliminado correctamente.'
        );
    }


    /**
     * Cambiar estado
     */
    public function cambiarEstado(User $usuario)
    {
        $clubId = auth()->user()->club_id;

        /*
        |--------------------------------------------------------------------------
        | Seguridad: usuario del mismo club
        |--------------------------------------------------------------------------
        */

        if ($usuario->club_id != $clubId) {

            abort(
                403,
                'No tiene permiso para modificar este usuario.'
            );
        }

        $usuario->activo = !$usuario->activo;

        $usuario->save();

        return back()->with(
            'success',
            'Estado actualizado correctamente.'
        );
    }
}



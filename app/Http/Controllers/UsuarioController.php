<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Rol;
use App\Models\Jugador;
use Illuminate\Validation\Rule;


class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
        public function index()
    {
        $usuarios = User::with('rol')->get();

        return view('configuracion.usuarios.index', compact('usuarios'));
    }

    /**
     * Show the form for creating a new resource.
     */
 public function create()
{
    $roles = \App\Models\Rol::all();

    $jugadores = \App\Models\Jugador::whereDoesntHave('user')
    ->orderBy('nombres')
    ->get();

    return view(
        'configuracion.usuarios.create',
        compact('roles','jugadores')
    );
}

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:users,name',
        'email' => 'required|email|max:255|unique:users,email',
        'password' => 'required|min:8',
        'rol_id' => 'required|exists:roles,id',
        'jugador_id' => 'nullable|exists:jugadores,id',
    ],[
        'name.required' => 'Debe escribir el nombre del usuario.',
        'name.unique' => 'Ya existe un usuario con ese nombre.',

        'email.required' => 'Debe escribir un correo electrónico.',
        'email.email' => 'El correo no es válido.',
        'email.unique' => 'Ese correo ya está registrado.',

        'password.required' => 'Debe escribir una contraseña.',
        'password.min' => 'La contraseña debe tener mínimo 8 caracteres.',

        'rol_id.required' => 'Debe seleccionar un rol.',
    ]);

    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'rol_id' => $request->rol_id,
        'jugador_id' => $request->jugador_id,
        'activo' => 1,
    ]);

    return redirect()
        ->route('usuarios.index')
        ->with('success', 'Usuario creado correctamente.');
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
  public function edit($id)
{
    $usuario = User::findOrFail($id);

    $roles = Rol::all();

    $jugadores = Jugador::whereDoesntHave('user')
        ->orWhere('id', $usuario->jugador_id)
        ->orderBy('nombres')
        ->get();

    return view('configuracion.usuarios.edit', compact(
        'usuario',
        'roles',
        'jugadores'
    ));
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    $usuario = User::findOrFail($id);

    $request->validate([
        'name' => 'required|max:255',
        'email' => 'required|email|unique:users,email,' . $usuario->id,
        'rol_id' => 'required',
        'password' => 'nullable|min:8',
    ]);

    $usuario->name = $request->name;
    $usuario->email = $request->email;
    $usuario->rol_id = $request->rol_id;
    $usuario->jugador_id = $request->jugador_id;

    // Solo cambia la contraseña si escribieron una nueva
    if ($request->filled('password')) {
        $usuario->password = bcrypt($request->password);
    }

    $usuario->save();

    return redirect()
        ->route('usuarios.index')
        ->with('success', 'Usuario actualizado correctamente.');
}

    /**
     * Remove the specified resource from storage.
     */
   public function destroy($id)
{
    $usuario = User::findOrFail($id);

    // No permitir eliminar el usuario que está logueado
    if ($usuario->id == auth()->id()) {

        return back()->with(
            'error',
            'No puedes eliminar tu propio usuario.'
        );

    }

    // No permitir eliminar administradores
    if ($usuario->rol && $usuario->rol->nombre == 'Administrador') {

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

public function cambiarEstado(User $usuario)
{
    $usuario->activo = !$usuario->activo;

    $usuario->save();

    return back()->with(
        'success',
        'Estado actualizado correctamente.'
    );
}


    }



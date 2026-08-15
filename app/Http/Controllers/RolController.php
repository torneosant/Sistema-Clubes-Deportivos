<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Rol;
use App\Models\Permiso;
use App\Models\User;

class RolController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Roles base del sistema
    |--------------------------------------------------------------------------
    |
    | Estos roles ya existen y contienen los permisos originales.
    | Funcionan como plantillas.
    |
    */

    private const ROLES_BASE = [
        1 => 'Administrador',
        2 => 'Entrenador',
        3 => 'Médico',
        4 => 'Tesorero',
        5 => 'Secretaria',
        6 => 'Consulta',
        7 => 'Deportista',
    ];


    /*
    |--------------------------------------------------------------------------
    | Crear roles propios del club
    |--------------------------------------------------------------------------
    */

    private function prepararRolesClub($clubId)
    {
        if (!$clubId) {
            abort(
                403,
                'El usuario no tiene un club asignado.'
            );
        }

        DB::transaction(function () use ($clubId) {

            foreach (self::ROLES_BASE as $rolBaseId => $nombreBase) {

                $rolBase = Rol::find($rolBaseId);

                if (!$rolBase) {
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Nombre interno del rol
                |--------------------------------------------------------------------------
                */

                $nombreInterno = '[CLUB:' . $clubId . '] ' . $nombreBase;

                /*
                |--------------------------------------------------------------------------
                | Buscar si ya existe
                |--------------------------------------------------------------------------
                */

                $rolClub = Rol::where('nombre', $nombreInterno)
                    ->first();

                /*
                |--------------------------------------------------------------------------
                | Si no existe, crearlo copiando permisos
                |--------------------------------------------------------------------------
                */

                if (!$rolClub) {

                   $rolClub = Rol::create([
    'nombre' => $nombreInterno,
]);

                    /*
                    |--------------------------------------------------------------------------
                    | Copiar permisos del rol base
                    |--------------------------------------------------------------------------
                    */

                    $permisos = $rolBase->permisos()
                        ->pluck('permisos.id')
                        ->toArray();

                    $rolClub->permisos()->sync($permisos);
                }

                /*
                |--------------------------------------------------------------------------
                | Migrar usuarios de este club que todavía usan
                | el rol base.
                |--------------------------------------------------------------------------
                */

                User::where('club_id', $clubId)
                    ->where('rol_id', $rolBaseId)
                    ->update([
                        'rol_id' => $rolClub->id,
                    ]);
            }
        });
    }


    /*
    |--------------------------------------------------------------------------
    | Listado
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $clubId = auth()->user()->club_id;

        $this->prepararRolesClub($clubId);

        /*
        |--------------------------------------------------------------------------
        | Roles propios del club
        |--------------------------------------------------------------------------
        */

        $roles = Rol::with(['usuarios', 'permisos'])
            ->where('nombre', 'like', '[CLUB:' . $clubId . ']%')
            ->orderBy('nombre')
            ->get();

        return view(
            'configuracion.roles.index',
            compact('roles')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Crear
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $permisos = Permiso::where('activo', 1)
            ->orderBy('nombre')
            ->get();

        return view(
            'configuracion.roles.create',
            compact('permisos')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Guardar
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $clubId = auth()->user()->club_id;

        if (!$clubId) {
            abort(
                403,
                'El usuario no tiene un club asignado.'
            );
        }

        $request->validate([
            'nombre' => 'required|max:100',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Evitar que alguien intente crear un rol con el formato interno
        |--------------------------------------------------------------------------
        */

        $nombre = trim($request->nombre);

        if (str_starts_with($nombre, '[CLUB:')) {

            abort(
                403,
                'Nombre de rol no permitido.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Verificar duplicado dentro del club
        |--------------------------------------------------------------------------
        */

        $existe = Rol::where(
                'nombre',
                '[CLUB:' . $clubId . '] ' . $nombre
            )
            ->exists();

        if ($existe) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Ya existe un rol con ese nombre.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Crear rol
        |--------------------------------------------------------------------------
        */

        $rol = Rol::create([
            'nombre' => '[CLUB:' . $clubId . '] ' . $nombre,
            'activo' => 1,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Permisos
        |--------------------------------------------------------------------------
        */

        $rol->permisos()->sync(
            $request->permisos ?? []
        );

        return redirect()
            ->route('roles.index')
            ->with(
                'success',
                'Rol creado correctamente.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Editar
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $clubId = auth()->user()->club_id;

        $rol = Rol::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | No permitir editar roles del sistema
        |--------------------------------------------------------------------------
        */

        if (!$this->esRolDelClub($rol, $clubId)) {

            abort(
                403,
                'No tiene permiso para editar este rol.'
            );
        }

        $permisos = Permiso::where('activo', 1)
            ->orderBy('nombre')
            ->get();

        return view(
            'configuracion.roles.edit',
            compact('rol', 'permisos')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Actualizar
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, $id)
{
    $clubId = auth()->user()->club_id;

    $rol = Rol::findOrFail($id);

    /*
    |--------------------------------------------------------------------------
    | Seguridad: el rol debe pertenecer al club actual
    |--------------------------------------------------------------------------
    */

    if (!$this->esRolDelClub($rol, $clubId)) {

        abort(
            403,
            'No tiene permiso para modificar este rol.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Actualizar permisos
    |--------------------------------------------------------------------------
    */

    $rol->permisos()->sync(
        $request->input('permisos', [])
    );

    return redirect()
        ->route('roles.index')
        ->with(
            'success',
            'Permisos del rol actualizados correctamente.'
        );
}

    /*
    |--------------------------------------------------------------------------
    | Eliminar
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $clubId = auth()->user()->club_id;

        $rol = Rol::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Seguridad
        |--------------------------------------------------------------------------
        */

        if (!$this->esRolDelClub($rol, $clubId)) {

            abort(
                403,
                'No tiene permiso para eliminar este rol.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | No eliminar roles con usuarios
        |--------------------------------------------------------------------------
        */

        if ($rol->usuarios()->count() > 0) {

            return back()->with(
                'error',
                'No puedes eliminar un rol que tiene usuarios asociados.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Eliminar permisos relacionados
        |--------------------------------------------------------------------------
        */

        $rol->permisos()->detach();

        $rol->delete();

        return back()->with(
            'success',
            'Rol eliminado correctamente.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Comprobar pertenencia del rol al club
    |--------------------------------------------------------------------------
    */

    private function esRolDelClub(
        Rol $rol,
        $clubId
    ) {
        return str_starts_with(
            $rol->nombre,
            '[CLUB:' . $clubId . ']'
        );
    }
}

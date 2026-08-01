<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rol;
use App\Models\Permiso;

class RolController extends Controller
{
   public function index()
{
    $roles = Rol::with(['usuarios','permisos'])
                ->orderBy('id')
                ->get();

    return view('configuracion.roles.index', compact('roles'));
}
    public function create()
    {
        $permisos = Permiso::where('activo',1)
            ->orderBy('nombre')
            ->get();

        return view('configuracion.roles.create', compact('permisos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'=>'required|unique:roles,nombre'
        ]);

        $rol = Rol::create([
            'nombre'=>$request->nombre,
            'activo'=>1
        ]);

        if($request->has('permisos')){
            $rol->permisos()->sync($request->permisos);
        }

        return redirect()
            ->route('roles.index')
            ->with('success','Rol creado correctamente.');
    }

    public function edit($id)
    {
        $rol = Rol::findOrFail($id);

        $permisos = Permiso::where('activo',1)
            ->orderBy('nombre')
            ->get();

        return view(
            'configuracion.roles.edit',
            compact('rol','permisos')
        );
    }

    public function update(Request $request, $id)
    {
        $rol = Rol::findOrFail($id);

        $rol->permisos()->sync(
            $request->permisos ?? []
        );

        return redirect()
            ->route('roles.index')
            ->with('success','Rol actualizado correctamente.');
    }

    public function destroy($id)
    {
        $rol = Rol::findOrFail($id);

        if($rol->usuarios()->count()>0){

            return back()->with(
                'error',
                'No puedes eliminar un rol que tiene usuarios asociados.'
            );

        }

        $rol->delete();

        return back()->with(
            'success',
            'Rol eliminado correctamente.'
        );
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Club;
use Illuminate\Http\Request;

class ClubController extends Controller
{
    /**
     * Display a listing of the resource.
     */
  public function index()
{
    $club = Club::findOrFail(auth()->user()->club_id);

    return view('club.index', compact('club'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
  public function store(Request $request)
{
    $datos = $request->validate([
        'nombre' => 'required|max:255',
        'email' => 'nullable|email',
        'telefono' => 'nullable|max:30',
        'ciudad' => 'nullable|max:100',
        'departamento' => 'nullable|max:100',
        'direccion' => 'nullable|max:255',
        'descripcion' => 'nullable',
    ]);

    $datos['slug'] = \Illuminate\Support\Str::slug($datos['nombre']);
    $datos['pais'] = 'Colombia';

    Club::updateOrCreate(
        ['id' => 1],
        $datos
    );

    return back()->with('success', 'Información guardada correctamente.');

}

    /**
     * Display the specified resource.
     */
    public function show(Club $club)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Club $club)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Club $club)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Club $club)
    {
        //
    }
}

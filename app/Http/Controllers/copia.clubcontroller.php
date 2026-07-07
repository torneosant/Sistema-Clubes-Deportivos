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
    $club = Club::first();

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
    $request->validate([
        'nombre' => 'required|max:255',
    ]);

    Club::updateOrCreate(
        ['id' => 1],
        [
            'nombre' => $request->nombre,
            'slug' => \Illuminate\Support\Str::slug($request->nombre),
            'email' => $request->email,
            'telefono' => $request->telefono,
            'ciudad' => $request->ciudad,
            'departamento' => $request->departamento,
            'pais' => $request->pais ?? 'Colombia',     
            'direccion' => $request->direccion,
            'descripcion' => $request->descripcion,
        ]
    );

    return redirect()->back()->with('success', 'Club guardado correctamente.');
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

<?php

        namespace App\Http\Controllers;

        use App\Models\Jugador;
        use Illuminate\Http\Request;
        use Illuminate\Support\Facades\Storage; 
        use Illuminate\Validation\Rule;

        class JugadorController extends Controller
        {
     public function index(Request $request)
{
    $buscar = trim($request->get('buscar', ''));

    if ($buscar != '') {

        $jugadores = Jugador::where('nombres', 'like', "%{$buscar}%")
            ->orWhere('apellidos', 'like', "%{$buscar}%")
            ->orWhere('numero_documento', 'like', "%{$buscar}%")
            ->orWhere('telefono', 'like', "%{$buscar}%")
            ->orWhere('categoria', 'like', "%{$buscar}%")
            ->orWhere('equipo', 'like', "%{$buscar}%")
            ->orderBy('nombres')
            ->paginate(10)
            ->withQueryString();

    } else {

        $jugadores = Jugador::orderBy('nombres')
            ->paginate(10);

    }
          $totalJugadores = Jugador::count();

$totalActivos = Jugador::where('activo', true)->count();

$totalCategorias = Jugador::whereNotNull('categoria')
    ->distinct()
    ->count('categoria');
 return view('jugadores.index', compact(
    'jugadores',
    'buscar',
    'totalJugadores',
    'totalActivos',
    'totalCategorias'
));
}
            public function create()
            {
        
            return view('jugadores.create');
        }

            public function store(Request $request)
        {
            $datos = $request->validate([
                'nombres' => 'required|max:255',
                'apellidos' => 'required|max:255',
                'telefono' => 'nullable|max:30',
                'ciudad' => 'nullable|max:100',
                'categoria' => 'nullable|max:100',
                'equipo' => 'nullable|max:100',
                'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
                'fecha_nacimiento' => 'nullable|date',
                'email' => 'nullable|email|max:255',
                'direccion' => 'nullable|max:255',
                'estatura' => 'nullable|numeric',
                'peso' => 'nullable|numeric',
                'posicion' => 'nullable|max:100',
                'pierna_habil' => 'nullable|max:20',
                'eps' => 'nullable|max:150',
                'tipo_sangre' => 'nullable|max:10',
                'alergias' => 'nullable',
                'observaciones_medicas' => 'nullable',
                'acudiente' => 'nullable|max:255',
                'telefono_acudiente' => 'nullable|max:30',
                'parentesco' => 'nullable|max:100',
                'numero_documento' => 'nullable|unique:jugadors,numero_documento|max:50',
        
                ]);
                if ($request->hasFile('foto')) {

        $datos['foto'] = $request
            ->file('foto')
            ->store('jugadores', 'public');

    }

            $datos['club_id'] = 1;
            $datos['activo'] = true;
            
            Jugador::create($datos);
            

            return redirect()->route('jugadores.index')
                ->with('success', 'Jugador registrado correctamente.');
        }

            public function show(Jugador $jugador)
            {
                //
            }

        public function edit(Jugador $jugador)
        {
            return view('jugadores.edit', compact('jugador'));
        }

        public function update(Request $request, Jugador $jugador)
        {
            $datos = $request->validate([
                'nombres' => 'required|max:255',
                'apellidos' => 'required|max:255',
                'telefono' => 'nullable|max:30',
                'ciudad' => 'nullable|max:100',
                'categoria' => 'nullable|max:100',
                'equipo' => 'nullable|max:100',
                'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
                'fecha_nacimiento' => 'nullable|date',
                'email' => 'nullable|email|max:255',
                'direccion' => 'nullable|max:255',
                'estatura' => 'nullable|numeric',
                'peso' => 'nullable|numeric',
                'posicion' => 'nullable|max:100',
                'pierna_habil' => 'nullable|max:20',
                'eps' => 'nullable|max:150',
                'tipo_sangre' => 'nullable|max:10',
                'alergias' => 'nullable',
                'observaciones_medicas' => 'nullable',  
                'acudiente' => 'nullable|max:255',
                'telefono_acudiente' => 'nullable|max:30',
                'parentesco' => 'nullable|max:100',
                'numero_documento' => [
        'nullable',
        'max:50',
        Rule::unique('jugadors')->ignore($jugador->id),
    ],
            ]);
        if ($request->hasFile('foto')) {

        // Eliminar la foto anterior
        if ($jugador->foto && Storage::disk('public')->exists($jugador->foto)) {
            Storage::disk('public')->delete($jugador->foto);
        }

        // Guardar la nueva
        $datos['foto'] = $request
            ->file('foto')
            ->store('jugadores', 'public');
    }

            $jugador->update($datos);

            return redirect()->route('jugadores.index')
                ->with('success', 'Jugador actualizado correctamente.');
        }
        public function cambiarEstado(Jugador $jugador)
{
    $jugador->activo = !$jugador->activo;
    $jugador->save();

    return back()->with(
        'success',
        $jugador->activo
            ? 'Jugador activado correctamente.'
            : 'Jugador inactivado correctamente.'
    );
}

        public function destroy(Jugador $jugador)
        {
            $jugador->delete();

            return redirect()->route('jugadores.index')
                ->with('success', 'Jugador eliminado correctamente.');
        }
        }
<?php

        namespace App\Http\Controllers;

        use App\Models\Jugador;
        use Illuminate\Http\Request;
        use Illuminate\Support\Facades\Storage; 
        use Illuminate\Validation\Rule;
        use App\Models\Categoria;
        use App\Models\Equipo;
        use App\Exports\JugadoresExport;
        use Maatwebsite\Excel\Facades\Excel;
        use Barryvdh\DomPDF\Facade\Pdf;

        class JugadorController extends Controller
        {
        public function index(Request $request)
{
    $buscar = trim($request->get('buscar', ''));

    $categoria = $request->categoria;
    $estado = $request->estado;
    $equipo = $request->equipo;

    $jugadores = Jugador::with(['categoria', 'equipo'])

        ->when($buscar, function ($query) use ($buscar) {

            $query->where('nombres', 'like', "%{$buscar}%")
                  ->orWhere('apellidos', 'like', "%{$buscar}%")
                  ->orWhere('numero_documento', 'like', "%{$buscar}%")
                  ->orWhere('telefono', 'like', "%{$buscar}%");

        })

        ->when($categoria, function ($query) use ($categoria) {

            $query->where('categoria_id', $categoria);

        })

        ->when($estado !== null && $estado !== '', function ($query) use ($estado) {

            $query->where('activo', $estado);

        })
        ->when($equipo, function ($query) use ($equipo) {

    $query->where('equipo_id', $equipo);

})

        ->orderBy('nombres')
        ->paginate(10)
        ->withQueryString();

    $categorias = Categoria::where('activo', true)
        ->orderBy('nombre')
        ->get();
        $equipos = Equipo::where('activo', true)
    ->orderBy('nombre')
    ->get();

    $totalJugadores = Jugador::count();

    $totalActivos = Jugador::where('activo', true)->count();

    $totalCategorias = Categoria::count();

    return view('jugadores.index', compact(
    'jugadores',
    'buscar',
    'categoria',
    'equipo',
    'estado',
    'categorias',
    'equipos',
    'totalJugadores',
    'totalActivos',
    'totalCategorias'
));
}
  public function create()
{
    $categorias = Categoria::where('activo', true)
        ->orderBy('nombre')
        ->get();

    $equipos = Equipo::where('activo', true)
        ->orderBy('nombre')
        ->get();

    return view('jugadores.create', compact(
        'categorias',
        'equipos'
    ));
}
            public function store(Request $request)
        {
            $datos = $request->validate([
                'nombres' => 'required|string|max:255', 
                'apellidos' => 'required|string|max:255', 
                'telefono' => 'nullable|max:30',
                'ciudad' => 'nullable|max:100',
                'categoria_id' => 'nullable|exists:categorias,id',
                'equipo_id'    => 'nullable|exists:equipos,id',
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
                'numero_documento' => 'nullable|unique:jugadores,numero_documento|max:50',
        
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
    return view('jugadores.show', compact('jugador'));
}

 public function edit(Jugador $jugador)
{
    $categorias = Categoria::where('activo', true)
        ->orderBy('nombre')
        ->get();

    $equipos = Equipo::where('activo', true)
        ->orderBy('nombre')
        ->get();

    return view('jugadores.edit', compact(
        'jugador',
        'categorias',
        'equipos'
    ));
}

            public function update(Request $request, Jugador $jugador)
            {
                $datos = $request->validate([
                    'nombres' => 'required|string|max:255', 
                    'apellidos' => 'required|string|max:255', 
                    'telefono' => 'nullable|max:30',
                    'ciudad' => 'nullable|max:100',
                    'categoria_id' => 'nullable|exists:categorias,id',
                    'equipo_id' => 'nullable|exists:equipos,id',
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
                    Rule::unique('jugadores')->ignore($jugador->id),
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
        public function exportExcel()
{
    return Excel::download(
        new JugadoresExport,
        'jugadores.xlsx'
    );
}
public function print()
{
    $jugadores = Jugador::with(['categoria', 'equipo'])
        ->orderBy('nombres')
        ->get();

    return view('jugadores.print', compact('jugadores'));
}

public function pdf()
{
    $jugadores = Jugador::with(['categoria', 'equipo'])
        ->orderBy('nombres')
        ->get();

    $pdf = Pdf::loadView('jugadores.print', compact('jugadores'));

    return $pdf->download('Jugadores_'.date('Y-m-d').'.pdf');
}
        }
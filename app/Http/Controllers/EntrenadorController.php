<?php

        namespace App\Http\Controllers;

        use App\Models\Entrenador;
        use Illuminate\Http\Request;
        use Illuminate\Support\Facades\Storage; 
        use Illuminate\Validation\Rule;
        use App\Models\Categoria;
        use App\Models\Equipo;
        use App\Exports\JugadoresExport;
        use App\Exports\EntrenadoresExport;
        use Maatwebsite\Excel\Facades\Excel;
        use Barryvdh\DomPDF\Facade\Pdf;
        
        

        class EntrenadorController extends Controller
        {
        public function index(Request $request)
{
    $buscar = trim($request->get('buscar', ''));    
    $estado = $request->estado;


    $entrenadores = Entrenador::query()

        ->when($buscar, function ($query) use ($buscar) {

            $query->where('nombres', 'like', "%{$buscar}%")
                  ->orWhere('apellidos', 'like', "%{$buscar}%")
                  ->orWhere('numero_documento', 'like', "%{$buscar}%")
                  ->orWhere('telefono', 'like', "%{$buscar}%");

        })

        ->when($estado !== null && $estado !== '', function ($query) use ($estado) {

            $query->where('activo', $estado);

        })

        ->orderBy('nombres')
        ->paginate(10)
        ->withQueryString();

   
    $totalEntrenadores = Entrenador::count();

    $totalActivos = Entrenador::where('activo', true)->count();

    
    return view('entrenadores.index', compact(
    'entrenadores',
    'buscar',
    'estado',
    'totalEntrenadores',
    'totalActivos'
));
}
public function create()
{
    $equipos = Equipo::where('activo',1)
        ->orderBy('nombre')
        ->get();

    $categorias = Categoria::where('activo',1)
        ->orderBy('nombre')
        ->get();

    return view('entrenadores.create', compact(
        'equipos',
        'categorias'
    ));
}
public function store(Request $request)
{
    $datos = $request->validate(

    [
        'nombres'            => 'required|string|max:255',
        'apellidos'          => 'required|string|max:255',
        'numero_documento'   => 'nullable|max:50|unique:entrenadors,numero_documento',
        'fecha_nacimiento'   => 'nullable|date',
        'telefono'           => 'nullable|max:30',
        'email'              => 'nullable|email|max:255',
        'direccion'          => 'nullable|max:255',
        'ciudad'             => 'nullable|max:100',
        'cargo'              => 'nullable|max:150',
        'licencia'           => 'nullable|max:150',
        'fecha_ingreso'      => 'nullable|date',
        'observaciones'      => 'nullable',
        'foto'               => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
        'activo'             => 'nullable|boolean',
    ],

    [
        'nombres.required'          => 'Debe ingresar el nombre del entrenador.',
        'apellidos.required'        => 'Debe ingresar los apellidos del entrenador.',
        'numero_documento.unique'   => 'Ya existe un entrenador registrado con ese número de documento.',
        'email.email'               => 'El correo electrónico no es válido.',
        'foto.image'                => 'El archivo debe ser una imagen.',
        'foto.mimes'                => 'La foto debe ser JPG, JPEG o PNG.',
        'foto.max'                  => 'La foto no puede superar los 4 MB.',
    ]

    );

    if ($request->hasFile('foto')) {
        $datos['foto'] = $request->file('foto')->store('entrenadores', 'public');
    }

    $datos['club_id'] = 1;

    $entrenador = Entrenador::create($datos);

    if ($request->has('equipos')) {
        $entrenador->equipos()->sync($request->equipos);
    }

    return redirect()
        ->route('entrenadores.index')
        ->with('success', 'Entrenador registrado correctamente.');
}

     public function show(Entrenador $entrenador)
{
    return view('entrenadores.show', compact('entrenador'));
}

 public function edit(Entrenador $entrenador)
{
    $equipos = Equipo::where('activo',1)
        ->orderBy('nombre')
        ->get();

    $categorias = Categoria::where('activo',1)
        ->orderBy('nombre')
        ->get();

    return view('entrenadores.edit', compact(
        'entrenador',
        'equipos',
        'categorias'
    ));
}

            public function update(Request $request, Entrenador $Entrenador)
            {
                $datos = $request->validate([
    'nombres' => 'required|max:255',
    'apellidos' => 'required|max:255',
    'telefono' => 'nullable|max:30',
    'email' => 'nullable|email|max:255',
    'ciudad' => 'nullable|max:100',
    'direccion' => 'nullable|max:255',
    'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
]);            if ($request->hasFile('foto')) {

            // Eliminar la foto anterior
            if ($Entrenador->foto && Storage::disk('public')->exists($jugador->foto)) {
                Storage::disk('public')->delete($Entrenador->foto);
            }

            // Guardar la nueva
            $datos['foto'] = $request
                ->file('foto')
                ->store('entrenadores', 'public');
        }

           $Entrenador->update($datos);

$Entrenador->equipos()->sync(
    $request->equipos ?? []
);

return redirect()
    ->route('entrenadores.index')
    ->with('success', 'Entrenador actualizado correctamente.');        }
        public function cambiarEstado(Entrenador $Entrenador)
{
    $Entrenador->activo = !$Entrenador->activo;
    $Entrenador->save();

    return back()->with(
        'success',
        $Entrenador->activo
            ? 'Entrenador activado correctamente.'
            : 'Entrenador inactivado correctamente.'
    );
}

        public function destroy(Entrenador $Entrenador)
        {
            $Entrenador->delete();

            return redirect()->route('entrenadores.index')
                ->with('success', 'Entrenador eliminado correctamente.');
        }
        public function exportExcel()
{
    return Excel::download(
        new EntrenadoresExport,
        'entrenadores.xlsx'
    );
}
public function print()
{
    $entrenadores = Entrenador::orderBy('apellidos')
        ->orderBy('nombres')
        ->get();

    return view('entrenadores.print', compact('entrenadores'));
}

public function pdf()
{
    $entrenadores = Entrenador::orderBy('apellidos')
        ->orderBy('nombres')
        ->get();

    $pdf = Pdf::loadView(
        'entrenadores.print',
        compact('entrenadores')
    );

    return $pdf->download('entrenadores.pdf');
}
        }   
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
    $clubId = auth()->user()->club_id;

    $buscar = trim($request->get('buscar', ''));
    $estado = $request->estado;

    $entrenadores = Entrenador::where('club_id', $clubId)
        ->when($buscar, function ($query) use ($buscar) {

            $query->where(function ($q) use ($buscar) {

                $q->where('nombres', 'like', "%{$buscar}%")
                    ->orWhere('apellidos', 'like', "%{$buscar}%")
                    ->orWhere('numero_documento', 'like', "%{$buscar}%")
                    ->orWhere('telefono', 'like', "%{$buscar}%");

            });

        })
        ->when($estado !== null && $estado !== '', function ($query) use ($estado) {

            $query->where('activo', $estado);

        })
        ->orderBy('nombres')
        ->paginate(10)
        ->withQueryString();

    $totalEntrenadores = Entrenador::where('club_id', $clubId)
        ->count();

    $totalActivos = Entrenador::where('club_id', $clubId)
        ->where('activo', true)
        ->count();

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
    $clubId = auth()->user()->club_id;

    $equipos = Equipo::where('club_id', $clubId)
        ->where('activo', true)
        ->orderBy('nombre')
        ->get();

    $categorias = Categoria::where('club_id', $clubId)
        ->where('activo', true)
        ->orderBy('nombre')
        ->get();

    return view('entrenadores.create', compact(
        'equipos',
        'categorias'
    ));
}public function store(Request $request)
{
    $datos = $request->validate(

    [
        'nombres'            => 'required|string|max:255',
        'apellidos'          => 'required|string|max:255',
        'numero_documento' => [
    'nullable',
    'max:50',
    Rule::unique('entrenadors', 'numero_documento')
        ->where(fn ($query) => $query->where('club_id', auth()->user()->club_id)),
],
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

    $datos['club_id'] = auth()->user()->club_id;

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
    if ($entrenador->club_id != auth()->user()->club_id) {
        abort(403);
    }

    return view('entrenadores.show', compact('entrenador'));
}

 public function edit(Entrenador $entrenador)
{
    if ($entrenador->club_id != auth()->user()->club_id) {
        abort(403);
    }

    $clubId = auth()->user()->club_id;

    $equipos = Equipo::where('club_id', $clubId)
        ->where('activo', true)
        ->orderBy('nombre')
        ->get();

    $categorias = Categoria::where('club_id', $clubId)
        ->where('activo', true)
        ->orderBy('nombre')
        ->get();

    return view('entrenadores.edit', compact(
        'entrenador',
        'equipos',
        'categorias'
    ));
}

            public function update(Request $request, Entrenador $entrenador)
{
    if ($entrenador->club_id != auth()->user()->club_id) {
        abort(403);
    }

    $datos = $request->validate([
        'nombres' => 'required|max:255',
        'apellidos' => 'required|max:255',
        'numero_documento' => [
            'nullable',
            'max:50',
            Rule::unique('entrenadors', 'numero_documento')
                ->ignore($entrenador->id)
                ->where(fn ($query) => $query->where(
                    'club_id',
                    auth()->user()->club_id
                )),
        ],
        'telefono' => 'nullable|max:30',
        'email' => 'nullable|email|max:255',
        'ciudad' => 'nullable|max:100',
        'direccion' => 'nullable|max:255',
        'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
    ]);

    if ($request->hasFile('foto')) {

        if (
            $entrenador->foto &&
            Storage::disk('public')->exists($entrenador->foto)
        ) {
            Storage::disk('public')->delete($entrenador->foto);
        }

        $datos['foto'] = $request
            ->file('foto')
            ->store('entrenadores', 'public');
    }

    $entrenador->update($datos);

    // Solo equipos pertenecientes al club actual
    $clubId = auth()->user()->club_id;

    $equiposPermitidos = Equipo::where('club_id', $clubId)
        ->pluck('id')
        ->toArray();

    $equiposSeleccionados = collect($request->equipos ?? [])
        ->intersect($equiposPermitidos)
        ->values()
        ->toArray();

    $entrenador->equipos()->sync($equiposSeleccionados);

    return redirect()
        ->route('entrenadores.index')
        ->with('success', 'Entrenador actualizado correctamente.');
}        

public function destroy(Entrenador $entrenador)
{
    if ($entrenador->club_id != auth()->user()->club_id) {
        abort(403);
    }

    $entrenador->delete();

    return redirect()
        ->route('entrenadores.index')
        ->with('success', 'Entrenador eliminado correctamente.');
}


        public function exportExcel()
{
    $clubId = auth()->user()->club_id;

    return Excel::download(
        new EntrenadoresExport($clubId),
        'entrenadores.xlsx'
    );
}

public function pdf()
{
    $clubId = auth()->user()->club_id;

    $entrenadores = Entrenador::where('club_id', $clubId)
        ->orderBy('apellidos')
        ->orderBy('nombres')
        ->get();

    $pdf = Pdf::loadView(
        'entrenadores.print',
        compact('entrenadores')
    );

    return $pdf->download('entrenadores.pdf');
}
        }   
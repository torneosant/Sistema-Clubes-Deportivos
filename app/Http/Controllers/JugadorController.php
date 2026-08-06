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
        use App\Models\Contabilidad;
        use App\Models\PartidoJugador;
        use App\Imports\JugadoresImport;
        use App\Exports\JugadoresPlantillaExport;
        use App\Models\Club;
        use App\Models\Documento;
        use App\Models\TipoDocumento;

        class JugadorController extends Controller
        {
        public function index(Request $request)
{

        // Si es Deportista, mostrar únicamente su ficha
if (auth()->user()->rol->nombre == 'Deportista') {

    return redirect()->route(
        'jugadores.show',
        auth()->user()->jugador_id
    );

}

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

    $club = Club::first();

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
    'totalCategorias',
    'club',
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
                'nombres' => 'required|max:255',
                'apellidos' => 'required|max:255',
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

    // Si es Deportista solo puede ver su propia ficha
if (
    auth()->user()->rol->nombre === 'Deportista' &&
    auth()->user()->jugador_id != $jugador->id
) {
    abort(403, 'No tiene permiso para ver este jugador.');
}


    $jugador->load([
    'equipo',
    'categoria',
    'historialesMedicos',
    'asistencias.entrenamiento'
]);

    $movimientos = Contabilidad::with('concepto')
        ->where('jugador_id', $jugador->id)
        ->latest('fecha')
        ->get();

    $ingresos = $movimientos
        ->where('tipo', 'Ingreso')
        ->sum('valor');

    $gastos = $movimientos
        ->where('tipo', 'Egreso')
        ->sum('valor');

    $saldo = $ingresos - $gastos;

    $asistencias = $jugador->asistencias()
    ->with('entrenamiento')
    ->latest()
    ->get();

$presentes = $asistencias->where('estado', 'Presente')->count();

$ausentes = $asistencias->where('estado', 'Ausente')->count();

$permisos = $asistencias->where('estado', 'Permiso')->count();

$incapacidades = $asistencias->where('estado', 'Incapacidad')->count();

$totalAsistencias = $asistencias->count();

$porcentajeAsistencia = $totalAsistencias
    ? round(($presentes / $totalAsistencias) * 100)
    : 0;

    $estadisticas = PartidoJugador::where('jugador_id', $jugador->id);

$partidosJugados = $estadisticas->count();

$minutosJugados = $estadisticas->sum('minutos');

$goles = $estadisticas->sum('goles');

$asistenciasDeGol = $estadisticas->sum('asistencias');

$amarillas = $estadisticas->sum('amarillas');

$rojas = $estadisticas->sum('rojas');

$figuras = $estadisticas->where('figura',1)->count();

$detallePartidos = PartidoJugador::with('partido')
    ->where('jugador_id', $jugador->id)
    ->orderByDesc('partido_id')
    ->get();

$documentos = $jugador->documentos()
    ->with('tipoDocumento')
    ->latest()
    ->get();

$tipos = TipoDocumento::where('activo', true)
    ->orderBy('nombre')
    ->get();


    return view('jugadores.show', [
        'jugador'      => $jugador,
        'historiales'  => $jugador->historialesMedicos,
        'movimientos'  => $movimientos,
        'ingresos'     => $ingresos,
        'gastos'       => $gastos,
        'saldo'        => $saldo,
        'asistencias' => $asistencias,
'presentes' => $presentes,
'ausentes' => $ausentes,
'permisos' => $permisos,
'incapacidades' => $incapacidades,
'totalAsistencias' => $totalAsistencias,
'porcentajeAsistencia' => $porcentajeAsistencia,
'partidosJugados' => $partidosJugados,
'minutosJugados' => $minutosJugados,
'goles' => $goles,
'asistenciasDeGol' => $asistenciasDeGol,
'amarillas' => $amarillas,
'rojas' => $rojas,
'figuras' => $figuras,
'detallePartidos' => $detallePartidos,
'documentos' => $documentos,
'tipos' => $tipos,
    ]);
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
                    'nombres' => 'required|max:255',
                    'apellidos' => 'required|max:255',
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

public function importar(Request $request)
{
    $request->validate([
        'archivo' => 'required|mimes:xlsx,xls',
        'club_id' => 'required'
    ]);

    Excel::import(
        new JugadoresImport($request->club_id),
        $request->file('archivo')
    );

    return back()->with(
        'success',
        'Jugadores importados correctamente.'
    );
}
public function plantillaExcel()
{
    return Excel::download(
        new JugadoresPlantillaExport(),
        'Plantilla_Jugadores.xlsx'
    );
}


        }
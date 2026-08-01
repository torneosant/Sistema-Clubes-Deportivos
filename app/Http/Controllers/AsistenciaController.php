<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Entrenamiento;
use Illuminate\Http\Request;
use App\Models\Jugador;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AsistenciaExport;


class AsistenciaController extends Controller
{
 public function create(Entrenamiento $entrenamiento)
{
    $categorias = $entrenamiento->categorias->pluck('id');

    $jugadores = Jugador::where('equipo_id', $entrenamiento->equipo_id)
        ->whereIn('categoria_id', $categorias)
        ->where('activo', 1)
        ->orderBy('apellidos')
        ->orderBy('nombres')
        ->get();

    $asistencias = Asistencia::where('entrenamiento_id', $entrenamiento->id)
        ->get()
        ->keyBy('jugador_id');

        $totalJugadores = $jugadores->count();

$presentes = $asistencias->where('estado', 'Presente')->count();
$ausentes = $asistencias->where('estado', 'Ausente')->count();
$permisos = $asistencias->where('estado', 'Permiso')->count();
$incapacidades = $asistencias->where('estado', 'Incapacidad')->count();

$porcentaje = $totalJugadores > 0
    ? round(($presentes / $totalJugadores) * 100)
    : 0;
        

    return view('asistencias.create', compact(
    'entrenamiento',
    'jugadores',
    'asistencias',
    'totalJugadores',
    'presentes',
    'ausentes',
    'permisos',
    'incapacidades',
    'porcentaje'
));
}

   public function store(Request $request, Entrenamiento $entrenamiento)
{
    foreach ($request->estado as $jugadorId => $estado) {

    Asistencia::updateOrCreate(

        [
            'entrenamiento_id' => $entrenamiento->id,
            'jugador_id'       => $jugadorId,
        ],

        [
            'estado'       => $estado,
            'observacion'  => $request->observacion[$jugadorId] ?? null,
        ]

    );

}

    return redirect()
        ->route('entrenamientos.index')
        ->with('success', 'Asistencia guardada correctamente.');
    
}


public function pdf(Entrenamiento $entrenamiento)
{

    $categorias = $entrenamiento->categorias->pluck('id');

    $jugadores = Jugador::where('equipo_id',$entrenamiento->equipo_id)
        ->whereIn('categoria_id',$categorias)
        ->where('activo',1)
        ->orderBy('apellidos')
        ->orderBy('nombres')
        ->get();

    $asistencias = Asistencia::where(
        'entrenamiento_id',
        $entrenamiento->id
    )
    ->get()
    ->keyBy('jugador_id');

   $totalJugadores = $jugadores->count();

    $presentes = $asistencias->where('estado','Presente')->count();
    $ausentes = $asistencias->where('estado','Ausente')->count();
    $permisos = $asistencias->where('estado','Permiso')->count();
    $incapacidades = $asistencias->where('estado','Incapacidad')->count();

    $porcentaje = $totalJugadores
        ? round(($presentes/$totalJugadores)*100)
        : 0;

    $pdf = Pdf::loadView(
        'asistencias.pdf',
        compact(
            'entrenamiento',
            'jugadores',
            'asistencias',
            'totalJugadores',
            'presentes',
            'ausentes',
            'permisos',
            'incapacidades',
            'porcentaje'
        )
    );

    return $pdf->stream(
        'Asistencia_'.$entrenamiento->fecha.'.pdf'
    );

}


public function imprimir(Entrenamiento $entrenamiento)
{

}

public function excel(Entrenamiento $entrenamiento)
{
    return Excel::download(
        new AsistenciaExport($entrenamiento),
        'Asistencia_'.$entrenamiento->fecha.'.xlsx'
    );
}

}

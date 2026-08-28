<?php

namespace App\Http\Controllers;

use App\Models\Competencia;
use App\Models\Jugador;
use App\Models\Categoria;
use Illuminate\Http\Request;
use App\Exports\CompetenciaParticipantesExport;
use Maatwebsite\Excel\Facades\Excel;

class CompetenciaParticipanteController extends Controller
{
    /**
     * Mostrar la planilla de participantes.
     */
    public function index(Competencia $competencia)
    {
        $this->validarClub($competencia);

        $competencia->load([
            'categoria',
            'jugadores.categoria',
        ]);

        $clubId = auth()->user()->club_id;

        /*
        |--------------------------------------------------------------------------
        | Jugadores de la categoría principal
        |--------------------------------------------------------------------------
        */

        $jugadoresCategoria = collect();

        if ($competencia->categoria_id) {

            $jugadoresCategoria = Jugador::where('club_id', $clubId)
                ->where('categoria_id', $competencia->categoria_id)
                ->orderBy('apellidos')
                ->orderBy('nombres')
                ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | Categorías disponibles para refuerzos
        |--------------------------------------------------------------------------
        */

        $categorias = Categoria::where('club_id', $clubId)
            ->orderBy('nombre')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Todos los jugadores del club
        |--------------------------------------------------------------------------
        */

        $jugadoresClub = Jugador::where('club_id', $clubId)
    ->with('categoria')
    ->orderBy('apellidos')
    ->orderBy('nombres')
    ->get();

$jugadoresParaJavascript = $jugadoresClub->map(function ($jugador) {
    return [
        'id' => $jugador->id,
        'nombres' => $jugador->nombres,
        'apellidos' => $jugador->apellidos,
        'categoria_id' => $jugador->categoria_id,
        'categoria' => $jugador->categoria?->nombre,
    ];
})->values();
        /*
        |--------------------------------------------------------------------------
        | IDs ya inscritos
        |--------------------------------------------------------------------------
        */

        $participantesIds = $competencia->jugadores
            ->pluck('id')
            ->toArray();

        return view(
    'competencias.participantes.index',
    compact(
        'competencia',
        'jugadoresCategoria',
        'categorias',
        'jugadoresClub',
        'participantesIds',
        'jugadoresParaJavascript'
    )
);
    }


    /**
     * Guardar la planilla completa.
     */
    public function store(
        Request $request,
        Competencia $competencia
    ) {
        $this->validarClub($competencia);

        $clubId = auth()->user()->club_id;

        $jugadores = $request->input('jugadores', []);

        /*
        |--------------------------------------------------------------------------
        | Validamos que los jugadores realmente pertenezcan al club.
        |--------------------------------------------------------------------------
        */

        $jugadoresValidos = Jugador::where('club_id', $clubId)
            ->whereIn('id', $jugadores)
            ->with('categoria')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Construimos la información de la relación.
        |--------------------------------------------------------------------------
        */

        $participantes = [];

        foreach ($jugadoresValidos as $jugador) {

            $esRefuerzo =
                $competencia->categoria_id !== null &&
                $jugador->categoria_id != $competencia->categoria_id;

            $participantes[$jugador->id] = [
                'es_refuerzo' => $esRefuerzo,
                'categoria_origen_id' => $jugador->categoria_id,
                'observaciones' => null,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Sincronizamos.
        |
        | Esto permite:
        | - agregar jugadores
        | - quitar jugadores
        | - mantener varios torneos por jugador
        |--------------------------------------------------------------------------
        */

        $competencia->jugadores()->sync($participantes);

        return redirect()
            ->route(
                'competencias.participantes.index',
                $competencia
            )
            ->with(
                'success',
                'Planilla de participantes actualizada correctamente.'
            );
    }


    /**
     * Agregar un jugador individualmente.
     */
    public function agregar(
        Request $request,
        Competencia $competencia
    ) {
        $this->validarClub($competencia);

        $clubId = auth()->user()->club_id;

        $datos = $request->validate([
            'jugador_id' => [
                'required',
                'exists:jugadores,id',
            ],
        ]);

        $jugador = Jugador::where('club_id', $clubId)
            ->with('categoria')
            ->findOrFail($datos['jugador_id']);

        $esRefuerzo =
            $competencia->categoria_id !== null &&
            $jugador->categoria_id != $competencia->categoria_id;

        $competencia->jugadores()->syncWithoutDetaching([
            $jugador->id => [
                'es_refuerzo' => $esRefuerzo,
                'categoria_origen_id' => $jugador->categoria_id,
                'observaciones' => null,
            ],
        ]);

        return back()
            ->with(
                'success',
                'Jugador agregado a la competencia.'
            );
    }


    /**
     * Quitar jugador.
     */
    public function eliminar(
        Competencia $competencia,
        Jugador $jugador
    ) {
        $this->validarClub($competencia);

        $competencia->jugadores()
            ->detach($jugador->id);

        return back()
            ->with(
                'success',
                'Jugador retirado de la competencia.'
            );
    }


    /**
     * Seguridad: la competencia debe pertenecer al club.
     */
    private function validarClub(Competencia $competencia)
    {
        abort_unless(
            $competencia->club_id === auth()->user()->club_id,
            403
        );
    }

    public function exportar(Request $request, Competencia $competencia)
{
    $this->validarClub($competencia);

    $campos = $request->input('campos', []);

    if (empty($campos)) {
        return back()->with(
            'error',
            'Debes seleccionar al menos un dato del jugador.'
        );
    }

    return Excel::download(
        new CompetenciaParticipantesExport(
            $competencia,
            $campos
        ),
        'planilla-' . \Str::slug($competencia->nombre) . '.xlsx'
    );
}

public function exportarZip(Request $request, Competencia $competencia)
{
    $this->validarClub($competencia);

    $campos = $request->input('campos', []);
    $documentos = $request->input('documentos', []);

    if (empty($campos)) {
        return back()->with(
            'error',
            'Debes seleccionar al menos un dato del jugador.'
        );
    }

    if (empty($documentos)) {
        return back()->with(
            'error',
            'Debes seleccionar al menos un documento.'
        );
    }

    $service = new \App\Services\CompetenciaExportacionService();

    $zipPath = $service->generarZip(
        $competencia,
        $campos,
        $documentos
    );

    return response()
        ->download(
            $zipPath,
            'planilla-' . \Str::slug($competencia->nombre) . '.zip'
        )
        ->deleteFileAfterSend(true);
}

public function exportarPdf(Request $request, Competencia $competencia)
{
    $this->validarClub($competencia);

    $campos = $request->input('campos', []);

    if (empty($campos)) {
        return back()->with(
            'error',
            'Debes seleccionar al menos un dato del jugador.'
        );
    }

    $jugadores = $competencia
        ->jugadores()
        ->with('categoria')
        ->orderBy('apellidos')
        ->orderBy('nombres')
        ->get();

    $nombresCampos = [
        'nombres' => 'Nombres',
        'apellidos' => 'Apellidos',
        'documento' => 'Documento',
        'fecha_nacimiento' => 'Fecha de nacimiento',
        'telefono' => 'Teléfono',
        'email' => 'Email',
        'direccion' => 'Dirección',
        'eps' => 'EPS',
        'tipo_sangre' => 'Tipo de sangre',
        'acudiente' => 'Acudiente',
        'documento_acudiente' => 'Documento acudiente',
        'telefono_acudiente' => 'Teléfono acudiente',
        'email_acudiente' => 'Email acudiente',
        'parentesco' => 'Parentesco',
    ];

    $encabezados = [];

    foreach ($campos as $campo) {
        if (isset($nombresCampos[$campo])) {
            $encabezados[] = $nombresCampos[$campo];
        }
    }

    if (empty($encabezados)) {
        return back()->with(
            'error',
            'Los campos seleccionados no son válidos.'
        );
    }

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
        'competencias.participantes.pdf',
        [
            'competencia' => $competencia,
            'jugadores' => $jugadores,
            'campos' => $campos,
            'encabezados' => $encabezados,
        ]
    );

    $pdf->setPaper('letter', 'landscape');

    return $pdf->download(
        'planilla-' .
        \Str::slug($competencia->nombre) .
        '.pdf'
    );
}

}
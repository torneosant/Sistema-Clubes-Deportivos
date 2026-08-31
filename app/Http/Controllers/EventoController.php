<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\EventoOcurrencia;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventoController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LISTADO
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $clubId = auth()->user()->club_id;

        $eventos = Evento::where('club_id', $clubId)
            ->orderBy('fecha_inicio')
            ->orderBy('hora')
            ->paginate(15);

        return view(
            'eventos.index',
            compact('eventos')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREAR
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        return redirect()->route('calendario.index');
    }


    /*
    |--------------------------------------------------------------------------
    | GUARDAR EVENTO
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $datos = $request->validate([

            'titulo' =>
                'required|string|max:255',

            'descripcion' =>
                'nullable|string|max:5000',

            'fecha_inicio' =>
                'required|date',

            'hora' =>
                'nullable|date_format:H:i',

            'lugar' =>
                'nullable|string|max:255',

            'tipo' =>
                'required|string|max:100',

            'recurrencia' =>
                'required|in:unico,mensual,meses',

            'fecha_fin_recurrencia' =>
                'nullable|date|after_or_equal:fecha_inicio',

            'dia_recurrencia' =>
                'nullable|integer|min:1|max:31',

            'meses_recurrencia' =>
                'nullable|array',

            'meses_recurrencia.*' =>
                'integer|min:1|max:12',

        ]);


        $clubId = auth()->user()->club_id;


        /*
        |--------------------------------------------------------------------------
        | FECHA INICIAL
        |--------------------------------------------------------------------------
        */

        $fechaInicio = Carbon::parse(
            $datos['fecha_inicio']
        );


        /*
        |--------------------------------------------------------------------------
        | EVENTO ÚNICO
        |--------------------------------------------------------------------------
        */

        if ($datos['recurrencia'] === 'unico') {

            $datos['fecha_fin_recurrencia'] = null;

            $datos['dia_recurrencia'] = null;

            $datos['meses_recurrencia'] = null;
        }


        /*
        |--------------------------------------------------------------------------
        | EVENTO MENSUAL
        |--------------------------------------------------------------------------
        */

        if ($datos['recurrencia'] === 'mensual') {

            $datos['dia_recurrencia'] =
                $datos['dia_recurrencia']
                ?? $fechaInicio->day;

            $datos['meses_recurrencia'] = null;


            /*
            | Si no especifican hasta cuándo,
            | automáticamente será un año.
            */

            if (
                empty(
                    $datos['fecha_fin_recurrencia']
                )
            ) {

                $datos['fecha_fin_recurrencia'] =
                    $fechaInicio
                        ->copy()
                        ->addYear()
                        ->toDateString();
            }
        }


        /*
        |--------------------------------------------------------------------------
        | MESES SELECCIONADOS
        |--------------------------------------------------------------------------
        */

        if ($datos['recurrencia'] === 'meses') {

            $datos['meses_recurrencia'] =
                array_values(
                    $datos['meses_recurrencia'] ?? []
                );


            $datos['dia_recurrencia'] =
                $datos['dia_recurrencia']
                ?? $fechaInicio->day;


            if (
                empty(
                    $datos['fecha_fin_recurrencia']
                )
            ) {

                $datos['fecha_fin_recurrencia'] =
                    $fechaInicio
                        ->copy()
                        ->addYear()
                        ->toDateString();
            }
        }


        /*
        |--------------------------------------------------------------------------
        | CREAR EVENTO
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $datos,
            $clubId
        ) {

            $datos['club_id'] = $clubId;

            $datos['activo'] = true;

            $evento = Evento::create($datos);

            /*
            | Generamos automáticamente las ocurrencias.
            */

            $this->generarOcurrencias($evento);
        });


        return redirect()
            ->route('calendario.index')
            ->with(
                'success',
                'Evento creado correctamente.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | MOSTRAR
    |--------------------------------------------------------------------------
    */

    public function show(Evento $evento)
    {
        $this->verificarClub($evento);

        $evento->load('ocurrencias');

        return view(
            'eventos.show',
            compact('evento')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDITAR
    |--------------------------------------------------------------------------
    */

    public function edit(Evento $evento)
{
    if ($evento->club_id !== auth()->user()->club_id) {
        abort(403);
    }

    $evento->load('ocurrencias');

    return view(
        'calendario.eventos.edit',
        compact('evento')
    );
}


    /*
    |--------------------------------------------------------------------------
    | ACTUALIZAR
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Evento $evento
    ) {

        $this->verificarClub($evento);

        $datos = $request->validate([

            'titulo' =>
                'required|string|max:255',

            'descripcion' =>
                'nullable|string|max:5000',

            'fecha_inicio' =>
                'required|date',

            'hora' =>
                'nullable|date_format:H:i',

            'lugar' =>
                'nullable|string|max:255',

            'tipo' =>
                'required|string|max:100',

            'recurrencia' =>
                'required|in:unico,mensual,meses',

            'fecha_fin_recurrencia' =>
                'nullable|date|after_or_equal:fecha_inicio',

            'dia_recurrencia' =>
                'nullable|integer|min:1|max:31',

            'meses_recurrencia' =>
                'nullable|array',

            'meses_recurrencia.*' =>
                'integer|min:1|max:12',

            'activo' =>
                'nullable|boolean',

        ]);


        $fechaInicio = Carbon::parse(
            $datos['fecha_inicio']
        );


        $datos['activo'] =
            $request->boolean('activo');


        if ($datos['recurrencia'] === 'unico') {

            $datos['fecha_fin_recurrencia'] = null;

            $datos['dia_recurrencia'] = null;

            $datos['meses_recurrencia'] = null;
        }


        if ($datos['recurrencia'] === 'mensual') {

            $datos['dia_recurrencia'] =
                $datos['dia_recurrencia']
                ?? $fechaInicio->day;

            $datos['meses_recurrencia'] = null;
        }


        if ($datos['recurrencia'] === 'meses') {

            $datos['meses_recurrencia'] =
                array_values(
                    $datos['meses_recurrencia'] ?? []
                );

            $datos['dia_recurrencia'] =
                $datos['dia_recurrencia']
                ?? $fechaInicio->day;
        }


        DB::transaction(function () use (
            $evento,
            $datos
        ) {

            $evento->update($datos);


            /*
            |--------------------------------------------------------------------------
            | Regenerar ocurrencias no modificadas
            |--------------------------------------------------------------------------
            */

            $evento
                ->ocurrencias()
                ->where('modificada', false)
                ->delete();


            $this->generarOcurrencias($evento);
        });


        return redirect()
            ->route('calendario.index')
            ->with(
                'success',
                'Evento actualizado correctamente.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | ELIMINAR
    |--------------------------------------------------------------------------
    */

    public function destroy(Evento $evento)
    {
        $this->verificarClub($evento);

        $evento->delete();

        return redirect()
            ->route('calendario.index')
            ->with(
                'success',
                'Evento eliminado correctamente.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | GENERAR OCURRENCIAS
    |--------------------------------------------------------------------------
    */

    private function generarOcurrencias(
        Evento $evento
    ) {

        /*
        |--------------------------------------------------------------------------
        | EVENTO ÚNICO
        |--------------------------------------------------------------------------
        */

        if ($evento->recurrencia === 'unico') {

            $evento->ocurrencias()->updateOrCreate(

                [
                    'fecha_original' =>
                        $evento->fecha_inicio
                            ->toDateString(),
                ],

                [
                    'fecha' =>
                        $evento->fecha_inicio
                            ->toDateString(),

                    'hora' =>
                        $evento->hora,

                    'lugar' =>
                        $evento->lugar,

                    'modificada' =>
                        false,

                    'cancelada' =>
                        false,
                ]
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | INICIO Y FIN
        |--------------------------------------------------------------------------
        */

        $inicio =
            $evento->fecha_inicio->copy();


        $fin =
            $evento->fecha_fin_recurrencia
                ? $evento->fecha_fin_recurrencia->copy()
                : $inicio->copy()->addYear();


        /*
        |--------------------------------------------------------------------------
        | DÍA DE RECURRENCIA
        |--------------------------------------------------------------------------
        */

        $dia =
            $evento->dia_recurrencia
            ?? $inicio->day;


        /*
        |--------------------------------------------------------------------------
        | MESES SELECCIONADOS
        |--------------------------------------------------------------------------
        */

        $meses =
            $evento->meses_recurrencia
            ?? [];


        /*
        |--------------------------------------------------------------------------
        | RECORRER LOS MESES
        |--------------------------------------------------------------------------
        */

        $cursor =
            $inicio
                ->copy()
                ->startOfMonth();


        while ($cursor->lte($fin)) {

            $mes =
                $cursor->month;


            $debeCrear = false;


            /*
            |--------------------------------------------------------------------------
            | MENSUAL
            |--------------------------------------------------------------------------
            */

            if (
                $evento->recurrencia === 'mensual'
            ) {

                $debeCrear = true;
            }


            /*
            |--------------------------------------------------------------------------
            | MESES SELECCIONADOS
            |--------------------------------------------------------------------------
            */

            if (
                $evento->recurrencia === 'meses'
            ) {

                $debeCrear =
                    in_array(
                        $mes,
                        $meses
                    );
            }


            if ($debeCrear) {

                /*
                |--------------------------------------------------------------------------
                | Último día del mes
                |--------------------------------------------------------------------------
                |
                | Ejemplo:
                | día 31 en febrero → 28/29.
                |--------------------------------------------------------------------------
                */

                $ultimoDia =
                    $cursor
                        ->copy()
                        ->endOfMonth()
                        ->day;


                $diaReal =
                    min(
                        $dia,
                        $ultimoDia
                    );


                $fecha =
                    $cursor
                        ->copy()
                        ->day($diaReal);


                /*
                |--------------------------------------------------------------------------
                | No crear fechas anteriores
                |--------------------------------------------------------------------------
                */

                if (
                    $fecha->gte($inicio)
                    &&
                    $fecha->lte($fin)
                ) {

                    $evento
                        ->ocurrencias()
                        ->updateOrCreate(

                            [
                                'fecha_original' =>
                                    $fecha->toDateString(),
                            ],

                            [
                                'fecha' =>
                                    $fecha->toDateString(),

                                'hora' =>
                                    $evento->hora,

                                'lugar' =>
                                    $evento->lugar,

                                'modificada' =>
                                    false,

                                'cancelada' =>
                                    false,
                            ]
                        );
                }
            }


            $cursor->addMonth();
        }
    }


    /*
    |--------------------------------------------------------------------------
    | SEGURIDAD: EL EVENTO DEBE PERTENECER AL CLUB
    |--------------------------------------------------------------------------
    */

    private function verificarClub(
        Evento $evento
    ) {

        if (
            (int) $evento->club_id
            !== (int) auth()->user()->club_id
        ) {

            abort(403);
        }
    }
}
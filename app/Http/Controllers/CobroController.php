<?php

namespace App\Http\Controllers;

use App\Models\Cobro;
use App\Models\CargoJugador;
use App\Models\ConceptoContable;
use App\Models\Jugador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CobroController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $clubId = auth()->user()->club_id;

        $cobros = Cobro::with('concepto')
            ->where('club_id', $clubId)
            ->orderByDesc('activo')
            ->orderBy('tipo')
            ->orderBy('id', 'desc')
            ->get();

        return view(
            'cobros.index',
            compact('cobros')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $clubId = auth()->user()->club_id;

        $conceptos = ConceptoContable::where(
            'club_id',
            $clubId
        )
        ->where(
            'tipo',
            'Ingreso'
        )
        ->where(
            'activo',
            1
        )
        ->orderBy('nombre')
        ->get();

        return view(
            'cobros.create',
            compact('conceptos')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $clubId = auth()->user()->club_id;

        $datos = $request->validate([

            'concepto_contable_id' =>
                'required|exists:concepto_contables,id',

            'tipo' =>
                'required|in:Unico,Mensual',

            'valor' =>
                'required|numeric|min:1',

            'dia_cobro' =>
                'nullable|integer|min:1|max:31',

            'fecha_maxima' =>
                'nullable|date',

            'fecha_inicio' =>
                'nullable|date',

            'observaciones' =>
                'nullable|string',

        ]);


        /*
        |--------------------------------------------------------------------------
        | Verificar concepto
        |--------------------------------------------------------------------------
        */

        $concepto = ConceptoContable::where(
            'id',
            $datos['concepto_contable_id']
        )
        ->where(
            'club_id',
            $clubId
        )
        ->where(
            'tipo',
            'Ingreso'
        )
        ->where(
            'activo',
            1
        )
        ->first();


        if (!$concepto) {

            return back()
                ->withErrors([
                    'concepto_contable_id' =>
                        'El concepto seleccionado no pertenece al club.'
                ])
                ->withInput();

        }


        /*
        |--------------------------------------------------------------------------
        | Validaciones según tipo
        |--------------------------------------------------------------------------
        */

        if ($datos['tipo'] === 'Mensual') {

            if (
                empty($datos['dia_cobro'])
            ) {

                return back()
                    ->withErrors([
                        'dia_cobro' =>
                            'Indica el día de cobro mensual.'
                    ])
                    ->withInput();

            }

            $datos['fecha_maxima'] = null;

        }


        if ($datos['tipo'] === 'Unico') {

            if (
                empty($datos['fecha_maxima'])
            ) {

                return back()
                    ->withErrors([
                        'fecha_maxima' =>
                            'Indica la fecha máxima de cobro.'
                    ])
                    ->withInput();

            }

            $datos['dia_cobro'] = null;

        }


        $datos['club_id'] =
            $clubId;

        $datos['activo'] =
            $request->boolean('activo');


        Cobro::create($datos);


        return redirect()
            ->route('cobros.index')
            ->with(
                'success',
                'Cobro configurado correctamente.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(Cobro $cobro)
    {
        abort_unless(
            $cobro->club_id ==
            auth()->user()->club_id,
            403
        );

        $clubId =
            auth()->user()->club_id;

        $conceptos = ConceptoContable::where(
            'club_id',
            $clubId
        )
        ->where(
            'tipo',
            'Ingreso'
        )
        ->where(
            'activo',
            1
        )
        ->orderBy('nombre')
        ->get();

        return view(
            'cobros.edit',
            compact(
                'cobro',
                'conceptos'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Cobro $cobro
    ) {

        abort_unless(
            $cobro->club_id ==
            auth()->user()->club_id,
            403
        );

        $datos = $request->validate([

            'concepto_contable_id' =>
                'required|exists:concepto_contables,id',

            'tipo' =>
                'required|in:Unico,Mensual',

            'valor' =>
                'required|numeric|min:1',

            'dia_cobro' =>
                'nullable|integer|min:1|max:31',

            'fecha_maxima' =>
                'nullable|date',

            'fecha_inicio' =>
                'nullable|date',

            'observaciones' =>
                'nullable|string',

        ]);


        if ($datos['tipo'] === 'Mensual') {

            if (empty($datos['dia_cobro'])) {

                return back()
                    ->withErrors([
                        'dia_cobro' =>
                            'Indica el día de cobro mensual.'
                    ])
                    ->withInput();

            }

            $datos['fecha_maxima'] = null;

        }


        if ($datos['tipo'] === 'Unico') {

            if (empty($datos['fecha_maxima'])) {

                return back()
                    ->withErrors([
                        'fecha_maxima' =>
                            'Indica la fecha máxima de cobro.'
                    ])
                    ->withInput();

            }

            $datos['dia_cobro'] = null;

        }


        $cobro->update(
            $datos
        );


        return redirect()
            ->route('cobros.index')
            ->with(
                'success',
                'Cobro actualizado correctamente.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | ACTIVAR / DESACTIVAR
    |--------------------------------------------------------------------------
    */

    public function toggle(Cobro $cobro)
    {
        abort_unless(
            $cobro->club_id ==
            auth()->user()->club_id,
            403
        );

        $cobro->update([
            'activo' =>
                !$cobro->activo,
        ]);

        return back()
            ->with(
                'success',
                'Estado del cobro actualizado.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | GENERAR COBROS
    |--------------------------------------------------------------------------
    */

    public function generar(
        Request $request,
        Cobro $cobro
    ) {

        abort_unless(
            $cobro->club_id ==
            auth()->user()->club_id,
            403
        );


        $datos = $request->validate([

            'periodo' =>
                'required|date_format:Y-m',

        ]);


        $periodo =
            $datos['periodo'];


        /*
        |--------------------------------------------------------------------------
        | Solo cobros activos
        |--------------------------------------------------------------------------
        */

        if (!$cobro->activo) {

            return back()
                ->withErrors([
                    'cobro' =>
                        'Este cobro está inactivo.'
                ]);

        }


        /*
        |--------------------------------------------------------------------------
        | Jugadores activos
        |--------------------------------------------------------------------------
        */

        $jugadores = Jugador::where(
            'club_id',
            $cobro->club_id
        )
        ->where(
            'activo',
            1
        )
        ->get();


        $creados = 0;
        $existentes = 0;


        DB::transaction(function () use (
            $jugadores,
            $cobro,
            $periodo,
            &$creados,
            &$existentes
        ) {

            foreach ($jugadores as $jugador) {

                /*
                |--------------------------------------------------------------------------
                | Evitar duplicados
                |--------------------------------------------------------------------------
                */

                $existe = CargoJugador::where(
                    'club_id',
                    $cobro->club_id
                )
                ->where(
                    'jugador_id',
                    $jugador->id
                )
                ->where(
                    'concepto_contable_id',
                    $cobro->concepto_contable_id
                )
                ->where(
                    'periodo',
                    $periodo
                )
                ->exists();


                if ($existe) {

                    $existentes++;

                    continue;

                }


                /*
                |--------------------------------------------------------------------------
                | Fecha del cargo
                |--------------------------------------------------------------------------
                */

                $fecha = now()->toDateString();


                if ($cobro->tipo === 'Unico') {

                    $fecha =
                        $cobro->fecha_maxima
                            ? $cobro->fecha_maxima->toDateString()
                            : $fecha;

                }


                /*
                |--------------------------------------------------------------------------
                | Crear obligación
                |--------------------------------------------------------------------------
                */

                CargoJugador::create([

                    'club_id' =>
                        $cobro->club_id,

                    'jugador_id' =>
                        $jugador->id,

                    'concepto_contable_id' =>
                        $cobro->concepto_contable_id,

                    'periodo' =>
                        $periodo,

                    'fecha' =>
                        $fecha,

                    'valor' =>
                        $cobro->valor,

                    'valor_pagado' =>
                        0,

                    'estado' =>
                        'Pendiente',

                    'observaciones' =>
                        $cobro->observaciones,

                ]);


                $creados++;

            }

        });


        return redirect()
            ->route('cobros.index')
            ->with(
                'success',
                "Cobros generados: {$creados}. Ya existentes: {$existentes}."
            );
    }
}
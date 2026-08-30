<?php

namespace App\Http\Controllers;

use App\Models\ConceptoContable;
use App\Models\BecaJugador;
use App\Models\Jugador;
use Illuminate\Http\Request;
use App\Models\CargoJugador;
use Carbon\Carbon;

class ConceptoContableController extends Controller
{
    /**
     * Listado de conceptos contables.
     */
    public function index()
    {
        $clubId = auth()->user()->club_id;

        $conceptos = ConceptoContable::where('club_id', $clubId)
            ->orderBy('tipo')
            ->orderBy('nombre')
            ->get();

        return view(
            'conceptos-contables.index',
            compact('conceptos')
        );
    }


    /**
     * Crear concepto.
     */
    public function create()
    {
        return view('conceptos-contables.create');
    }


    /**
     * Guardar concepto.
     */
    public function store(Request $request)
    {
        $datos = $this->validarDatos($request);

        $datos['club_id'] = auth()->user()->club_id;

        $datos['activo'] = $request->boolean('activo');

        $datos = $this->prepararConfiguracionCobro(
            $request,
            $datos
        );

        ConceptoContable::create($datos);

        return redirect()
            ->route('conceptos-contables.index')
            ->with(
                'success',
                'Concepto creado correctamente.'
            );
    }


    /**
     * Mostrar concepto.
     */
    public function show($conceptos_contable)
    {
        $clubId = auth()->user()->club_id;

        $conceptoContable = ConceptoContable::where(
            'club_id',
            $clubId
        )
            ->where(
                'id',
                $conceptos_contable
            )
            ->firstOrFail();

        return view(
            'conceptos-contables.show',
            compact('conceptoContable')
        );
    }


    /**
     * Editar concepto.
     *
     * Carga también jugadores y becas.
     */
    public function edit($conceptos_contable)
    {
        $clubId = auth()->user()->club_id;

        $conceptoContable = ConceptoContable::where(
            'club_id',
            $clubId
        )
            ->where(
                'id',
                $conceptos_contable
            )
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Jugadores del club
        |--------------------------------------------------------------------------
        */

        $jugadores = Jugador::where(
            'club_id',
            $clubId
        )
            ->orderBy('apellidos')
            ->orderBy('nombres')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Becas del concepto
        |--------------------------------------------------------------------------
        */

        $becas = BecaJugador::with('jugador')
            ->where(
                'club_id',
                $clubId
            )
            ->where(
                'concepto_contable_id',
                $conceptoContable->id
            )
            ->orderByDesc('activo')
            ->orderBy('fecha_inicio')
            ->get();


        return view(
            'conceptos-contables.edit',
            compact(
                'conceptoContable',
                'jugadores',
                'becas'
            )
        );
    }


    /**
     * Actualizar concepto.
     */
    public function update(
        Request $request,
        $conceptos_contable
    ) {
        $clubId = auth()->user()->club_id;

        $conceptoContable = ConceptoContable::where(
            'club_id',
            $clubId
        )
            ->where(
                'id',
                $conceptos_contable
            )
            ->firstOrFail();


        $datos = $this->validarDatos($request);

        $datos['activo'] = $request->boolean('activo');

        $datos = $this->prepararConfiguracionCobro(
            $request,
            $datos
        );


        $conceptoContable->update($datos);


        return redirect()
            ->route(
                'conceptos-contables.edit',
                $conceptoContable
            )
            ->with(
                'success',
                'Concepto actualizado correctamente.'
            );
    }


    /**
     * Guardar una beca.
     *
     * Tipos:
     *
     * siempre
     * anio
     * personalizado
     */
    public function guardarBeca(
        Request $request,
        $conceptos_contable
    ) {
        $clubId = auth()->user()->club_id;


        /*
        |--------------------------------------------------------------------------
        | Buscar concepto
        |--------------------------------------------------------------------------
        */

        $conceptoContable = ConceptoContable::where(
            'club_id',
            $clubId
        )
            ->where(
                'id',
                $conceptos_contable
            )
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Validación
        |--------------------------------------------------------------------------
        */

        $datos = $request->validate([

            'jugador_id' => [
                'required',
                'integer',
                'exists:jugadores,id',
            ],

            'tipo_vigencia' => [
                'required',
                'in:siempre,anio,personalizado',
            ],

            'anio' => [
                'nullable',
                'integer',
                'min:2000',
                'max:2100',
            ],

            'fecha_inicio' => [
                'nullable',
                'date',
            ],

            'fecha_fin' => [
                'nullable',
                'date',
            ],

            'porcentaje' => [
                'required',
                'numeric',
                'min:1',
                'max:100',
            ],

            'motivo' => [
                'nullable',
                'string',
                'max:1000',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Verificar jugador
        |--------------------------------------------------------------------------
        |
        | El jugador debe pertenecer al mismo club.
        |
        */

        $jugador = Jugador::where(
            'club_id',
            $clubId
        )
            ->where(
                'id',
                $datos['jugador_id']
            )
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Determinar vigencia
        |--------------------------------------------------------------------------
        */

        switch ($datos['tipo_vigencia']) {

            /*
            |--------------------------------------------------------------------------
            | POR SIEMPRE
            |--------------------------------------------------------------------------
            */

            case 'siempre':

                $fechaInicio = now()
                    ->startOfDay()
                    ->toDateString();

                $fechaFin = null;

                break;


            /*
            |--------------------------------------------------------------------------
            | TODO EL AÑO
            |--------------------------------------------------------------------------
            */

            case 'anio':

                if (empty($datos['anio'])) {

                    return back()
                        ->withErrors([
                            'anio' =>
                                'Debes seleccionar el año de la beca.'
                        ])
                        ->withInput();
                }


                $anio = (int) $datos['anio'];

                $fechaInicio =
                    $anio . '-01-01';

                $fechaFin =
                    $anio . '-12-31';

                break;


            /*
            |--------------------------------------------------------------------------
            | PERSONALIZADO
            |--------------------------------------------------------------------------
            */

            case 'personalizado':

                if (
                    empty($datos['fecha_inicio']) ||
                    empty($datos['fecha_fin'])
                ) {

                    return back()
                        ->withErrors([
                            'fecha_inicio' =>
                                'Debes indicar las fechas de inicio y finalización.'
                        ])
                        ->withInput();
                }


                if (
                    $datos['fecha_fin'] <
                    $datos['fecha_inicio']
                ) {

                    return back()
                        ->withErrors([
                            'fecha_fin' =>
                                'La fecha final debe ser igual o posterior a la fecha inicial.'
                        ])
                        ->withInput();
                }


                $fechaInicio =
                    $datos['fecha_inicio'];

                $fechaFin =
                    $datos['fecha_fin'];

                break;
        }


        /*
        |--------------------------------------------------------------------------
        | Evitar becas duplicadas o periodos cruzados
        |--------------------------------------------------------------------------
        */

        $becaExistente = BecaJugador::where(
            'club_id',
            $clubId
        )
            ->where(
                'jugador_id',
                $jugador->id
            )
            ->where(
                'concepto_contable_id',
                $conceptoContable->id
            )
            ->where(
                'activo',
                true
            )
            ->where(function ($query) use (
                $fechaInicio,
                $fechaFin
            ) {

                /*
                |--------------------------------------------------------------------------
                | Nueva beca permanente
                |--------------------------------------------------------------------------
                */

                if (!$fechaFin) {

                    $query
                        ->where(
                            'fecha_fin',
                            '>=',
                            $fechaInicio
                        )
                        ->orWhereNull(
                            'fecha_fin'
                        );

                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | Becas existentes permanentes
                |--------------------------------------------------------------------------
                */

                $query->where(function ($q) use (
                    $fechaInicio,
                    $fechaFin
                ) {

                    $q->whereNull(
                        'fecha_fin'
                    )
                        ->where(
                            'fecha_inicio',
                            '<=',
                            $fechaFin
                        );

                })


                /*
                |--------------------------------------------------------------------------
                | Periodos normales cruzados
                |--------------------------------------------------------------------------
                */

                    ->orWhere(function ($q) use (
                        $fechaInicio,
                        $fechaFin
                    ) {

                        $q->where(
                            'fecha_inicio',
                            '<=',
                            $fechaFin
                        )
                            ->where(
                                'fecha_fin',
                                '>=',
                                $fechaInicio
                            );

                    });

            })
            ->exists();


        /*
        |--------------------------------------------------------------------------
        | Si ya existe una beca cruzada
        |--------------------------------------------------------------------------
        */

        if ($becaExistente) {

            return back()
                ->withErrors([
                    'jugador_id' =>
                        'Este jugador ya tiene una beca activa para este concepto durante un periodo que se cruza con el seleccionado.'
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Crear beca
        |--------------------------------------------------------------------------
        */

        BecaJugador::create([

            'club_id' =>
                $clubId,

            'jugador_id' =>
                $jugador->id,

            'concepto_contable_id' =>
                $conceptoContable->id,

            'fecha_inicio' =>
                $fechaInicio,

            'fecha_fin' =>
                $fechaFin,

            'porcentaje' =>
                $datos['porcentaje'],

            'motivo' =>
                $datos['motivo'] ?? null,

            'activo' =>
                true,

        ]);


        /*
        |--------------------------------------------------------------------------
        | Volver al concepto
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'conceptos-contables.edit',
                $conceptoContable
            )
            ->with(
                'success',
                'Beca configurada correctamente.'
            );
    }
/**
 * Generar cobros del concepto para un periodo.
 */
/**
 * Generar cobros mensuales para uno o varios meses.
 */
public function generarCobros(
    Request $request,
    $conceptos_contable
) {
    $clubId = auth()->user()->club_id;

    /*
    |--------------------------------------------------------------------------
    | Buscar concepto
    |--------------------------------------------------------------------------
    */

    $concepto = ConceptoContable::where(
        'club_id',
        $clubId
    )
        ->where(
            'id',
            $conceptos_contable
        )
        ->firstOrFail();


    /*
    |--------------------------------------------------------------------------
    | Validar configuración
    |--------------------------------------------------------------------------
    */

    if (!$concepto->activo) {

        return back()
            ->withErrors([
                'cobro' =>
                    'El concepto está inactivo.'
            ]);
    }


    if (!$concepto->genera_cobro) {

        return back()
            ->withErrors([
                'cobro' =>
                    'Este concepto no está configurado para generar cobros.'
            ]);
    }


    if ($concepto->tipo_cobro !== 'Mensual') {

        return back()
            ->withErrors([
                'cobro' =>
                    'Este proceso solamente genera cobros mensuales.'
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Año
    |--------------------------------------------------------------------------
    */

    $anio = (int) $request->input(
        'anio',
        now()->year
    );


    /*
    |--------------------------------------------------------------------------
    | Meses seleccionados
    |--------------------------------------------------------------------------
    */

    $meses = $request->input(
        'meses',
        []
    );


    /*
    |--------------------------------------------------------------------------
    | Generar año completo
    |--------------------------------------------------------------------------
    */

    if ($request->boolean('todo_el_anio')) {

        $meses = range(1, 12);

    }


    /*
    |--------------------------------------------------------------------------
    | Validar meses
    |--------------------------------------------------------------------------
    */

    $meses = collect($meses)
        ->map(fn ($mes) => (int) $mes)
        ->filter(fn ($mes) =>
            $mes >= 1 && $mes <= 12
        )
        ->unique()
        ->sort()
        ->values()
        ->toArray();


    if (empty($meses)) {

        return back()
            ->withErrors([
                'meses' =>
                    'Debes seleccionar al menos un mes.'
            ])
            ->withInput();
    }


    /*
    |--------------------------------------------------------------------------
    | Jugadores activos
    |--------------------------------------------------------------------------
    */

    $jugadores = Jugador::where(
        'club_id',
        $clubId
    )
        ->where('activo', true)
        ->get();


    $creados = 0;
    $becados = 0;
    $existentes = 0;


    /*
    |--------------------------------------------------------------------------
    | Procesar meses
    |--------------------------------------------------------------------------
    */

    foreach ($meses as $numeroMes) {

        $fechaPeriodo = Carbon::create(
            $anio,
            $numeroMes,
            1
        )->startOfMonth();


        $periodo = $fechaPeriodo->format('Y-m');


        /*
        |--------------------------------------------------------------------------
        | Respetar fecha de inicio del concepto
        |--------------------------------------------------------------------------
        */

        if (
            $concepto->fecha_inicio &&
            $fechaPeriodo->lt(
                Carbon::parse(
                    $concepto->fecha_inicio
                )->startOfMonth()
            )
        ) {

            continue;
        }


        /*
        |--------------------------------------------------------------------------
        | Fecha del cargo
        |--------------------------------------------------------------------------
        */

        $diaCobro = (int) (
            $concepto->dia_cobro ?: 1
        );


        $diaCobro = min(
            $diaCobro,
            $fechaPeriodo->daysInMonth
        );


        $fechaCargo = $fechaPeriodo
            ->copy()
            ->day($diaCobro);


        /*
        |--------------------------------------------------------------------------
        | Procesar jugadores
        |--------------------------------------------------------------------------
        */

        foreach ($jugadores as $jugador) {


            /*
            |--------------------------------------------------------------------------
            | Evitar duplicados
            |--------------------------------------------------------------------------
            */

            $yaExiste = CargoJugador::where(
                'club_id',
                $clubId
            )
                ->where(
                    'jugador_id',
                    $jugador->id
                )
                ->where(
                    'concepto_contable_id',
                    $concepto->id
                )
                ->where(
                    'periodo',
                    $periodo
                )
                ->exists();


            if ($yaExiste) {

                $existentes++;

                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Buscar beca vigente
            |--------------------------------------------------------------------------
            */

            $beca = BecaJugador::where(
                'club_id',
                $clubId
            )
                ->where(
                    'jugador_id',
                    $jugador->id
                )
                ->where(
                    'concepto_contable_id',
                    $concepto->id
                )
                ->where(
                    'activo',
                    true
                )
                ->where(
                    'fecha_inicio',
                    '<=',
                    $fechaPeriodo->copy()->endOfMonth()
                )
                ->where(function ($query) use ($fechaPeriodo) {

                    $query
                        ->whereNull('fecha_fin')
                        ->orWhere(
                            'fecha_fin',
                            '>=',
                            $fechaPeriodo->copy()->startOfMonth()
                        );

                })
                ->first();


            /*
            |--------------------------------------------------------------------------
            | Beca del 100%
            |--------------------------------------------------------------------------
            */

            if (
                $beca &&
                (float) $beca->porcentaje >= 100
            ) {

                $becados++;

                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Calcular valor
            |--------------------------------------------------------------------------
            */

            $valor = (float) $concepto->valor_cobro;


            /*
            |--------------------------------------------------------------------------
            | Aplicar beca parcial
            |--------------------------------------------------------------------------
            */

            if ($beca) {

                $porcentaje =
                    (float) $beca->porcentaje;

                $valor =
                    $valor *
                    (
                        1 -
                        ($porcentaje / 100)
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | Crear cargo
            |--------------------------------------------------------------------------
            */

            CargoJugador::create([

                'club_id' =>
                    $clubId,

                'jugador_id' =>
                    $jugador->id,

                'concepto_contable_id' =>
                    $concepto->id,

                'periodo' =>
                    $periodo,

                'fecha' =>
                    $fechaCargo,

                'valor' =>
                    $valor,

                'valor_pagado' =>
                    0,

                'estado' =>
                    'Pendiente',

                'motivo_exoneracion' =>
                    $beca
                        ? $beca->motivo
                        : null,

            ]);


            $creados++;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Resultado
    |--------------------------------------------------------------------------
    */

    return back()
        ->with(
            'success',
            "Proceso terminado. Cobros creados: {$creados}. " .
            "Jugadores becados/exonerados: {$becados}. " .
            "Cobros que ya existían: {$existentes}."
        );
}



public function destroy($conceptos_contable)
    {
        $clubId = auth()->user()->club_id;

        $conceptoContable = ConceptoContable::where(
            'club_id',
            $clubId
        )
            ->where(
                'id',
                $conceptos_contable
            )
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Movimientos
        |--------------------------------------------------------------------------
        */

        $tieneMovimientos =
            $conceptoContable
                ->movimientos()
                ->exists();


        /*
        |--------------------------------------------------------------------------
        | Cargos
        |--------------------------------------------------------------------------
        */

        $tieneCargos =
            $conceptoContable
                ->cargos()
                ->exists();


        /*
        |--------------------------------------------------------------------------
        | Becas
        |--------------------------------------------------------------------------
        */

        $tieneBecas =
            $conceptoContable
                ->becas()
                ->exists();


        /*
        |--------------------------------------------------------------------------
        | Si tiene historial, desactivar
        |--------------------------------------------------------------------------
        */

        if (
            $tieneMovimientos ||
            $tieneCargos ||
            $tieneBecas
        ) {

            $conceptoContable->update([
                'activo' => false,
            ]);


            return redirect()
                ->route(
                    'conceptos-contables.index'
                )
                ->with(
                    'success',
                    'El concepto tiene registros asociados y fue desactivado para conservar el historial.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Si nunca se utilizó, eliminar
        |--------------------------------------------------------------------------
        */

        $conceptoContable->delete();


        return redirect()
            ->route(
                'conceptos-contables.index'
            )
            ->with(
                'success',
                'Concepto eliminado correctamente.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDAR DATOS DEL CONCEPTO
    |--------------------------------------------------------------------------
    */

    private function validarDatos(
        Request $request
    ) {
        return $request->validate([

            'nombre' =>
                'required|string|max:255',

            'tipo' => [
                'required',
                'in:Ingreso,Egreso',
            ],

            'valor_predeterminado' =>
                'nullable|numeric|min:0',

            'descripcion' =>
                'nullable|string',

            /*
            |--------------------------------------------------------------------------
            | Configuración de cobro
            |--------------------------------------------------------------------------
            */

            'tipo_cobro' => [
                'nullable',
                'in:Unico,Mensual',
            ],

            'valor_cobro' =>
                'nullable|numeric|min:0',

            'dia_cobro' =>
                'nullable|integer|min:1|max:31',

            'fecha_maxima' =>
                'nullable|date',

            'fecha_inicio' =>
                'nullable|date',

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | PREPARAR CONFIGURACIÓN DE COBRO
    |--------------------------------------------------------------------------
    */

    private function prepararConfiguracionCobro(
        Request $request,
        array $datos
    ) {

        /*
        |--------------------------------------------------------------------------
        | Solo ingresos pueden generar cobros
        |--------------------------------------------------------------------------
        */

        if (
            $request->tipo !== 'Ingreso' ||
            !$request->boolean('genera_cobro')
        ) {

            $datos['genera_cobro'] = false;
            $datos['tipo_cobro'] = null;
            $datos['valor_cobro'] = null;
            $datos['dia_cobro'] = null;
            $datos['fecha_maxima'] = null;
            $datos['fecha_inicio'] = null;

            return $datos;
        }


        $datos['genera_cobro'] = true;


        /*
        |--------------------------------------------------------------------------
        | COBRO MENSUAL
        |--------------------------------------------------------------------------
        */

        if (
            $request->tipo_cobro === 'Mensual'
        ) {

            if (
                !$request->filled('dia_cobro')
            ) {

                abort(
                    422,
                    'Debes indicar el día de cobro mensual.'
                );
            }


            $datos['tipo_cobro'] =
                'Mensual';

            $datos['dia_cobro'] =
                $request->dia_cobro;

            $datos['fecha_maxima'] =
                null;
        }


        /*
        |--------------------------------------------------------------------------
        | COBRO ÚNICO
        |--------------------------------------------------------------------------
        */

        elseif (
            $request->tipo_cobro === 'Unico'
        ) {

            if (
                !$request->filled('fecha_maxima')
            ) {

                abort(
                    422,
                    'Debes indicar la fecha máxima del cobro.'
                );
            }


            $datos['tipo_cobro'] =
                'Unico';

            $datos['fecha_maxima'] =
                $request->fecha_maxima;

            $datos['dia_cobro'] =
                null;
        }


        /*
        |--------------------------------------------------------------------------
        | VALOR DEL COBRO
        |--------------------------------------------------------------------------
        */

        if (
            empty($request->valor_cobro)
        ) {

            abort(
                422,
                'Debes indicar el valor del cobro.'
            );
        }


        $datos['valor_cobro'] =
            $request->valor_cobro;


        /*
        |--------------------------------------------------------------------------
        | FECHA DE INICIO
        |--------------------------------------------------------------------------
        */

        $datos['fecha_inicio'] =
            $request->fecha_inicio;


        return $datos;
    }
}
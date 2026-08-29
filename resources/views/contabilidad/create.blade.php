@extends('layouts.app')

@section('titulo')
💰 Nuevo Movimiento
@endsection

@section('contenido')

<div class="max-w-5xl mx-auto">

    {{-- ENCABEZADO --}}

    <div class="mb-6">

        <h1 class="text-2xl font-bold text-slate-800">
            💰 Nuevo movimiento
        </h1>

        <p class="text-sm text-gray-500 mt-1">
            Registra un ingreso o egreso del club.
        </p>

    </div>


    {{-- MENSAJES DE ERROR --}}

    @if($errors->any())

        <div class="mb-5 rounded-xl bg-red-50 border border-red-200
                    px-4 py-3 text-red-700">

            <div class="font-semibold mb-2">
                Revisa la información:
            </div>

            <ul class="list-disc ml-5 text-sm">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- FORMULARIO --}}

    <div class="bg-white rounded-xl shadow-sm
                border border-gray-200 overflow-hidden">

        {{-- CABECERA --}}

        <div class="px-6 py-4 bg-gray-50 border-b">

            <h2 class="font-bold text-slate-700">
                Registrar movimiento
            </h2>

            <p class="text-xs text-gray-500 mt-1">
                Los ingresos de jugadores pueden asociarse
                automáticamente con una obligación pendiente.
            </p>

        </div>


        <form
            method="POST"
            action="{{ route('contabilidad.store') }}"
            class="p-6"
        >

            @csrf


            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">


                {{-- =====================================================
                     FECHA
                ====================================================== --}}

                <div>

                    <label class="block text-sm font-semibold
                                  text-gray-700 mb-1">

                        Fecha

                    </label>

                    <input
                        type="date"
                        name="fecha"
                        value="{{ old(
                            'fecha',
                            date('Y-m-d')
                        ) }}"
                        required
                        class="w-full border border-gray-300
                               rounded-lg px-3 py-2
                               focus:ring-2 focus:ring-blue-500
                               focus:border-blue-500"
                    >

                </div>


                {{-- =====================================================
                     TIPO
                ====================================================== --}}

                <div>

                    <label class="block text-sm font-semibold
                                  text-gray-700 mb-1">

                        Tipo de movimiento

                    </label>

                    <select
                        name="tipo"
                        id="tipoMovimiento"
                        required
                        class="w-full border border-gray-300
                               rounded-lg px-3 py-2
                               bg-white
                               focus:ring-2 focus:ring-blue-500
                               focus:border-blue-500"
                    >

                        <option value="Ingreso"
                            @selected(
                                old('tipo', 'Ingreso') === 'Ingreso'
                            )
                        >
                            💰 Ingreso
                        </option>

                        <option value="Egreso"
                            @selected(
                                old('tipo') === 'Egreso'
                            )
                        >
                            💸 Egreso
                        </option>

                    </select>

                </div>


                {{-- =====================================================
                     CONCEPTO
                ====================================================== --}}

                <div>

                    <label class="block text-sm font-semibold
                                  text-gray-700 mb-1">

                        Concepto

                    </label>

                    <select
                        name="concepto_contable_id"
                        id="concepto"
                        required
                        class="w-full border border-gray-300
                               rounded-lg px-3 py-2
                               bg-white
                               focus:ring-2 focus:ring-blue-500
                               focus:border-blue-500"
                    >

                        <option value="">
                            Seleccione...
                        </option>


                        @foreach($conceptos as $concepto)

                            <option
                                value="{{ $concepto->id }}"
                                data-jugador="{{ $concepto->requiere_jugador ?? 0 }}"
                                data-valor="{{ $concepto->valor_predeterminado ?? '' }}"

                                @selected(
                                    old(
                                        'concepto_contable_id'
                                    ) == $concepto->id
                                )
                            >

                                {{ $concepto->nombre }}

                            </option>

                        @endforeach

                    </select>

                </div>


                
{{-- =====================================================
     JUGADOR
====================================================== --}}

<div>

    <label class="block text-sm font-semibold
                  text-gray-700 mb-1">

        Jugador

    </label>


    {{-- Campo que el usuario puede escribir y buscar --}}

    <input
        type="text"
        id="jugador_busqueda"
        list="listaJugadores"
        autocomplete="off"
        placeholder="🔎 Escribe el nombre del jugador..."
        value=""
        class="w-full border border-gray-300
               rounded-lg px-3 py-2
               focus:ring-2 focus:ring-blue-500
               focus:border-blue-500"
    >


    {{-- ID real que se enviará al controlador --}}

    <input
        type="hidden"
        name="jugador_id"
        id="jugador"
        value="{{ old('jugador_id') }}"
    >


    {{-- Lista de jugadores --}}

    <datalist id="listaJugadores">

        @foreach($jugadores as $jugador)

            <option
                value="{{ $jugador->apellidos }} {{ $jugador->nombres }}"
                data-id="{{ $jugador->id }}"
            >

            </option>

        @endforeach

    </datalist>


    <p
        id="mensajeJugador"
        class="text-xs text-gray-400 mt-1"
    >

        Escribe para buscar un jugador.

    </p>

</div>


                {{-- =====================================================
                     PENDIENTE
                ====================================================== --}}

                <div
                    id="bloquePendiente"
                    class="hidden md:col-span-2"
                >

                    <div class="rounded-xl border border-blue-200
                                bg-blue-50 p-4">


                        <div class="flex items-center gap-2 mb-3">

                            <span class="text-lg">
                                📋
                            </span>

                            <div>

                                <h3 class="font-semibold text-blue-800">

                                    ¿Qué está pagando?

                                </h3>

                                <p class="text-xs text-blue-600">

                                    Selecciona la obligación que corresponde
                                    a este ingreso.

                                </p>

                            </div>

                        </div>


                        <label
                            class="block text-sm font-semibold
                                   text-gray-700 mb-1"
                        >

                            Pendiente del jugador

                        </label>


                        <select
                            name="cargo_jugador_id"
                            id="cargo_jugador_id"
                            class="w-full border border-blue-200
                                   rounded-lg px-3 py-2
                                   bg-white
                                   focus:ring-2 focus:ring-blue-500
                                   focus:border-blue-500"
                        >

                            <option value="">
                                Seleccione el pendiente...
                            </option>


                            @foreach($cargosPendientes as $cargo)

                                @php

                                    $pendiente = max(
                                        0,
                                        (float) $cargo->valor -
                                        (float) $cargo->valor_pagado
                                    );

                                @endphp


                                <option
                                    value="{{ $cargo->id }}"
                                    data-jugador="{{ $cargo->jugador_id }}"
                                    data-pendiente="{{ $pendiente }}"
                                    data-periodo="{{ $cargo->periodo }}"
                                    data-concepto="{{ $cargo->concepto_contable_id }}"

                                    @selected(
                                        old(
                                            'cargo_jugador_id'
                                        ) == $cargo->id
                                    )
                                >

                                    {{ $cargo->concepto?->nombre ?? 'Sin concepto' }}

                                    —

                                    {{ $cargo->periodo }}

                                    —

                                    ${{ number_format(
                                        $pendiente,
                                        0,
                                        ',',
                                        '.'
                                    ) }}

                                </option>

                            @endforeach

                        </select>


                        <div
                            id="sinPendientes"
                            class="hidden mt-3 text-sm
                                   text-blue-700"
                        >

                            ℹ️ Este jugador no tiene obligaciones
                            pendientes.

                        </div>

                    </div>

                </div>


                {{-- =====================================================
                     PERIODO
                ====================================================== --}}

                <div>

                    <label class="block text-sm font-semibold
                                  text-gray-700 mb-1">

                        Periodo

                    </label>

                    <input
                        type="month"
                        name="periodo"
                        id="periodo"
                        value="{{ old('periodo') }}"
                        class="w-full border border-gray-300
                               rounded-lg px-3 py-2
                               focus:ring-2 focus:ring-blue-500
                               focus:border-blue-500"
                    >

                    <p class="text-xs text-gray-400 mt-1">

                        Ejemplo: Agosto 2026.

                    </p>

                </div>


                {{-- =====================================================
                     VALOR
                ====================================================== --}}

                <div>

                    <label class="block text-sm font-semibold
                                  text-gray-700 mb-1">

                        Valor

                    </label>


                    <div class="relative">

                        <span
                            class="absolute left-3 top-1/2
                                   -translate-y-1/2
                                   text-gray-500 font-semibold"
                        >
                            $
                        </span>


                        <input
                            type="number"
                            name="valor"
                            id="valor"
                            value="{{ old('valor') }}"
                            min="1"
                            step="1"
                            required
                            placeholder="20000"
                            class="w-full border border-gray-300
                                   rounded-lg pl-8 pr-3 py-2
                                   focus:ring-2 focus:ring-blue-500
                                   focus:border-blue-500"
                        >

                    </div>


                    <p
                        id="mensajeValor"
                        class="text-xs text-gray-400 mt-1"
                    >
                        Ingresa el valor del movimiento.

                    </p>

                </div>


                {{-- =====================================================
                     TERCERO
                ====================================================== --}}

                <div>

                    <label class="block text-sm font-semibold
                                  text-gray-700 mb-1">

                        Pagador / Beneficiario

                    </label>

                    <input
                        type="text"
                        name="tercero"
                        value="{{ old('tercero') }}"
                        placeholder="Ej: Madre del jugador, Nike..."
                        class="w-full border border-gray-300
                               rounded-lg px-3 py-2
                               focus:ring-2 focus:ring-blue-500
                               focus:border-blue-500"
                    >

                </div>


                {{-- =====================================================
                     MÉTODO DE PAGO
                ====================================================== --}}

                <div>

                    <label class="block text-sm font-semibold
                                  text-gray-700 mb-1">

                        Método de pago

                    </label>

                    <select
                        name="metodo_pago"
                        class="w-full border border-gray-300
                               rounded-lg px-3 py-2
                               bg-white
                               focus:ring-2 focus:ring-blue-500
                               focus:border-blue-500"
                    >

                        <option value="Efectivo"
                            @selected(
                                old('metodo_pago')
                                === 'Efectivo'
                            )
                        >
                            Efectivo
                        </option>

                        <option value="Transferencia"
                            @selected(
                                old('metodo_pago')
                                === 'Transferencia'
                            )
                        >
                            Transferencia
                        </option>

                        <option value="Nequi"
                            @selected(
                                old('metodo_pago')
                                === 'Nequi'
                            )
                        >
                            Nequi
                        </option>

                        <option value="Daviplata"
                            @selected(
                                old('metodo_pago')
                                === 'Daviplata'
                            )
                        >
                            Daviplata
                        </option>

                        <option value="Tarjeta"
                            @selected(
                                old('metodo_pago')
                                === 'Tarjeta'
                            )
                        >
                            Tarjeta
                        </option>

                        <option value="Otro"
                            @selected(
                                old('metodo_pago')
                                === 'Otro'
                            )
                        >
                            Otro
                        </option>

                    </select>

                </div>


                {{-- =====================================================
                     OBSERVACIONES
                ====================================================== --}}

                <div class="md:col-span-2">

                    <label class="block text-sm font-semibold
                                  text-gray-700 mb-1">

                        Observaciones

                    </label>

                    <textarea
                        name="observaciones"
                        rows="3"
                        placeholder="Observaciones opcionales..."
                        class="w-full border border-gray-300
                               rounded-lg px-3 py-2
                               focus:ring-2 focus:ring-blue-500
                               focus:border-blue-500"
                    >{{ old('observaciones') }}</textarea>

                </div>

            </div>


            {{-- =====================================================
                 INFORMACIÓN
            ====================================================== --}}

            <div
                id="mensajePago"
                class="hidden mt-5 rounded-xl
                       bg-green-50 border border-green-200
                       px-4 py-3"
            >

                <div class="flex gap-3">

                    <div class="text-lg">
                        ✓
                    </div>

                    <div>

                        <div class="font-semibold text-green-800">

                            Pago asociado a una obligación

                        </div>

                        <div
                            id="detallePago"
                            class="text-sm text-green-700 mt-1"
                        >

                        </div>

                    </div>

                </div>

            </div>


            {{-- =====================================================
                 BOTONES
            ====================================================== --}}

            <div
                class="mt-6 pt-5 border-t
                       flex justify-end gap-3"
            >

                <a
                    href="{{ route('contabilidad.index') }}"
                    class="px-5 py-2 rounded-lg
                           bg-gray-200 hover:bg-gray-300
                           text-gray-700 font-semibold
                           text-sm transition"
                >

                    Cancelar

                </a>


                <button
                    type="submit"
                    class="px-5 py-2 rounded-lg
                           bg-green-600 hover:bg-green-700
                           text-white font-semibold
                           text-sm shadow-sm transition"
                >

                    💾 Guardar movimiento

                </button>

            </div>

        </form>

    </div>

</div>


{{-- =========================================================
     JAVASCRIPT
========================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const tipo =
        document.getElementById('tipoMovimiento');

    const concepto =
        document.getElementById('concepto');

    /*
    |--------------------------------------------------------------------------
    | JUGADOR
    |--------------------------------------------------------------------------
    */

    const jugador =
        document.getElementById('jugador');

    const jugadorBusqueda =
        document.getElementById('jugador_busqueda');

    const listaJugadores =
        document.getElementById('listaJugadores');

    const mensajeJugador =
        document.getElementById('mensajeJugador');


    /*
    |--------------------------------------------------------------------------
    | PENDIENTES
    |--------------------------------------------------------------------------
    */

    const bloquePendiente =
        document.getElementById('bloquePendiente');

    const selectCargo =
        document.getElementById('cargo_jugador_id');

    const sinPendientes =
        document.getElementById('sinPendientes');


    /*
    |--------------------------------------------------------------------------
    | OTROS CAMPOS
    |--------------------------------------------------------------------------
    */

    const valor =
        document.getElementById('valor');

    const periodo =
        document.getElementById('periodo');

    const mensajeValor =
        document.getElementById('mensajeValor');

    const mensajePago =
        document.getElementById('mensajePago');

    const detallePago =
        document.getElementById('detallePago');


    /*
    |--------------------------------------------------------------------------
    | BUSCAR JUGADOR
    |--------------------------------------------------------------------------
    */

    function buscarJugador() {

        const texto =
            jugadorBusqueda.value
                .trim()
                .toLowerCase();


        /*
        |--------------------------------------------------------------------------
        | Si está vacío
        |--------------------------------------------------------------------------
        */

        if (!texto) {

            jugador.value = '';

            mensajeJugador.textContent =
                'Escribe para buscar un jugador.';

            mensajeJugador.className =
                'text-xs text-gray-400 mt-1';

            actualizarPendientes();

            return;

        }


        let encontrado = null;


        /*
        |--------------------------------------------------------------------------
        | Buscar coincidencia exacta en el datalist
        |--------------------------------------------------------------------------
        */

        Array.from(
            listaJugadores.options
        ).forEach(function (option) {

            const nombre =
                option.value
                    .trim()
                    .toLowerCase();


            if (nombre === texto) {

                encontrado =
                    option.dataset.id;

            }

        });


        /*
        |--------------------------------------------------------------------------
        | Jugador encontrado
        |--------------------------------------------------------------------------
        */

        if (encontrado) {

            jugador.value =
                encontrado;


            mensajeJugador.textContent =
                '✓ Jugador seleccionado correctamente.';

            mensajeJugador.className =
                'text-xs text-green-600 mt-1';


            actualizarPendientes();

        } else {

            /*
            |--------------------------------------------------------------------------
            | Todavía no seleccionó un jugador válido
            |--------------------------------------------------------------------------
            */

            jugador.value = '';


            mensajeJugador.textContent =
                'Selecciona un jugador de la lista.';

            mensajeJugador.className =
                'text-xs text-red-500 mt-1';


            actualizarPendientes();

        }

    }


    /*
    |--------------------------------------------------------------------------
    | ACTUALIZAR PENDIENTES
    |--------------------------------------------------------------------------
    */

    function actualizarPendientes() {

        const jugadorId =
            jugador.value;

        const esIngreso =
            tipo.value === 'Ingreso';


        /*
        |--------------------------------------------------------------------------
        | Ocultar si no es ingreso o no hay jugador
        |--------------------------------------------------------------------------
        */

        if (
            !esIngreso ||
            !jugadorId
        ) {

            bloquePendiente.classList.add(
                'hidden'
            );

            mensajePago.classList.add(
                'hidden'
            );

            selectCargo.value = '';

            return;

        }


        let cantidad = 0;


        /*
        |--------------------------------------------------------------------------
        | Mostrar únicamente los cargos de ese jugador
        |--------------------------------------------------------------------------
        */

        Array.from(
            selectCargo.options
        ).forEach(function (option, index) {

            /*
            |--------------------------------------------------------------------------
            | Primera opción
            |--------------------------------------------------------------------------
            */

            if (index === 0) {

                option.hidden = false;

                return;

            }


            const pertenece =
                option.dataset.jugador === jugadorId;


            option.hidden =
                !pertenece;


            if (pertenece) {

                cantidad++;

            }

        });


        /*
        |--------------------------------------------------------------------------
        | Mostrar bloque
        |--------------------------------------------------------------------------
        */

        bloquePendiente.classList.remove(
            'hidden'
        );


        /*
        |--------------------------------------------------------------------------
        | No tiene pendientes
        |--------------------------------------------------------------------------
        */

        if (cantidad === 0) {

            sinPendientes.classList.remove(
                'hidden'
            );

            selectCargo.disabled = true;

            selectCargo.value = '';

            mensajePago.classList.add(
                'hidden'
            );

        } else {

            sinPendientes.classList.add(
                'hidden'
            );

            selectCargo.disabled = false;

        }


        /*
        |--------------------------------------------------------------------------
        | Si solo tiene un pendiente,
        | seleccionarlo automáticamente
        |--------------------------------------------------------------------------
        */

        if (cantidad === 1) {

            let unicaOpcion = null;


            Array.from(
                selectCargo.options
            ).forEach(function (option, index) {

                if (
                    index > 0 &&
                    option.dataset.jugador === jugadorId
                ) {

                    unicaOpcion = option;

                }

            });


            if (unicaOpcion) {

                selectCargo.value =
                    unicaOpcion.value;

                aplicarCargo();

            }

        }

    }


    /*
    |--------------------------------------------------------------------------
    | APLICAR CARGO
    |--------------------------------------------------------------------------
    */

    function aplicarCargo() {

        const opcion =
            selectCargo.options[
                selectCargo.selectedIndex
            ];


        if (
            !opcion ||
            !opcion.value
        ) {

            mensajePago.classList.add(
                'hidden'
            );

            return;

        }


        const pendiente =
            parseFloat(
                opcion.dataset.pendiente || 0
            );


        /*
        |--------------------------------------------------------------------------
        | Cargar valor
        |--------------------------------------------------------------------------
        */

        valor.value =
            pendiente;


        /*
        |--------------------------------------------------------------------------
        | Cargar periodo
        |--------------------------------------------------------------------------
        */

        if (
            opcion.dataset.periodo
        ) {

            periodo.value =
                opcion.dataset.periodo;

        }


        /*
        |--------------------------------------------------------------------------
        | Mostrar confirmación
        |--------------------------------------------------------------------------
        */

        mensajePago.classList.remove(
            'hidden'
        );


        detallePago.textContent =
            'Se registrará un pago de $' +
            pendiente.toLocaleString(
                'es-CO'
            ) +
            ' para esta obligación.';


        mensajeValor.textContent =
            'Valor pendiente de esta obligación.';

    }


    /*
    |--------------------------------------------------------------------------
    | CAMBIO DE JUGADOR
    |--------------------------------------------------------------------------
    */

    jugadorBusqueda.addEventListener(
        'change',
        function () {

            buscarJugador();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | También buscar cuando el usuario escribe
    | y selecciona una opción del datalist
    |--------------------------------------------------------------------------
    */

    jugadorBusqueda.addEventListener(
        'input',
        function () {

            /*
            |--------------------------------------------------------------------------
            | Si el texto coincide exactamente con un jugador,
            | lo seleccionamos inmediatamente.
            |--------------------------------------------------------------------------
            */

            const texto =
                jugadorBusqueda.value
                    .trim()
                    .toLowerCase();


            let encontrado = null;


            Array.from(
                listaJugadores.options
            ).forEach(function (option) {

                if (
                    option.value
                        .trim()
                        .toLowerCase() === texto
                ) {

                    encontrado =
                        option.dataset.id;

                }

            });


            if (encontrado) {

                jugador.value =
                    encontrado;


                mensajeJugador.textContent =
                    '✓ Jugador seleccionado correctamente.';

                mensajeJugador.className =
                    'text-xs text-green-600 mt-1';


                actualizarPendientes();

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | CAMBIO DE TIPO
    |--------------------------------------------------------------------------
    */

    tipo.addEventListener(
        'change',
        function () {

            actualizarPendientes();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | CAMBIO DE PENDIENTE
    |--------------------------------------------------------------------------
    */

    selectCargo.addEventListener(
        'change',
        function () {

            aplicarCargo();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | CAMBIO DE CONCEPTO
    |--------------------------------------------------------------------------
    */

    concepto.addEventListener(
        'change',
        function () {

            /*
            |--------------------------------------------------------------------------
            | Si ya seleccionó una obligación,
            | el valor de esa obligación tiene prioridad.
            |--------------------------------------------------------------------------
            */

            if (
                selectCargo.value
            ) {

                return;

            }


            const opcion =
                concepto.options[
                    concepto.selectedIndex
                ];


            if (
                opcion &&
                opcion.dataset.valor
            ) {

                const valorPredeterminado =
                    parseFloat(
                        opcion.dataset.valor
                    );


                if (
                    valorPredeterminado > 0
                ) {

                    valor.value =
                        valorPredeterminado;


                    mensajeValor.textContent =
                        'Valor predeterminado del concepto. Puedes modificarlo.';

                }

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | INICIALIZAR
    |--------------------------------------------------------------------------
    */

    actualizarPendientes();


    /*
    |--------------------------------------------------------------------------
    | RECUPERAR DATOS SI HUBO ERROR DE VALIDACIÓN
    |--------------------------------------------------------------------------
    */

    @if(old('jugador_id'))

        jugador.value =
            '{{ old('jugador_id') }}';


        /*
        |--------------------------------------------------------------------------
        | Buscar nombre del jugador para volver a mostrarlo
        |--------------------------------------------------------------------------
        */

        Array.from(
            listaJugadores.options
        ).forEach(function (option) {

            if (
                option.dataset.id ===
                jugador.value
            ) {

                jugadorBusqueda.value =
                    option.value;

                mensajeJugador.textContent =
                    '✓ Jugador seleccionado correctamente.';

                mensajeJugador.className =
                    'text-xs text-green-600 mt-1';

            }

        });


        actualizarPendientes();

    @endif


    @if(old('cargo_jugador_id'))

        selectCargo.value =
            '{{ old('cargo_jugador_id') }}';


        aplicarCargo();

    @endif

});

</script>


@endsection
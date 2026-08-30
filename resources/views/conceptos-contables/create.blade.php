@extends('layouts.app')

@section('titulo', 'Nuevo concepto contable')

@section('contenido')

<x-page-header
    title="📚 Nuevo concepto contable"
    subtitle="Crea un concepto y, si corresponde, configura cómo se cobrará."
/>


@if($errors->any())

    <div class="mb-5 rounded-xl bg-red-50 border border-red-200
                text-red-700 px-4 py-3 text-sm">

        <div class="font-semibold mb-2">
            Revisa la información:
        </div>

        <ul class="list-disc ml-5">

            @foreach($errors->all() as $error)

                <li>{{ $error }}</li>

            @endforeach

        </ul>

    </div>

@endif


<div class="max-w-3xl mx-auto">

    <form
        method="POST"
        action="{{ route('conceptos-contables.store') }}"
    >

        @csrf


        <div class="bg-white rounded-xl shadow-lg overflow-hidden">


            {{-- =====================================================
                 INFORMACIÓN DEL CONCEPTO
            ====================================================== --}}

            <div class="bg-slate-800 text-white px-6 py-4">

                <h2 class="font-bold text-lg">
                    📚 Información del concepto
                </h2>

                <p class="text-xs text-slate-300 mt-1">
                    Define qué representa este concepto dentro del club.
                </p>

            </div>


            <div class="p-6 space-y-5">


                {{-- =================================================
                     NOMBRE
                ================================================== --}}

                <div>

                    <label
                        class="block text-sm font-semibold
                               text-gray-700 mb-1"
                    >
                        Nombre del concepto
                    </label>

                    <input
                        type="text"
                        name="nombre"
                        value="{{ old('nombre') }}"
                        required
                        maxlength="255"
                        placeholder="Ej. MENSUALIDAD"
                        class="w-full border border-gray-300
                               rounded-lg px-3 py-2.5
                               focus:ring-2 focus:ring-blue-500
                               focus:border-blue-500"
                    >

                    <p class="text-xs text-gray-400 mt-1">
                        Ejemplo: MENSUALIDAD, UNIFORME, INSCRIPCIÓN.
                    </p>

                </div>


                {{-- =================================================
                     TIPO
                ================================================== --}}

                <div>

                    <label
                        class="block text-sm font-semibold
                               text-gray-700 mb-1"
                    >
                        Tipo
                    </label>

                    <select
                        name="tipo"
                        id="tipo"
                        required
                        class="w-full border border-gray-300
                               rounded-lg px-3 py-2.5
                               bg-white
                               focus:ring-2 focus:ring-blue-500
                               focus:border-blue-500"
                    >

                        <option value="">
                            Seleccione un tipo
                        </option>

                        <option
                            value="Ingreso"
                            @selected(old('tipo') === 'Ingreso')
                        >
                            💰 Ingreso
                        </option>

                        <option
                            value="Egreso"
                            @selected(old('tipo') === 'Egreso')
                        >
                            💸 Egreso
                        </option>

                    </select>

                    <p class="text-xs text-gray-400 mt-1">
                        Solo los conceptos de ingreso pueden generar cobros.
                    </p>

                </div>


                {{-- =================================================
                     VALOR PREDETERMINADO
                ================================================== --}}

                <div>

                    <label
                        class="block text-sm font-semibold
                               text-gray-700 mb-1"
                    >
                        Valor predeterminado
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
                            name="valor_predeterminado"
                            value="{{ old('valor_predeterminado') }}"
                            min="0"
                            step="1"
                            placeholder="20000"
                            class="w-full border border-gray-300
                                   rounded-lg pl-8 pr-3 py-2.5
                                   focus:ring-2 focus:ring-blue-500
                                   focus:border-blue-500"
                        >

                    </div>

                    <p class="text-xs text-gray-400 mt-1">
                        Valor de referencia para movimientos manuales.
                    </p>

                </div>


                {{-- =================================================
                     CONFIGURACIÓN DE COBRO
                ================================================== --}}

                <div
                    id="configuracionCobro"
                    class="hidden rounded-xl
                           border border-blue-200
                           bg-blue-50 overflow-hidden"
                >

                    <div
                        class="px-5 py-4
                               border-b border-blue-200"
                    >

                        <h3
                            class="font-bold text-blue-900"
                        >
                            ⚙️ Configuración de cobro
                        </h3>

                        <p
                            class="text-xs text-blue-700 mt-1"
                        >
                            Define si este concepto genera obligaciones
                            automáticamente para los jugadores.
                        </p>

                    </div>


                    <div class="p-5 space-y-5">


                        {{-- GENERA COBRO --}}

                        <div
                            class="rounded-xl bg-white
                                   border border-blue-100
                                   p-4"
                        >

                            <label
                                class="flex items-center
                                       gap-3 cursor-pointer"
                            >

                                <input
                                    type="checkbox"
                                    name="genera_cobro"
                                    id="generaCobro"
                                    value="1"
                                    @checked(
                                        old('genera_cobro')
                                    )
                                    class="w-5 h-5 text-blue-600
                                           border-gray-300 rounded
                                           focus:ring-blue-500"
                                >

                                <div>

                                    <div
                                        class="font-semibold
                                               text-gray-800"
                                    >
                                        Este concepto genera cobros
                                    </div>

                                    <div
                                        class="text-xs
                                               text-gray-500"
                                    >
                                        Al activarlo podrás generar
                                        obligaciones para los jugadores.
                                    </div>

                                </div>

                            </label>

                        </div>


                        {{-- CAMPOS DE COBRO --}}

                        <div
                            id="camposCobro"
                            class="hidden space-y-5"
                        >


                            {{-- TIPO DE COBRO --}}

                            <div>

                                <label
                                    class="block text-sm
                                           font-semibold
                                           text-gray-700 mb-1"
                                >
                                    Tipo de cobro
                                </label>

                                <select
                                    name="tipo_cobro"
                                    id="tipoCobro"
                                    class="w-full border
                                           border-gray-300
                                           rounded-lg px-3 py-2.5
                                           bg-white"
                                >

                                    <option value="">
                                        Seleccione...
                                    </option>

                                    <option
                                        value="Mensual"
                                        @selected(
                                            old('tipo_cobro')
                                            === 'Mensual'
                                        )
                                    >
                                        📅 Mensual
                                    </option>

                                    <option
                                        value="Unico"
                                        @selected(
                                            old('tipo_cobro')
                                            === 'Unico'
                                        )
                                    >
                                        📌 Único
                                    </option>

                                </select>

                            </div>


                            {{-- VALOR DEL COBRO --}}

                            <div>

                                <label
                                    class="block text-sm
                                           font-semibold
                                           text-gray-700 mb-1"
                                >
                                    Valor del cobro
                                </label>

                                <div class="relative">

                                    <span
                                        class="absolute left-3
                                               top-1/2
                                               -translate-y-1/2
                                               text-gray-500
                                               font-semibold"
                                    >
                                        $
                                    </span>

                                    <input
                                        type="number"
                                        name="valor_cobro"
                                        value="{{ old(
                                            'valor_cobro'
                                        ) }}"
                                        min="1"
                                        step="1"
                                        placeholder="20000"
                                        class="w-full border
                                               border-gray-300
                                               rounded-lg
                                               pl-8 pr-3 py-2.5"
                                    >

                                </div>

                            </div>


                            {{-- COBRO MENSUAL --}}

                            <div
                                id="campoMensual"
                                class="hidden"
                            >

                                <label
                                    class="block text-sm
                                           font-semibold
                                           text-gray-700 mb-1"
                                >
                                    Día de cobro mensual
                                </label>

                                <select
                                    name="dia_cobro"
                                    class="w-full border
                                           border-gray-300
                                           rounded-lg
                                           px-3 py-2.5
                                           bg-white"
                                >

                                    <option value="">
                                        Seleccione el día
                                    </option>

                                    @for($dia = 1; $dia <= 31; $dia++)

                                        <option
                                            value="{{ $dia }}"
                                            @selected(
                                                old('dia_cobro')
                                                == $dia
                                            )
                                        >
                                            Día {{ $dia }}
                                        </option>

                                    @endfor

                                </select>

                                <p
                                    class="text-xs text-gray-500 mt-1"
                                >
                                    Ese día se podrá generar el cobro
                                    de cada mes.
                                </p>

                            </div>


                            {{-- COBRO ÚNICO --}}

                            <div
                                id="campoUnico"
                                class="hidden"
                            >

                                <label
                                    class="block text-sm
                                           font-semibold
                                           text-gray-700 mb-1"
                                >
                                    Fecha máxima de cobro
                                </label>

                                <input
                                    type="date"
                                    name="fecha_maxima"
                                    value="{{ old(
                                        'fecha_maxima'
                                    ) }}"
                                    class="w-full border
                                           border-gray-300
                                           rounded-lg
                                           px-3 py-2.5"
                                >

                                <p
                                    class="text-xs text-gray-500 mt-1"
                                >
                                    Fecha límite para esta obligación.
                                </p>

                            </div>


                            {{-- FECHA DE INICIO --}}

                            <div>

                                <label
                                    class="block text-sm
                                           font-semibold
                                           text-gray-700 mb-1"
                                >
                                    Fecha de inicio
                                </label>

                                <input
                                    type="date"
                                    name="fecha_inicio"
                                    value="{{ old(
                                        'fecha_inicio'
                                    ) }}"
                                    class="w-full border
                                           border-gray-300
                                           rounded-lg
                                           px-3 py-2.5"
                                >

                                <p
                                    class="text-xs text-gray-500 mt-1"
                                >
                                    Desde cuándo estará disponible este cobro.
                                </p>

                            </div>


                        </div>

                    </div>

                </div>


                {{-- =================================================
                     DESCRIPCIÓN
                ================================================== --}}

                <div>

                    <label
                        class="block text-sm font-semibold
                               text-gray-700 mb-1"
                    >
                        Descripción
                    </label>

                    <textarea
                        name="descripcion"
                        rows="3"
                        placeholder="Descripción opcional del concepto..."
                        class="w-full border border-gray-300
                               rounded-lg px-3 py-2.5
                               focus:ring-2 focus:ring-blue-500
                               focus:border-blue-500"
                    >{{ old('descripcion') }}</textarea>

                </div>


                {{-- =================================================
                     ACTIVO
                ================================================== --}}

                <div
                    class="rounded-xl bg-gray-50
                           border border-gray-200 p-4"
                >

                    <label
                        class="flex items-center gap-3
                               cursor-pointer"
                    >

                        <input
                            type="checkbox"
                            name="activo"
                            value="1"
                            @checked(
                                old('activo', true)
                            )
                            class="w-4 h-4 text-blue-600
                                   border-gray-300 rounded
                                   focus:ring-blue-500"
                        >

                        <div>

                            <div
                                class="text-sm
                                       font-semibold
                                       text-gray-700"
                            >
                                Concepto activo
                            </div>

                            <div
                                class="text-xs text-gray-500"
                            >
                                Podrá utilizarse en nuevos registros.
                            </div>

                        </div>

                    </label>

                </div>


            </div>


            {{-- =====================================================
                 INFORMACIÓN
            ====================================================== --}}

            <div
                class="mx-6 mb-6 rounded-xl
                       bg-blue-50 border border-blue-200
                       px-4 py-3"
            >

                <div class="flex gap-3">

                    <div class="text-lg">
                        ℹ️
                    </div>

                    <div>

                        <div
                            class="font-semibold
                                   text-blue-800 text-sm"
                        >
                            ¿Cómo funciona?
                        </div>

                        <div
                            class="text-xs
                                   text-blue-700 mt-1"
                        >
                            Si activas la configuración de cobro,
                            este concepto podrá generar obligaciones
                            para los jugadores. Luego podrás generar
                            los cargos del periodo desde el concepto.
                        </div>

                    </div>

                </div>

            </div>


            {{-- =====================================================
                 BOTONES
            ====================================================== --}}

            <div
                class="border-t bg-gray-50 px-6 py-4
                       flex justify-end gap-3"
            >

                <a
                    href="{{ route(
                        'conceptos-contables.index'
                    ) }}"
                    class="px-5 py-2 rounded-lg
                           bg-gray-200 hover:bg-gray-300
                           text-gray-700 text-sm
                           font-semibold transition"
                >
                    Cancelar
                </a>


                <button
                    type="submit"
                    class="px-5 py-2 rounded-lg
                           bg-blue-600 hover:bg-blue-700
                           text-white text-sm
                           font-semibold transition"
                >

                    💾 Guardar concepto

                </button>

            </div>

        </div>

    </form>

</div>


{{-- =========================================================
     JAVASCRIPT
========================================================= --}}

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const tipo =
            document.getElementById('tipo');

        const configuracionCobro =
            document.getElementById(
                'configuracionCobro'
            );

        const generaCobro =
            document.getElementById(
                'generaCobro'
            );

        const camposCobro =
            document.getElementById(
                'camposCobro'
            );

        const tipoCobro =
            document.getElementById(
                'tipoCobro'
            );

        const campoMensual =
            document.getElementById(
                'campoMensual'
            );

        const campoUnico =
            document.getElementById(
                'campoUnico'
            );


        /*
        |--------------------------------------------------------------------------
        | MOSTRAR CONFIGURACIÓN SOLO PARA INGRESOS
        |--------------------------------------------------------------------------
        */

        function actualizarTipo()
        {
            if (
                tipo.value === 'Ingreso'
            ) {

                configuracionCobro
                    .classList
                    .remove('hidden');

            } else {

                configuracionCobro
                    .classList
                    .add('hidden');

                generaCobro.checked =
                    false;

                camposCobro
                    .classList
                    .add('hidden');

            }

            actualizarGeneraCobro();
        }


        /*
        |--------------------------------------------------------------------------
        | MOSTRAR CAMPOS DE COBRO
        |--------------------------------------------------------------------------
        */

        function actualizarGeneraCobro()
        {
            if (
                generaCobro.checked &&
                tipo.value === 'Ingreso'
            ) {

                camposCobro
                    .classList
                    .remove('hidden');

            } else {

                camposCobro
                    .classList
                    .add('hidden');

            }

            actualizarTipoCobro();
        }


        /*
        |--------------------------------------------------------------------------
        | MENSUAL / ÚNICO
        |--------------------------------------------------------------------------
        */

        function actualizarTipoCobro()
        {
            campoMensual
                .classList
                .add('hidden');

            campoUnico
                .classList
                .add('hidden');


            if (
                !generaCobro.checked
            ) {

                return;

            }


            if (
                tipoCobro.value === 'Mensual'
            ) {

                campoMensual
                    .classList
                    .remove('hidden');

            }


            if (
                tipoCobro.value === 'Unico'
            ) {

                campoUnico
                    .classList
                    .remove('hidden');

            }
        }


        tipo.addEventListener(
            'change',
            actualizarTipo
        );


        generaCobro.addEventListener(
            'change',
            actualizarGeneraCobro
        );


        tipoCobro.addEventListener(
            'change',
            actualizarTipoCobro
        );


        /*
        |--------------------------------------------------------------------------
        | INICIALIZAR
        |--------------------------------------------------------------------------
        */

        actualizarTipo();

    }
);

</script>

@endsection
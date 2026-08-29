@extends('layouts.app')

@section('titulo', 'Editar concepto contable')

@section('contenido')

<x-page-header
    title="✏️ Editar concepto contable"
    subtitle="Modifica la configuración del concepto seleccionado."
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
        action="{{ route(
            'conceptos-contables.update',
            $conceptoContable
        ) }}"
    >

        @csrf

        @method('PUT')


        <div class="bg-white rounded-xl shadow-lg overflow-hidden">


            {{-- ENCABEZADO --}}

            <div class="bg-slate-800 text-white px-6 py-4">

                <div class="flex items-center gap-3">

                    <div class="w-9 h-9 rounded-lg bg-white/10
                                flex items-center justify-center">

                        ✏️

                    </div>

                    <div>

                        <h2 class="font-bold text-lg">
                            {{ $conceptoContable->nombre }}
                        </h2>

                        <p class="text-xs text-slate-300 mt-1">
                            Configuración del concepto contable.
                        </p>

                    </div>

                </div>

            </div>


            {{-- FORMULARIO --}}

            <div class="p-6 space-y-5">


                {{-- NOMBRE --}}

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
                        value="{{ old(
                            'nombre',
                            $conceptoContable->nombre
                        ) }}"
                        required
                        maxlength="255"
                        class="w-full border border-gray-300
                               rounded-lg px-3 py-2.5
                               focus:ring-2 focus:ring-blue-500
                               focus:border-blue-500"
                    >

                </div>


                {{-- TIPO --}}

                <div>

                    <label
                        class="block text-sm font-semibold
                               text-gray-700 mb-1"
                    >

                        Tipo

                    </label>


                    <select
                        name="tipo"
                        required
                        class="w-full border border-gray-300
                               rounded-lg px-3 py-2.5
                               bg-white
                               focus:ring-2 focus:ring-blue-500
                               focus:border-blue-500"
                    >

                        <option
                            value="Ingreso"
                            @selected(
                                old(
                                    'tipo',
                                    $conceptoContable->tipo
                                ) === 'Ingreso'
                            )
                        >

                            💰 Ingreso

                        </option>


                        <option
                            value="Egreso"
                            @selected(
                                old(
                                    'tipo',
                                    $conceptoContable->tipo
                                ) === 'Egreso'
                            )
                        >

                            💸 Egreso

                        </option>

                    </select>

                </div>


                {{-- VALOR PREDETERMINADO --}}

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
                            value="{{ old(
                                'valor_predeterminado',
                                $conceptoContable->valor_predeterminado
                            ) }}"
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

                        Este valor se utilizará como referencia
                        al crear nuevos cargos.

                    </p>

                </div>


                {{-- DESCRIPCIÓN --}}

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
                        placeholder="Descripción opcional..."
                        class="w-full border border-gray-300
                               rounded-lg px-3 py-2.5
                               focus:ring-2 focus:ring-blue-500
                               focus:border-blue-500"
                    >{{ old(
                        'descripcion',
                        $conceptoContable->descripcion
                    ) }}</textarea>

                </div>


                {{-- ESTADO --}}

                <div class="rounded-xl bg-gray-50
                            border border-gray-200 p-4">

                    <label
                        class="flex items-center gap-3 cursor-pointer"
                    >

                        <input
                            type="checkbox"
                            name="activo"
                            value="1"
                            @checked(
                                old(
                                    'activo',
                                    $conceptoContable->activo
                                )
                            )
                            class="w-4 h-4 text-blue-600
                                   border-gray-300 rounded
                                   focus:ring-blue-500"
                        >


                        <div>

                            <div class="text-sm font-semibold text-gray-700">

                                Concepto activo

                            </div>

                            <div class="text-xs text-gray-500">

                                Los conceptos inactivos no aparecerán
                                para nuevos registros.

                            </div>

                        </div>

                    </label>

                </div>

            </div>


            {{-- INFORMACIÓN HISTÓRICA --}}

            <div class="mx-6 mb-6 rounded-xl
                        bg-amber-50 border border-amber-200
                        px-4 py-3">

                <div class="flex gap-3">

                    <div class="text-lg">
                        ⚠️
                    </div>

                    <div>

                        <div class="font-semibold text-amber-800 text-sm">

                            Importante

                        </div>

                        <div class="text-xs text-amber-700 mt-1">

                            Cambiar el valor predeterminado no modifica
                            los cargos que ya fueron creados. El nuevo
                            valor se utilizará únicamente para cargos futuros.

                        </div>

                    </div>

                </div>

            </div>


            {{-- BOTONES --}}

            <div class="border-t bg-gray-50 px-6 py-4
                        flex justify-end gap-3">

                <a
                    href="{{ route('conceptos-contables.index') }}"
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

                    💾 Guardar cambios

                </button>

            </div>

        </div>

    </form>

</div>

@endsection
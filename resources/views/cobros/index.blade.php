@extends('layouts.app')

@section('titulo', 'Cobros')

@section('contenido')

<x-page-header
    title="💰 Cobros"
    subtitle="Configura las obligaciones que el club cobra a sus jugadores."
/>


{{-- =========================================================
     MENSAJES
========================================================= --}}

@if(session('success'))

    <div class="mb-5 rounded-xl bg-green-50 border border-green-200
                text-green-700 px-4 py-3 text-sm">

        {{ session('success') }}

    </div>

@endif


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


{{-- =========================================================
     ACCIONES
========================================================= --}}

<div class="flex justify-end mb-5">

    <a href="{{ route('cobros.create') }}">

        <x-button color="green">
            ➕ Nuevo cobro
        </x-button>

    </a>

</div>


{{-- =========================================================
     LISTADO
========================================================= --}}

<div class="bg-white rounded-xl shadow-sm
            border border-gray-200 overflow-hidden">

    <div class="px-5 py-4 bg-gray-50 border-b">

        <div class="flex items-center justify-between">

            <div>

                <h2 class="font-bold text-gray-800">

                    Configuración de cobros

                </h2>

                <p class="text-xs text-gray-500 mt-1">

                    Desde aquí defines qué obligaciones
                    se pueden generar para los jugadores.

                </p>

            </div>


            <span
                class="px-3 py-1 rounded-full
                       bg-gray-200 text-gray-700
                       text-xs font-semibold"
            >

                {{ $cobros->count() }}

                {{ $cobros->count() == 1
                    ? 'cobro'
                    : 'cobros' }}

            </span>

        </div>

    </div>


    <div class="p-4">

        <div class="overflow-x-auto border rounded-xl">

            <table class="min-w-full text-sm">

                <thead class="bg-gray-100">

                    <tr>

                        <th class="px-4 py-3 text-left">
                            Concepto
                        </th>

                        <th class="px-4 py-3 text-center">
                            Tipo
                        </th>

                        <th class="px-4 py-3 text-right">
                            Valor
                        </th>

                        <th class="px-4 py-3 text-center">
                            Configuración
                        </th>

                        <th class="px-4 py-3 text-center">
                            Estado
                        </th>

                        <th class="px-4 py-3 text-center">
                            Acciones
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($cobros as $cobro)

                        <tr
                            class="border-t
                                   hover:bg-gray-50
                                   transition"
                        >


                            {{-- CONCEPTO --}}

                            <td class="px-4 py-4">

                                <div
                                    class="font-semibold
                                           text-gray-800"
                                >

                                    {{ $cobro->concepto?->nombre
                                        ?? 'Sin concepto' }}

                                </div>


                                @if(
                                    $cobro->concepto?->descripcion
                                )

                                    <div
                                        class="text-xs
                                               text-gray-500 mt-1"
                                    >

                                        {{ $cobro->concepto->descripcion }}

                                    </div>

                                @endif

                            </td>


                            {{-- TIPO --}}

                            <td class="px-4 py-4 text-center">

                                @if($cobro->tipo === 'Mensual')

                                    <span
                                        class="inline-flex
                                               px-2.5 py-1
                                               rounded-full
                                               bg-blue-100
                                               text-blue-700
                                               text-xs
                                               font-semibold"
                                    >

                                        📅 Mensual

                                    </span>

                                @else

                                    <span
                                        class="inline-flex
                                               px-2.5 py-1
                                               rounded-full
                                               bg-purple-100
                                               text-purple-700
                                               text-xs
                                               font-semibold"
                                    >

                                        📌 Único

                                    </span>

                                @endif

                            </td>


                            {{-- VALOR --}}

                            <td
                                class="px-4 py-4
                                       text-right
                                       font-bold
                                       whitespace-nowrap"
                            >

                                ${{ number_format(
                                    $cobro->valor,
                                    0,
                                    ',',
                                    '.'
                                ) }}

                            </td>


                            {{-- CONFIGURACIÓN --}}

                            <td
                                class="px-4 py-4
                                       text-center"
                            >

                                @if($cobro->tipo === 'Mensual')

                                    <div
                                        class="text-xs
                                               text-gray-600"
                                    >

                                        Día de cobro

                                    </div>

                                    <div
                                        class="font-semibold
                                               text-gray-800"
                                    >

                                        {{ $cobro->dia_cobro }}

                                    </div>

                                @else

                                    <div
                                        class="text-xs
                                               text-gray-600"
                                    >

                                        Fecha máxima

                                    </div>

                                    <div
                                        class="font-semibold
                                               text-gray-800"
                                    >

                                        {{ $cobro->fecha_maxima
                                            ? $cobro->fecha_maxima
                                                ->format('d/m/Y')
                                            : '—' }}

                                    </div>

                                @endif

                            </td>


                            {{-- ESTADO --}}

                            <td class="px-4 py-4 text-center">

                                @if($cobro->activo)

                                    <span
                                        class="inline-flex
                                               px-2.5 py-1
                                               rounded-full
                                               bg-green-100
                                               text-green-700
                                               text-xs
                                               font-semibold"
                                    >

                                        ● Activo

                                    </span>

                                @else

                                    <span
                                        class="inline-flex
                                               px-2.5 py-1
                                               rounded-full
                                               bg-gray-100
                                               text-gray-500
                                               text-xs
                                               font-semibold"
                                    >

                                        ● Inactivo

                                    </span>

                                @endif

                            </td>


                            {{-- ACCIONES --}}

                            <td class="px-4 py-4">

                                <div
                                    class="flex items-center
                                           justify-center gap-2"
                                >


                                    {{-- GENERAR --}}

                                    @if($cobro->activo)

                                        <button
                                            type="button"
                                            onclick="abrirGenerar(
                                                {{ $cobro->id }},
                                                '{{ addslashes(
                                                    $cobro->concepto?->nombre
                                                ) }}'
                                            )"
                                            title="Generar cobros"
                                            class="w-9 h-9
                                                   inline-flex
                                                   items-center
                                                   justify-center
                                                   rounded-lg
                                                   bg-green-50
                                                   text-green-600
                                                   hover:bg-green-100"
                                        >

                                            💳

                                        </button>

                                    @endif


                                    {{-- EDITAR --}}

                                    <a
                                        href="{{ route(
                                            'cobros.edit',
                                            $cobro
                                        ) }}"
                                        title="Editar cobro"
                                        class="w-9 h-9
                                               inline-flex
                                               items-center
                                               justify-center
                                               rounded-lg
                                               bg-blue-50
                                               text-blue-600
                                               hover:bg-blue-100"
                                    >

                                        ✏️

                                    </a>


                                    {{-- ACTIVAR / DESACTIVAR --}}

                                    <form
                                        method="POST"
                                        action="{{ route(
                                            'cobros.toggle',
                                            $cobro
                                        ) }}"
                                    >

                                        @csrf

                                        <button
                                            type="submit"
                                            title="{{ $cobro->activo
                                                ? 'Desactivar'
                                                : 'Activar' }}"
                                            class="w-9 h-9
                                                   inline-flex
                                                   items-center
                                                   justify-center
                                                   rounded-lg
                                                   {{ $cobro->activo
                                                       ? 'bg-red-50 text-red-600 hover:bg-red-100'
                                                       : 'bg-green-50 text-green-600 hover:bg-green-100' }}"
                                        >

                                            {{ $cobro->activo
                                                ? '⏸️'
                                                : '▶️' }}

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="px-4 py-12
                                       text-center
                                       text-gray-500"
                            >

                                <div class="text-4xl mb-3">
                                    💰
                                </div>

                                <div
                                    class="font-semibold
                                           text-gray-700"
                                >

                                    No hay cobros configurados

                                </div>

                                <div class="text-xs mt-1">

                                    Crea el primer cobro para
                                    comenzar a generar obligaciones.

                                </div>


                                <div class="mt-4">

                                    <a
                                        href="{{ route(
                                            'cobros.create'
                                        ) }}"
                                    >

                                        <x-button color="green">

                                            ➕ Crear primer cobro

                                        </x-button>

                                    </a>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


{{-- =========================================================
     MODAL GENERAR COBROS
========================================================= --}}

<div
    id="modalGenerar"
    class="hidden fixed inset-0 z-50
           bg-black/50
           items-center justify-center
           p-4"
>

    <div
        class="bg-white rounded-2xl
               shadow-xl
               w-full max-w-md"
    >

        <div class="px-6 py-5 border-b">

            <h3
                id="tituloGenerar"
                class="text-lg font-bold text-gray-800"
            >

                Generar cobros

            </h3>

            <p class="text-xs text-gray-500 mt-1">

                Selecciona el periodo para generar
                las obligaciones.

            </p>

        </div>


        <form
            id="formGenerar"
            method="POST"
            action=""
        >

            @csrf

            <div class="p-6">


                <label
                    class="block text-sm
                           font-semibold
                           text-gray-700 mb-2"
                >

                    Periodo

                </label>


                <input
                    type="month"
                    name="periodo"
                    value="{{ date('Y-m') }}"
                    required
                    class="w-full border
                           border-gray-300
                           rounded-lg
                           px-3 py-2"
                >


                <div
                    class="mt-4 rounded-lg
                           bg-yellow-50
                           border border-yellow-200
                           px-4 py-3
                           text-xs
                           text-yellow-800"
                >

                    ⚠️ El sistema generará el cobro
                    para los jugadores activos que aún
                    no tengan esta obligación en el
                    periodo seleccionado.

                </div>

            </div>


            <div
                class="px-6 py-4
                       bg-gray-50
                       border-t
                       flex justify-end gap-2"
            >

                <button
                    type="button"
                    onclick="cerrarGenerar()"
                    class="px-4 py-2
                           rounded-lg
                           bg-gray-200
                           hover:bg-gray-300
                           text-gray-700
                           text-sm
                           font-semibold"
                >

                    Cancelar

                </button>


                <button
                    type="submit"
                    class="px-4 py-2
                           rounded-lg
                           bg-green-600
                           hover:bg-green-700
                           text-white
                           text-sm
                           font-semibold"
                >

                    💳 Generar cobros

                </button>

            </div>

        </form>

    </div>

</div>


{{-- =========================================================
     JAVASCRIPT MODAL
========================================================= --}}

<script>

function abrirGenerar(id, nombre)
{
    const modal =
        document.getElementById('modalGenerar');

    const form =
        document.getElementById('formGenerar');

    const titulo =
        document.getElementById('tituloGenerar');


    titulo.textContent =
        'Generar: ' + nombre;


    form.action =
        '/cobros/' + id + '/generar';


    modal.classList.remove('hidden');

    modal.classList.add('flex');
}


function cerrarGenerar()
{
    const modal =
        document.getElementById('modalGenerar');

    modal.classList.add('hidden');

    modal.classList.remove('flex');
}


document
    .getElementById('modalGenerar')
    .addEventListener(
        'click',
        function (event) {

            if (
                event.target === this
            ) {

                cerrarGenerar();

            }

        }
    );

</script>

@endsection
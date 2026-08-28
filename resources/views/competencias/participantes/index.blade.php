@extends('layouts.app')

@section('titulo', 'Planilla de participantes')

@section('contenido')

<x-page-header
    title="👥 Planilla de participantes"
    subtitle="Selecciona los jugadores que participarán en esta competencia."
/>


{{-- ========================================================= --}}
{{-- CABECERA --}}
{{-- ========================================================= --}}

<div class="mb-5 flex justify-between items-center">

    <a href="{{ route('competencias.show', $competencia) }}"
       class="bg-gray-200 hover:bg-gray-300 px-5 py-2 rounded-lg">

        ← Volver a la competencia

    </a>


    <button
        type="button"
        onclick="abrirExportacion()"
        class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg">

        📤 Exportar planilla

    </button>

</div>



{{-- ========================================================= --}}
{{-- INFORMACIÓN DE LA COMPETENCIA --}}
{{-- ========================================================= --}}

<x-card class="mb-6">

    <div class="grid grid-cols-1 md:grid-cols-4 gap-5">

        <div>

            <p class="text-sm text-gray-500">
                Competencia
            </p>

            <p class="font-semibold">
                {{ $competencia->nombre }}
            </p>

        </div>


        <div>

            <p class="text-sm text-gray-500">
                Tipo
            </p>

            <p class="font-semibold">
                {{ ucfirst($competencia->tipo) }}
            </p>

        </div>


        <div>

            <p class="text-sm text-gray-500">
                Categoría principal
            </p>

            <p class="font-semibold">
                {{ $competencia->categoria?->nombre ?? 'Todas' }}
            </p>

        </div>


        <div>

            <p class="text-sm text-gray-500">
                Participantes
            </p>

            <p class="font-semibold">
                {{ $competencia->jugadores->count() }}
            </p>

        </div>

    </div>

</x-card>



{{-- ========================================================= --}}
{{-- FORMULARIO PRINCIPAL --}}
{{-- ========================================================= --}}

<form
    method="POST"
    action="{{ route('competencias.participantes.store', $competencia) }}">

    @csrf



    {{-- ===================================================== --}}
    {{-- JUGADORES DE LA CATEGORÍA PRINCIPAL --}}
    {{-- ===================================================== --}}

    <x-card class="mb-6">

        <div class="flex justify-between items-center mb-5">

            <div>

                <h2 class="text-lg font-semibold">
                    👥 Jugadores de la categoría
                </h2>

                <p class="text-sm text-gray-500">
                    Selecciona los jugadores que participarán en la competencia.
                </p>

            </div>


            @if($competencia->categoria)

                <span
                    class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-sm">

                    {{ $competencia->categoria->nombre }}

                </span>

            @endif

        </div>



        @if($jugadoresCategoria->count())

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead>

                        <tr class="border-b text-left">

                            <th class="py-3 w-12">
                                Seleccionar
                            </th>

                            <th class="py-3">
                                Jugador
                            </th>

                            <th class="py-3">
                                Documento
                            </th>

                            <th class="py-3">
                                Categoría
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach($jugadoresCategoria as $jugador)

                            <tr class="border-b hover:bg-slate-50">

                                <td class="py-3">

                                    <input
                                        type="checkbox"
                                        name="jugadores[]"
                                        value="{{ $jugador->id }}"
                                        class="w-5 h-5"
                                        @checked(
                                            in_array(
                                                $jugador->id,
                                                $participantesIds
                                            )
                                        )
                                    >

                                </td>


                                <td class="py-3 font-medium">

                                    {{ $jugador->nombres }}
                                    {{ $jugador->apellidos }}

                                </td>


                                <td class="py-3">

                                    {{ $jugador->documento ?? '—' }}

                                </td>


                                <td class="py-3">

                                    {{ $jugador->categoria?->nombre ?? '—' }}

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="text-center py-8 text-gray-500">

                <div class="text-4xl mb-3">
                    👥
                </div>

                <p>
                    No hay jugadores registrados en esta categoría.
                </p>

            </div>

        @endif

    </x-card>



    {{-- ===================================================== --}}
    {{-- REFUERZOS --}}
    {{-- ===================================================== --}}

    <x-card class="mb-6">

        <div class="mb-5">

            <h2 class="text-lg font-semibold">
                🔵 Refuerzos de otras categorías
            </h2>

            <p class="text-sm text-gray-500">
                Puedes seleccionar jugadores de otras categorías para reforzar
                esta competencia.
            </p>

        </div>



        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            <div>

                <label class="font-medium">
                    Categoría
                </label>


                <select
                    id="categoria_refuerzo"
                    class="w-full mt-2 border rounded-lg px-4 py-2">

                    <option value="">
                        Seleccione una categoría
                    </option>


                    @foreach($categorias as $categoria)

                        @if(
                            !$competencia->categoria_id ||
                            $categoria->id != $competencia->categoria_id
                        )

                            <option value="{{ $categoria->id }}">

                                {{ $categoria->nombre }}

                            </option>

                        @endif

                    @endforeach

                </select>

            </div>



            <div>

                <label class="font-medium">
                    Jugador
                </label>


                <select
                    id="jugador_refuerzo"
                    class="w-full mt-2 border rounded-lg px-4 py-2">

                    <option value="">
                        Primero seleccione una categoría
                    </option>

                </select>

            </div>

        </div>



        <div class="mt-5">

            <button
                type="button"
                id="agregarRefuerzo"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg">

                + Agregar refuerzo

            </button>

        </div>



        {{-- REFUERZOS SELECCIONADOS --}}

        <div class="mt-6">

            <h3 class="font-medium mb-3">
                Refuerzos seleccionados
            </h3>


            <div id="listaRefuerzos">

                @foreach(
                    $competencia->jugadores
                        ->where('pivot.es_refuerzo', true)
                    as $jugador
                )

                    <div
                        class="flex justify-between items-center border rounded-lg px-4 py-3 mb-2 bg-blue-50"
                        data-jugador="{{ $jugador->id }}"
                        id="refuerzo_{{ $jugador->id }}"
                    >

                        <div>

                            <span class="font-medium">

                                {{ $jugador->nombres }}
                                {{ $jugador->apellidos }}

                            </span>


                            <span class="text-sm text-gray-500 ml-2">

                                {{ $jugador->categoria?->nombre ?? '—' }}

                            </span>

                        </div>


                        <button
                            type="button"
                            class="text-red-600 hover:text-red-800 eliminar-refuerzo">

                            Quitar

                        </button>

                    </div>

                @endforeach

            </div>

        </div>

    </x-card>



    {{-- ===================================================== --}}
    {{-- PARTICIPANTES ACTUALES --}}
    {{-- ===================================================== --}}

    <x-card class="mb-6">

        <h2 class="text-lg font-semibold mb-5">
            📋 Participantes seleccionados
        </h2>


        @if($competencia->jugadores->count())

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead>

                        <tr class="border-b text-left">

                            <th class="py-3">
                                Jugador
                            </th>

                            <th class="py-3">
                                Categoría
                            </th>

                            <th class="py-3">
                                Participación
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach($competencia->jugadores as $jugador)

                            <tr class="border-b">

                                <td class="py-3 font-medium">

                                    {{ $jugador->nombres }}
                                    {{ $jugador->apellidos }}

                                </td>


                                <td class="py-3">

                                    {{ $jugador->categoria?->nombre ?? '—' }}

                                </td>


                                <td class="py-3">

                                    @if($jugador->pivot->es_refuerzo)

                                        <span
                                            class="px-2 py-1 rounded bg-blue-100 text-blue-700">

                                            🔵 Refuerzo

                                        </span>

                                    @else

                                        <span
                                            class="px-2 py-1 rounded bg-green-100 text-green-700">

                                            Categoría

                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <p class="text-gray-500">

                Todavía no hay participantes guardados.

            </p>

        @endif

    </x-card>



    {{-- ===================================================== --}}
    {{-- BOTONES --}}
    {{-- ===================================================== --}}

    <div class="flex justify-end gap-3">

        <a
            href="{{ route('competencias.show', $competencia) }}"
            class="bg-gray-200 hover:bg-gray-300 px-5 py-2 rounded-lg">

            Cancelar

        </a>


        <button
            type="submit"
            class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg">

            💾 Guardar planilla

        </button>

    </div>

</form>



{{-- ========================================================= --}}
{{-- MODAL DE EXPORTACIÓN --}}
{{-- ========================================================= --}}

<div
    id="modalExportacion"
    class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">

    <div
        class="bg-white rounded-xl shadow-xl w-full max-w-3xl max-h-[90vh] overflow-y-auto">


        {{-- CABECERA DEL MODAL --}}

        <div class="flex justify-between items-center px-6 py-4 border-b">

            <div>

                <h2 class="text-xl font-semibold">
                    📤 Exportar planilla
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Selecciona la información que deseas incluir.
                </p>

            </div>


            <button
                type="button"
                onclick="cerrarExportacion()"
                class="text-gray-500 hover:text-gray-800 text-2xl">

                ×

            </button>

        </div>



        {{-- FORMULARIO DE EXPORTACIÓN --}}

        <form
            id="formExportacion"
            method="GET"
            action="#">

            <div class="p-6">


                {{-- ========================================= --}}
                {{-- DATOS DEL JUGADOR --}}
                {{-- ========================================= --}}

                <div class="mb-7">

                    <div class="mb-4">

                        <h3 class="font-semibold text-lg">
                            👤 Datos del jugador
                        </h3>

                        <p class="text-sm text-gray-500">
                            Selecciona los campos que deseas incluir.
                        </p>

                    </div>


                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">


                        <label class="flex items-center gap-2">

                            <input
                                type="checkbox"
                                name="campos[]"
                                value="nombres"
                                class="w-4 h-4">

                            Nombres

                        </label>


                        <label class="flex items-center gap-2">

                            <input
                                type="checkbox"
                                name="campos[]"
                                value="apellidos"
                                class="w-4 h-4">

                            Apellidos

                        </label>


                        <label class="flex items-center gap-2">

                            <input
                                type="checkbox"
                                name="campos[]"
                                value="documento"
                                class="w-4 h-4">

                            Documento

                        </label>


                        <label class="flex items-center gap-2">

                            <input
                                type="checkbox"
                                name="campos[]"
                                value="fecha_nacimiento"
                                class="w-4 h-4">

                            Fecha de nacimiento

                        </label>


                        <label class="flex items-center gap-2">

                            <input
                                type="checkbox"
                                name="campos[]"
                                value="telefono"
                                class="w-4 h-4">

                            Teléfono

                        </label>


                        <label class="flex items-center gap-2">

                            <input
                                type="checkbox"
                                name="campos[]"
                                value="email"
                                class="w-4 h-4">

                            Email

                        </label>


                        <label class="flex items-center gap-2">

                            <input
                                type="checkbox"
                                name="campos[]"
                                value="direccion"
                                class="w-4 h-4">

                            Dirección

                        </label>


                        <label class="flex items-center gap-2">

                            <input
                                type="checkbox"
                                name="campos[]"
                                value="eps"
                                class="w-4 h-4">

                            EPS

                        </label>


                        <label class="flex items-center gap-2">

                            <input
                                type="checkbox"
                                name="campos[]"
                                value="tipo_sangre"
                                class="w-4 h-4">

                            Tipo de sangre

                        </label>


                        <label class="flex items-center gap-2">

                            <input
                                type="checkbox"
                                name="campos[]"
                                value="acudiente"
                                class="w-4 h-4">

                            Acudiente

                        </label>


                        <label class="flex items-center gap-2">

                            <input
                                type="checkbox"
                                name="campos[]"
                                value="documento_acudiente"
                                class="w-4 h-4">

                            Documento acudiente

                        </label>


                        <label class="flex items-center gap-2">

                            <input
                                type="checkbox"
                                name="campos[]"
                                value="telefono_acudiente"
                                class="w-4 h-4">

                            Teléfono acudiente

                        </label>


                        <label class="flex items-center gap-2">

                            <input
                                type="checkbox"
                                name="campos[]"
                                value="email_acudiente"
                                class="w-4 h-4">

                            Email acudiente

                        </label>


                        <label class="flex items-center gap-2">

                            <input
                                type="checkbox"
                                name="campos[]"
                                value="parentesco"
                                class="w-4 h-4">

                            Parentesco

                        </label>

                    </div>

                </div>



                {{-- ========================================= --}}
                {{-- DOCUMENTOS --}}
                {{-- ========================================= --}}

                <div class="mb-7">

                    <div class="mb-4">

                        <h3 class="font-semibold text-lg">
                            📎 Documentos
                        </h3>

                        <p class="text-sm text-gray-500">
                            Selecciona los documentos que deseas incluir.
                        </p>

                    </div>


                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">


                        <label class="flex items-center gap-2">

                            <input
                                type="checkbox"
                                name="documentos[]"
                                value="foto"
                                class="w-4 h-4">

                            Foto

                        </label>


                        <label class="flex items-center gap-2">

                            <input
                                type="checkbox"
                                name="documentos[]"
                                value="documento_identidad"
                                class="w-4 h-4">

                            Documento de identidad

                        </label>


                        <label class="flex items-center gap-2">

                            <input
                                type="checkbox"
                                name="documentos[]"
                                value="eps"
                                class="w-4 h-4">

                            Documento EPS

                        </label>


                        <label class="flex items-center gap-2">

                            <input
                                type="checkbox"
                                name="documentos[]"
                                value="certificado_medico"
                                class="w-4 h-4">

                            Certificado médico

                        </label>


                        <label class="flex items-center gap-2">

                            <input
                                type="checkbox"
                                name="documentos[]"
                                value="otros"
                                class="w-4 h-4">

                            Otros documentos

                        </label>


                        <label class="flex items-center gap-2">

                            <input
                                type="checkbox"
                                name="documentos[]"
                                value="todos"
                                class="w-4 h-4"
                                id="documentos_todos">

                            Todos los documentos

                        </label>

                    </div>

                </div>



                {{-- ========================================= --}}
                {{-- FORMATO --}}
                {{-- ========================================= --}}

                <div>

                    <h3 class="font-semibold text-lg mb-4">
                        📁 Formato de exportación
                    </h3>


                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">


                        <label
                            class="border rounded-lg p-4 cursor-pointer hover:bg-slate-50">

                            <input
                                type="radio"
                                name="formato"
                                value="excel"
                                checked
                                class="mr-2">

                            📊 Excel

                        </label>


                        <label
                            class="border rounded-lg p-4 cursor-pointer hover:bg-slate-50">

                            <input
                                type="radio"
                                name="formato"
                                value="pdf"
                                class="mr-2">

                            📄 PDF

                        </label>


                        <label
                            class="border rounded-lg p-4 cursor-pointer hover:bg-slate-50">

                            <input
                                type="radio"
                                name="formato"
                                value="zip"
                                class="mr-2">

                            📦 Excel + documentos

                        </label>

                    </div>

                </div>

            </div>



            {{-- PIE DEL MODAL --}}

            <div
                class="flex justify-end gap-3 px-6 py-4 border-t bg-gray-50">

                <button
                    type="button"
                    onclick="cerrarExportacion()"
                    class="bg-gray-200 hover:bg-gray-300 px-5 py-2 rounded-lg">

                    Cancelar

                </button>


                <button
                    type="button"
                    onclick="procesarExportacion()"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg">

                    📤 Exportar

                </button>

            </div>

        </form>

    </div>

</div>



{{-- ========================================================= --}}
{{-- JAVASCRIPT DE LA PLANILLA --}}
{{-- ========================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {


    /*
    |--------------------------------------------------------------------------
    | ELEMENTOS DE REFUERZOS
    |--------------------------------------------------------------------------
    */

    const categoria =
        document.getElementById('categoria_refuerzo');

    const jugador =
        document.getElementById('jugador_refuerzo');

    const boton =
        document.getElementById('agregarRefuerzo');

    const lista =
        document.getElementById('listaRefuerzos');


    /*
    |--------------------------------------------------------------------------
    | JUGADORES DISPONIBLES
    |--------------------------------------------------------------------------
    */

    const jugadores =
        @json($jugadoresParaJavascript);



    /*
    |--------------------------------------------------------------------------
    | CAMBIO DE CATEGORÍA
    |--------------------------------------------------------------------------
    */

    categoria.addEventListener('change', function () {

        jugador.innerHTML =
            '<option value="">Seleccione un jugador</option>';


        if (!this.value) {
            return;
        }


        jugadores
            .filter(function (j) {

                return String(j.categoria_id) ===
                       String(categoria.value);

            })
            .forEach(function (j) {

                const option =
                    document.createElement('option');


                option.value =
                    j.id;


                option.textContent =
                    j.nombres + ' ' + j.apellidos;


                jugador.appendChild(option);

            });

    });



    /*
    |--------------------------------------------------------------------------
    | AGREGAR REFUERZO
    |--------------------------------------------------------------------------
    */

    boton.addEventListener('click', function () {

        const jugadorId =
            jugador.value;


        if (!jugadorId) {

            alert('Seleccione un jugador.');

            return;

        }


        const seleccionado =
            jugadores.find(function (j) {

                return String(j.id) ===
                       String(jugadorId);

            });


        if (!seleccionado) {
            return;
        }


        /*
        | EVITAR DUPLICADOS
        */

        if (
            document.querySelector(
                '#refuerzo_' + seleccionado.id
            )
        ) {

            alert(
                'Este jugador ya está seleccionado.'
            );

            return;

        }


        /*
        | SI YA ESTÁ COMO JUGADOR DE CATEGORÍA
        */

        const checkboxExistente =
            document.querySelector(
                'input[name="jugadores[]"][value="' +
                seleccionado.id +
                '"]'
            );


        if (checkboxExistente) {

            checkboxExistente.checked =
                true;


            alert(
                'Este jugador ya pertenece a la categoría principal.'
            );


            jugador.value =
                '';


            return;

        }


        /*
        | CREAR REFUERZO
        */

        const div =
            document.createElement('div');


        div.id =
            'refuerzo_' + seleccionado.id;


        div.dataset.jugador =
            seleccionado.id;


        div.className =
            'flex justify-between items-center border rounded-lg px-4 py-3 mb-2 bg-blue-50';


        div.innerHTML = `

            <div>

                <span class="font-medium">

                    ${seleccionado.nombres}
                    ${seleccionado.apellidos}

                </span>

                <span class="text-sm text-gray-500 ml-2">

                    ${seleccionado.categoria ?? '—'}

                </span>

            </div>


            <div class="flex items-center gap-3">

                <span class="px-2 py-1 rounded bg-blue-100 text-blue-700 text-xs">

                    🔵 Refuerzo

                </span>


                <button
                    type="button"
                    class="text-red-600 hover:text-red-800 eliminar-refuerzo">

                    Quitar

                </button>

            </div>


            <input
                type="hidden"
                name="jugadores[]"
                value="${seleccionado.id}">

        `;


        lista.appendChild(div);


        jugador.value =
            '';

    });



    /*
    |--------------------------------------------------------------------------
    | QUITAR REFUERZO
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        'click',
        function (event) {

            if (
                event.target.classList.contains(
                    'eliminar-refuerzo'
                )
            ) {

                const elemento =
                    event.target.closest(
                        '[data-jugador]'
                    );


                if (elemento) {

                    elemento.remove();

                }

            }

        }
    );

});



/*
|--------------------------------------------------------------------------
| ABRIR EXPORTACIÓN
|--------------------------------------------------------------------------
*/

function abrirExportacion()
{

    const modal =
        document.getElementById(
            'modalExportacion'
        );


    modal.classList.remove(
        'hidden'
    );


    modal.classList.add(
        'flex'
    );

}



/*
|--------------------------------------------------------------------------
| CERRAR EXPORTACIÓN
|--------------------------------------------------------------------------
*/

function cerrarExportacion()
{

    const modal =
        document.getElementById(
            'modalExportacion'
        );


    modal.classList.add(
        'hidden'
    );


    modal.classList.remove(
        'flex'
    );

}



/*
|--------------------------------------------------------------------------
| DOCUMENTOS: TODOS
|--------------------------------------------------------------------------
*/

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const todos =
            document.getElementById(
                'documentos_todos'
            );


        if (!todos) {
            return;
        }


        todos.addEventListener(
            'change',
            function () {

                const documentos =
                    document.querySelectorAll(
                        'input[name="documentos[]"]:not(#documentos_todos)'
                    );


                documentos.forEach(
                    function (checkbox) {

                        checkbox.checked =
                            todos.checked;

                    }
                );

            }
        );

    }
);



/*
|--------------------------------------------------------------------------
| PROCESAR EXPORTACIÓN
|--------------------------------------------------------------------------
*/

function procesarExportacion()
{
    const campos =
        document.querySelectorAll(
            'input[name="campos[]"]:checked'
        );

    const documentos =
        document.querySelectorAll(
            'input[name="documentos[]"]:checked'
        );

    const formato =
        document.querySelector(
            'input[name="formato"]:checked'
        );


    /*
    |--------------------------------------------------------------------------
    | Validar campos
    |--------------------------------------------------------------------------
    */

    if (campos.length === 0) {

        alert(
            'Selecciona al menos un dato del jugador.'
        );

        return;
    }


    /*
    |--------------------------------------------------------------------------
    | Validar formato
    |--------------------------------------------------------------------------
    */

    if (!formato) {

        alert(
            'Selecciona un formato de exportación.'
        );

        return;
    }


    /*
    |--------------------------------------------------------------------------
    | EXCEL
    |--------------------------------------------------------------------------
    */

    if (formato.value === 'excel') {

        const url =
            "{{ route('competencias.participantes.exportar', $competencia) }}";

        const parametros =
            new URLSearchParams();


        campos.forEach(function (campo) {

            parametros.append(
                'campos[]',
                campo.value
            );

        });


        window.location.href =
            url + '?' + parametros.toString();

        return;
    }


    /*
    |--------------------------------------------------------------------------
    | EXCEL + DOCUMENTOS
    |--------------------------------------------------------------------------
    */

    if (formato.value === 'zip') {

        if (documentos.length === 0) {

            alert(
                'Selecciona al menos un documento.'
            );

            return;
        }


        const url =
            "{{ route('competencias.participantes.exportarZip', $competencia) }}";

        const parametros =
            new URLSearchParams();


        /*
        | Campos del jugador
        */

        campos.forEach(function (campo) {

            parametros.append(
                'campos[]',
                campo.value
            );

        });


        /*
        | Documentos
        */

        documentos.forEach(function (documento) {

            parametros.append(
                'documentos[]',
                documento.value
            );

        });


        parametros.append(
            'formato',
            'zip'
        );


        window.location.href =
            url + '?' + parametros.toString();

        return;
    }


    /*
    |--------------------------------------------------------------------------
    | PDF
    |--------------------------------------------------------------------------
    */

    if (formato.value === 'pdf') {

    const url =
        "{{ route('competencias.participantes.exportarPdf', $competencia) }}";

    const parametros =
        new URLSearchParams();


    campos.forEach(function (campo) {

        parametros.append(
            'campos[]',
            campo.value
        );

    });


    parametros.append(
        'formato',
        'pdf'
    );


    window.location.href =
        url + '?' + parametros.toString();

    return;

    }
}
</script>


@endsection
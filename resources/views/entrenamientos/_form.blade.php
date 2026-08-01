@csrf

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    {{-- Equipo --}}
    <div>
        <label class="font-semibold">Equipo *</label>

        <select
            name="equipo_id"
            class="w-full border rounded-lg px-4 py-3">

            <option value="">Seleccione un equipo...</option>

            @foreach($equipos as $equipo)

                <option value="{{ $equipo->id }}"
                    {{ old('equipo_id', $entrenamiento->equipo_id ?? '') == $equipo->id ? 'selected' : '' }}>

                    {{ $equipo->nombre }}

                </option>

            @endforeach

        </select>

        @error('equipo_id')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror

    </div>
    {{-- CATEGORIA --}}
    <div class="md:col-span-2">

    <label class="font-semibold mb-3 block">
        Categorías
    </label>

    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">

        @foreach($categorias as $categoria)

            <label class="flex items-center gap-3 border rounded-lg p-3 hover:bg-gray-50">

                <input
                    type="checkbox"
                    name="categorias[]"
                    value="{{ $categoria->id }}"

                    @checked(
                        isset($entrenamiento)
                            ? $entrenamiento->categorias->contains($categoria->id)
                            : in_array($categoria->id, old('categorias', []))
                    )

                    class="w-5 h-5">

                <span>{{ $categoria->nombre }}</span>

            </label>

        @endforeach

    </div>

</div>

    {{-- Entrenador --}}
    <div>

        <label class="font-semibold">Entrenador *</label>

        <select
            name="entrenador_id"
            class="w-full border rounded-lg px-4 py-3">

            <option value="">Seleccione un entrenador...</option>

            @foreach($entrenadores as $entrenador)

                <option value="{{ $entrenador->id }}"
                    {{ old('entrenador_id', $entrenamiento->entrenador_id ?? '') == $entrenador->id ? 'selected' : '' }}>

                    {{ $entrenador->nombres }} {{ $entrenador->apellidos }}

                </option>

            @endforeach

        </select>

        @error('entrenador_id')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror

    </div>

    {{-- Fecha --}}
    <div>

        <label class="font-semibold">Fecha *</label>

        <input
            type="date"
            name="fecha"
            value="{{ old('fecha', $entrenamiento->fecha ?? '') }}"
            class="w-full border rounded-lg px-4 py-3">

    </div>

    {{-- Lugar --}}
    <div>

        <label class="font-semibold">Lugar</label>

        <input
            type="text"
            name="lugar"
            value="{{ old('lugar', $entrenamiento->lugar ?? '') }}"
            class="w-full border rounded-lg px-4 py-3">

    </div>

    {{-- Hora inicio --}}
    <div>

        <label class="font-semibold">Hora inicio</label>

        <input
            type="time"
            name="hora_inicio"
            value="{{ old('hora_inicio', $entrenamiento->hora_inicio ?? '') }}"
            class="w-full border rounded-lg px-4 py-3">

    </div>

    {{-- Hora fin --}}
    <div>

        <label class="font-semibold">Hora fin</label>

        <input
            type="time"
            name="hora_fin"
            value="{{ old('hora_fin', $entrenamiento->hora_fin ?? '') }}"
            class="w-full border rounded-lg px-4 py-3">

    </div>

    {{-- Tipo --}}
    <div>

        <label class="font-semibold">Tipo</label>

        <select
            name="tipo"
            class="w-full border rounded-lg px-4 py-3">

            <option value="">Seleccione...</option>

            <option>Técnico</option>
            <option>Táctico</option>
            <option>Físico</option>
            <option>Definición</option>
            <option>Arqueros</option>
            <option>Partido Amistoso</option>
            <option>Recuperación</option>
            <option>Evaluación</option>
            <option>Otro</option>

        </select>

    </div>

    {{-- Estado --}}
    <div>

        <label class="font-semibold">Estado</label>

        <select
            name="estado"
            class="w-full border rounded-lg px-4 py-3">

            <option>Programado</option>
            <option>Realizado</option>
            <option>Cancelado</option>

        </select>

    </div>
    {{-- Entrenamiento recurrente --}}
<div class="md:col-span-2 mt-4">

    <label class="flex items-center gap-3">

        <input
            type="checkbox"
            id="recurrente"
            name="recurrente"
            value="1"
            @checked(old('recurrente', $entrenamiento->es_recurrente ?? false))
        >

        <span class="font-semibold">
            Entrenamiento fijo (Recurrente)
        </span>

    </label>

</div>

<div
    id="bloque-recurrente"
    class="md:col-span-2 mt-4"
    style="display:none;">

    <label class="font-semibold block mb-3">
        Días de entrenamiento
    </label>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">

        @php

        $dias = [
            'Lunes',
            'Martes',
            'Miércoles',
            'Jueves',
            'Viernes',
            'Sábado',
            'Domingo'
        ];

        $seleccionados = old(
            'dias_semana',
            isset($entrenamiento)
                ? json_decode($entrenamiento->dias_semana,true)
                : []
        );

        @endphp

        @foreach($dias as $dia)

            <label class="flex items-center gap-2">

                <input
                    type="checkbox"
                    name="dias_semana[]"
                    value="{{ $dia }}"
                    @checked(in_array($dia,$seleccionados ?? []))
                >

                {{ $dia }}

            </label>

        @endforeach

    </div>

    <div class="mt-5">

        <label class="font-semibold">
            Repetir hasta
        </label>

        <input
            type="date"
            name="fecha_fin"
            value="{{ old('fecha_fin',$entrenamiento->fecha_fin ?? '') }}"
            class="w-full border rounded-lg px-4 py-3 mt-2">

    </div>

</div>
    <div class="md:col-span-2">

    



</div>

    {{-- Observaciones --}}
    <div class="md:col-span-2">

        <label class="font-semibold">Observaciones</label>

        <textarea
            name="observaciones"
            rows="4"
            class="w-full border rounded-lg px-4 py-3">{{ old('observaciones', $entrenamiento->observaciones ?? '') }}</textarea>

    </div>

</div>

<div class="mt-8 flex justify-end gap-4">

    <a
        href="{{ route('entrenamientos.index') }}"
        class="bg-gray-500 hover:bg-gray-600 text-white px-8 py-3 rounded-lg">

        Cancelar

    </a>

    <button
        class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg">

        Guardar Entrenamiento

    </button>

    <script>

const check = document.getElementById('recurrente');
const bloque = document.getElementById('bloque-recurrente');

function mostrarBloque(){

    bloque.style.display = check.checked
        ? 'block'
        : 'none';

}

mostrarBloque();

check.addEventListener('change', mostrarBloque);

</script>

</div>
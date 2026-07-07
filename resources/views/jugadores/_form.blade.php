@csrf

<div class="space-y-8">


    {{-- DATOS PERSONALES --}}
    <div class="bg-white rounded-xl shadow">

        <div class="bg-slate-800 text-white px-6 py-3 rounded-t-xl">
            <h2 class="text-lg font-bold">👤 Datos personales</h2>
        </div>

        <div class="p-6 grid grid-cols-2 gap-6">
<div class="col-span-2">

    <label class="font-semibold block mb-3">
        Fotografía del jugador
    </label>

    @if(isset($jugador) && $jugador->foto)

        <img
            src="{{ asset('storage/'.$jugador->foto) }}"
            class="w-40 h-40 object-cover rounded-xl border mb-4">

    @endif

    <input
        type="file"
        name="foto"
        accept="image/*"
        class="w-full border rounded-lg p-3">

</div>
            <div>
                <label class="font-semibold">Nombres</label>

                <input
                    type="text"
                    name="nombres"
                    value="{{ old('nombres', $jugador->nombres ?? '') }}"
                    class="w-full mt-2 border rounded-lg p-3">
            </div>

            <div>
                <label class="font-semibold">Apellidos</label>

                <input
                    type="text"
                    name="apellidos"
                    value="{{ old('apellidos', $jugador->apellidos ?? '') }}"
                    class="w-full mt-2 border rounded-lg p-3">
            </div>

            <div>
                <label class="font-semibold">Tipo documento</label>

                <select
                    name="tipo_documento"
                    class="w-full mt-2 border rounded-lg p-3">

                    <option value="">Seleccione</option>
                    <option value="TI"
    {{ old('tipo_documento', $jugador->tipo_documento ?? '') == 'TI' ? 'selected' : '' }}>
    TI
</option>
                    <option value="CC"
        {{ old('tipo_documento', $jugador->tipo_documento ?? '') == 'CC' ? 'selected' : '' }}>
        CC
    </option>
                    <option value="CE"
        {{ old('tipo_documento', $jugador->tipo_documento ?? '') == 'CE' ? 'selected' : '' }}>
        CE
    </option>
                     <option value="Pasaporte"
        {{ old('tipo_documento', $jugador->tipo_documento ?? '') == 'Pasaporte' ? 'selected' : '' }}>
        Pasaporte
    </option>

                </select>

            </div>
<div>
    <label class="font-semibold">Fecha de nacimiento</label>

    <input
        type="date"
        name="fecha_nacimiento"
        value="{{ old('fecha_nacimiento', isset($jugador) && $jugador->fecha_nacimiento ? $jugador->fecha_nacimiento->format('Y-m-d') : '') }}"
            class="w-full mt-2 border rounded-lg p-3">
</div>
            <div>

                <label class="font-semibold">Número documento</label>

                <input
                    type="text"
                    name="numero_documento"
                    value="{{ old('numero_documento', $jugador->numero_documento ?? '') }}"
                    class="w-full mt-2 border rounded-lg p-3">
                @error('numero_documento')
    <p class="text-red-600 text-sm mt-1">
        {{ $message }}
    </p>
@enderror
            </div>

            <div>

                <label class="font-semibold">Género</label>

                <select
    name="genero"
    class="w-full mt-2 border rounded-lg p-3">

    <option value="">Seleccione</option>

    <option value="Masculino"
        {{ old('genero', $jugador->genero ?? '') == 'Masculino' ? 'selected' : '' }}>
        Masculino
    </option>

    <option value="Femenino"
        {{ old('genero', $jugador->genero ?? '') == 'Femenino' ? 'selected' : '' }}>
        Femenino
    </option>

</select>

            </div>

            <div>

                <label class="font-semibold">Teléfono</label>

                <input
                    type="text"
                    name="telefono"
                    value="{{ old('telefono', $jugador->telefono ?? '') }}"
                    class="w-full mt-2 border rounded-lg p-3">

            </div>

            <div>

                <label class="font-semibold">Ciudad</label>

                <input
                    type="text"
                    name="ciudad"
                    value="{{ old('ciudad', $jugador->ciudad ?? '') }}"
                    class="w-full mt-2 border rounded-lg p-3">

            </div>
                 <div>

                   <label class="font-semibold">Correo electrónico</label>

                 <input
                    type="email"
                    name="email"
                    value="{{ old('email', $jugador->email ?? '') }}"
                    class="w-full mt-2 border rounded-lg p-3">

                 </div>

                 
                 <div>

    <label class="font-semibold">Dirección</label>

    <input
        type="text"
        name="direccion"
        value="{{ old('direccion', $jugador->direccion ?? '') }}"
        class="w-full mt-2 border rounded-lg p-3">

</div>

        </div>

    </div>

    {{-- INFORMACIÓN DEPORTIVA --}}

    <div class="bg-white rounded-xl shadow">

        <div class="b   g-green-700 text-white px-6 py-3 rounded-t-xl">

            <h2 class="text-lg font-bold">⚽ Información deportiva</h2>

        </div>

        <div class="p-6 grid grid-cols-2 gap-6">

            <div>

                <label>Categoría</label>

                <input
                    type="text"
                    name="categoria"
                    value="{{ old('categoria', $jugador->categoria ?? '') }}"
                    class="w-full mt-2 border rounded-lg p-3">

            </div>

            <div>

                <label>Equipo</label>

                <input
                    type="text"
                    name="equipo"
                    value="{{ old('equipo', $jugador->equipo ?? '') }}"
                    class="w-full mt-2 border rounded-lg p-3">

            </div>

            <div>

                <label>Posición</label>

                <select
    name="posicion"
    class="w-full mt-2 border rounded-lg p-3">

<option value="">Seleccione</option>

@foreach([
'Arquera',
'Portera',
'Defensa Central',
'Lateral Derecho',
'Lateral Izquierdo',
'Volante Defensivo',
'Volante Mixto',
'Volante Ofensivo',
'Extremo Derecho',
'Extremo Izquierdo',
'Delantera'
] as $posicion)

<option value="{{ $posicion }}"
{{ old('posicion',$jugador->posicion ?? '')==$posicion ? 'selected':'' }}>

{{ $posicion }}

</option>

@endforeach

</select>

            </div>

            <div>

                <label>Pierna hábil</label>

                <select
                   name="pierna_habil"
                   class="w-full mt-2 border rounded-lg p-3">

                   <option value="">Seleccione</option>

                    <option value="Derecha"
                   {{ old('pierna_habil', $jugador->pierna_habil ?? '') == 'Derecha' ? 'selected' : '' }}>
                    Derecha
                 </option>

                  <option value="Izquierda"
                   {{ old('pierna_habil', $jugador->pierna_habil ?? '') == 'Izquierda' ? 'selected' : '' }}>
                    Izquierda
                   </option>

                   <option value="Ambas"
                  {{ old('pierna_habil', $jugador->pierna_habil ?? '') == 'Ambas' ? 'selected' : '' }}>
                    Ambas
                     </option>
                       <div>

                   <label class="font-semibold">Estatura (m)</label>

                       <input
                          type="number"
                         step="0.01"
                          name="estatura"
                           value="{{ old('estatura', $jugador->estatura ?? '') }}"
                           class="w-full mt-2 border rounded-lg p-3">

</div>

<div>

    <label class="font-semibold">Peso (kg)</label>

    <input
        type="number"
        step="0.01"
        name="peso"
        value="{{ old('peso', $jugador->peso ?? '') }}"
        class="w-full mt-2 border rounded-lg p-3">

</div>

</select>
{{-- INFORMACIÓN MÉDICA --}}

<div class="bg-white rounded-xl shadow">

    <div class="bg-red-700 text-white px-6 py-3 rounded-t-xl">
        <h2 class="text-lg font-bold">🏥 Información médica</h2>
    </div>

    <div class="p-6 grid grid-cols-2 gap-6">

        <div>

            <label class="font-semibold">EPS</label>

            <input
                type="text"
                name="eps"
                value="{{ old('eps', $jugador->eps ?? '') }}"
                class="w-full mt-2 border rounded-lg p-3">

        </div>

        <div>

            <label class="font-semibold">Tipo de sangre</label>

            <select
    name="tipo_sangre"
    class="w-full mt-2 border rounded-lg p-3">

    <option value="">Seleccione</option>

    @foreach(['O+','O-','A+','A-','B+','B-','AB+','AB-'] as $tipo)

        <option value="{{ $tipo }}"
            {{ old('tipo_sangre',$jugador->tipo_sangre ?? '')==$tipo ? 'selected':'' }}>

            {{ $tipo }}

        </option>

    @endforeach

</select>

        </div>

        <div class="col-span-2">

            <label class="font-semibold">Alergias</label>

            <textarea
                name="alergias"
                rows="3"
                class="w-full mt-2 border rounded-lg p-3">{{ old('alergias', $jugador->alergias ?? '') }}</textarea>

        </div>

        <div class="col-span-2">

            <label class="font-semibold">Observaciones médicas</label>

            <textarea
                name="observaciones_medicas"
                rows="3"
                class="w-full mt-2 border rounded-lg p-3">{{ old('observaciones_medicas', $jugador->observaciones_medicas ?? '') }}</textarea>

        </div>

    </div>

</div>
{{-- ACUDIENTE --}}

<div class="bg-white rounded-xl shadow">

    <div class="bg-indigo-700 text-white px-6 py-3 rounded-t-xl">
        <h2 class="text-lg font-bold">👨 Acudiente</h2>
    </div>

    <div class="p-6 grid grid-cols-3 gap-6">

        <div>

            <label class="font-semibold">Nombre</label>

            <input
                type="text"
                name="acudiente"
                value="{{ old('acudiente', $jugador->acudiente ?? '') }}"
                class="w-full mt-2 border rounded-lg p-3">

        </div>

        <div>

            <label class="font-semibold">Teléfono</label>

            <input
                type="text"
                name="telefono_acudiente"
                value="{{ old('telefono_acudiente', $jugador->telefono_acudiente ?? '') }}"
                class="w-full mt-2 border rounded-lg p-3">

        </div>

        <div>

            <label class="font-semibold">Parentesco</label>

            <select
    name="parentesco"
    class="w-full mt-2 border rounded-lg p-3">

    <option value="">Seleccione</option>

    <option value="Madre"
        {{ old('parentesco',$jugador->parentesco ?? '')=='Madre' ? 'selected':'' }}>
        Madre
    </option>

    <option value="Padre"
        {{ old('parentesco',$jugador->parentesco ?? '')=='Padre' ? 'selected':'' }}>
        Padre
    </option>

    <option value="Abuelo(a)"
        {{ old('parentesco',$jugador->parentesco ?? '')=='Abuelo(a)' ? 'selected':'' }}>
        Abuelo(a)
    </option>

    <option value="Hermano(a)"
        {{ old('parentesco',$jugador->parentesco ?? '')=='Hermano(a)' ? 'selected':'' }}>
        Hermano(a)
    </option>

    <option value="Tío(a)"
        {{ old('parentesco',$jugador->parentesco ?? '')=='Tío(a)' ? 'selected':'' }}>
        Tío(a)
    </option>

    <option value="Otro Familiar"
        {{ old('parentesco',$jugador->parentesco ?? '')=='Otro Familiar' ? 'selected':'' }}>
        Otro Familiar
    </option>

    <option value="Tutor"
        {{ old('parentesco',$jugador->parentesco ?? '')=='Tutor' ? 'selected':'' }}>
        Tutor
    </option>

</select>

        </div>

    </div>

</div>
            </div>

        </div>

    </div>
@csrf

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    {{-- Foto --}}
    <div class="md:col-span-2">
        <label class="font-semibold">Foto</label>

        <input
            type="file"
            name="foto"
            class="w-full border rounded-lg px-4 py-3">

        @isset($entrenador)
            @if($entrenador->foto)
                <img
                    src="{{ asset('storage/'.$entrenador->foto) }}"
                    class="w-24 h-24 rounded-full mt-4 object-cover">
            @endif
        @endisset
    </div>

    {{-- Nombres --}}
<div>
    <label class="font-semibold">Nombres</label>

    <input
        type="text"
        name="nombres"
        value="{{ old('nombres', $entrenador->nombres ?? '') }}"
        class="w-full border rounded-lg px-4 py-3">

    @error('nombres')
        <p class="text-red-600 text-sm mt-1">
            {{ $message }}
        </p>
    @enderror
</div>

    {{-- Apellidos --}}
    <div>
        <label class="font-semibold">Apellidos</label>

        <input
            type="text"
            name="apellidos"
            value="{{ old('apellidos', $entrenador->apellidos ?? '') }}"
            class="w-full border rounded-lg px-4 py-3">
    </div>

    {{-- Documento --}}
<div>
    <label class="font-semibold">Documento</label>

    <input
        type="text"
        name="numero_documento"
        value="{{ old('numero_documento', $entrenador->numero_documento ?? '') }}"
        class="w-full border rounded-lg px-4 py-3">

    @error('numero_documento')
        <p class="text-red-600 text-sm mt-1">
            {{ $message }}
        </p>
    @enderror
</div>

    {{-- Fecha nacimiento --}}
    <div>
        <label class="font-semibold">Fecha de nacimiento</label>

        <input
            type="date"
            name="fecha_nacimiento"
            value="{{ old('fecha_nacimiento', $entrenador->fecha_nacimiento ?? '') }}"
            class="w-full border rounded-lg px-4 py-3">
    </div>

    {{-- Teléfono --}}
    <div>
        <label class="font-semibold">Teléfono</label>

        <input
            type="text"
            name="telefono"
            value="{{ old('telefono', $entrenador->telefono ?? '') }}"
            class="w-full border rounded-lg px-4 py-3">
@error('telefono')
<p class="text-red-600 text-sm mt-1">{{ $message }}</p>
@enderror
        </div>

    {{-- Email --}}
    <div>
        <label class="font-semibold">Correo</label>

        <input
            type="email"
            name="email"
            value="{{ old('email', $entrenador->email ?? '') }}"
            class="w-full border rounded-lg px-4 py-3">
            @error('email')
<p class="text-red-600 text-sm mt-1">{{ $message }}</p>
@enderror
    </div>

    {{-- Ciudad --}}
    <div>
        <label class="font-semibold">Ciudad</label>

        <input
            type="text"
            name="ciudad"
            value="{{ old('ciudad', $entrenador->ciudad ?? '') }}"
            class="w-full border rounded-lg px-4 py-3">
    </div>

    {{-- Dirección --}}
    <div>
        <label class="font-semibold">Dirección</label>

        <input
            type="text"
            name="direccion"
            value="{{ old('direccion', $entrenador->direccion ?? '') }}"
            class="w-full border rounded-lg px-4 py-3">
    </div>

    {{-- Cargo --}}
    <div>
        <label class="font-semibold">Cargo</label>

        <select
    name="cargo"
    class="w-full border rounded-lg px-4 py-3">

    <option value="">Seleccione...</option>

    @php
        $cargos = [
            'Entrenador Principal',
            'Asistente Técnico',
            'Preparador Físico',
            'Entrenador de Arqueros',
            'Fisioterapeuta',
            'Médico Deportivo',
            'Psicólogo Deportivo',
            'Nutricionista',
            'Utilero',
            'Otro'
        ];
    @endphp

    @foreach($cargos as $cargo)

        <option value="{{ $cargo }}"
            {{ old('cargo', $entrenador->cargo ?? '') == $cargo ? 'selected' : '' }}>

            {{ $cargo }}

        </option>

    @endforeach

</select>
    </div>

    {{-- Licencia --}}
    <div>
        <label class="font-semibold">Licencia</label>

        <input
            type="text"
            name="licencia"
            value="{{ old('licencia', $entrenador->licencia ?? '') }}"
            class="w-full border rounded-lg px-4 py-3">
    </div>
    {{-- Equipos que dirige --}}
<div class="md:col-span-2">

    <label class="font-semibold mb-3 block">
        Equipos que dirige
    </label>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">

        @foreach($equipos as $equipo)

            <label class="flex items-center gap-3 border rounded-lg p-3 hover:bg-gray-50">

                <input
                    type="checkbox"
                    name="equipos[]"
                    value="{{ $equipo->id }}"

                    @checked(
                        isset($entrenador)
                            ? $entrenador->equipos->contains($equipo->id)
                            : in_array($equipo->id, old('equipos', []))
                    )

                    class="w-5 h-5">

                <span>{{ $equipo->nombre }} • {{ $equipo->categoria->nombre ?? '-' }}</span>

            </label>

        @endforeach

    </div>

</div>
   
    {{-- Fecha ingreso --}}
    <div>
        <label class="font-semibold">Fecha de ingreso</label>

        <input
            type="date"
            name="fecha_ingreso"
            value="{{ old('fecha_ingreso', $entrenador->fecha_ingreso ?? '') }}"
            class="w-full border rounded-lg px-4 py-3">
    </div>

    {{-- Estado --}}
    <div>
        <label class="font-semibold">Estado</label>

        <select
            name="activo"
            class="w-full border rounded-lg px-4 py-3">

            <option value="1" {{ old('activo', $entrenador->activo ?? 1) == 1 ? 'selected' : '' }}>Activo</option>

            <option value="0" {{ old('activo', $entrenador->activo ?? 1) == 0 ? 'selected' : '' }}>Inactivo</option>

        </select>
    </div>

    {{-- Observaciones --}}
    <div class="md:col-span-2">

        <label class="font-semibold">Observaciones</label>

        <textarea
            name="observaciones"
            rows="4"
            class="w-full border rounded-lg px-4 py-3">{{ old('observaciones', $entrenador->observaciones ?? '') }}</textarea>

    </div>

</div>
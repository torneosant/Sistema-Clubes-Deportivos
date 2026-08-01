@csrf

<div class="space-y-8">

    {{-- ================= DATOS PERSONALES ================= --}}
    <div class="bg-white rounded-xl shadow">

        <div class="bg-slate-800 text-white px-6 py-3 rounded-t-xl">
            <h2 class="text-lg font-bold">👤 Datos personales</h2>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="md:col-span-2">

                <label class="font-semibold block mb-3">
                    Fotografía
                </label>

                @isset($jugador)
                    @if($jugador->foto)
                        <img
                            src="{{ asset('storage/'.$jugador->foto) }}"
                            class="w-36 h-36 rounded-xl object-cover border mb-4">
                    @endif
                @endisset

                <input
                    type="file"
                    name="foto"
                    class="w-full border rounded-lg p-3">

            </div>

            <div>

                <label>Nombres</label>

                <input
                    type="text"
                    name="nombres"
                    value="{{ old('nombres',$jugador->nombres ?? '') }}"
                    class="w-full border rounded-lg p-3 mt-2">
                    @error('nombres') <p class="text-red-600 text-sm mt-1"> {{ $message }} </p> @enderror 

            </div>

            <div>

                <label>Apellidos</label>

                <input
                    type="text"
                    name="apellidos"
                    value="{{ old('apellidos',$jugador->apellidos ?? '') }}"
                    class="w-full border rounded-lg p-3 mt-2">

            </div>

            <div>

                <label>Tipo documento</label>

                <select
                    name="tipo_documento"
                    class="w-full border rounded-lg p-3 mt-2">

                    <option value="">Seleccione</option>

                    @foreach(['TI','CC','CE','Pasaporte'] as $tipo)

                        <option
                            value="{{ $tipo }}"
                            {{ old('tipo_documento',$jugador->tipo_documento ?? '')==$tipo ? 'selected':'' }}>

                            {{ $tipo }}

                        </option>

                    @endforeach

                </select>
 
            </div>

            <div>

                <label>Número documento</label>

                <input
                    type="text"
                    name="numero_documento"
                    value="{{ old('numero_documento',$jugador->numero_documento ?? '') }}"
                    class="w-full border rounded-lg p-3 mt-2">
@error('numero_documento')
        <p class="text-red-600 text-sm mt-1">
            {{ $message }}
        </p>
    @enderror
            </div>

            <div>

                <label>Fecha nacimiento</label>

                <input
                    type="date"
                    name="fecha_nacimiento"
                    value="{{ old('fecha_nacimiento', isset($jugador) && $jugador->fecha_nacimiento ? $jugador->fecha_nacimiento->format('Y-m-d') : '') }}"
                    class="w-full border rounded-lg p-3 mt-2">

            </div>

            <div>

                <label>Género</label>

                <select
                    name="genero"
                    class="w-full border rounded-lg p-3 mt-2">

                    <option value="">Seleccione</option>

                    <option value="Masculino"
                        {{ old('genero',$jugador->genero ?? '')=='Masculino' ? 'selected':'' }}>
                        Masculino
                    </option>

                    <option value="Femenino"
                        {{ old('genero',$jugador->genero ?? '')=='Femenino' ? 'selected':'' }}>
                        Femenino
                    </option>

                </select>

            </div>

            <div>

                <label>Teléfono</label>

                <input
                    type="text"
                    name="telefono"
                    value="{{ old('telefono',$jugador->telefono ?? '') }}"
                    class="w-full border rounded-lg p-3 mt-2">

            </div>

            <div>

                <label>Correo</label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email',$jugador->email ?? '') }}"
                    class="w-full border rounded-lg p-3 mt-2">

            </div>

            <div>

                <label>Ciudad</label>

                <input
                    type="text"
                    name="ciudad"
                    value="{{ old('ciudad',$jugador->ciudad ?? '') }}"
                    class="w-full border rounded-lg p-3 mt-2">

            </div>

            <div>

                <label>Dirección</label>

                <input
                    type="text"
                    name="direccion"
                    value="{{ old('direccion',$jugador->direccion ?? '') }}"
                    class="w-full border rounded-lg p-3 mt-2">

            </div>

        </div>

    </div>

    {{-- ================= INFORMACIÓN DEPORTIVA ================= --}}

    <div class="bg-white rounded-xl shadow">

        <div class="bg-green-700 text-white px-6 py-3 rounded-t-xl">

            <h2 class="text-lg font-bold">
                ⚽ Información deportiva
            </h2>

        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>

                <label>Categoría</label>

                <<select
    id="categoria_id"
    name="categoria_id"
    class="w-full border rounded-lg p-3 mt-2">

    <option value="">Seleccione una categoría</option>

</select>

            </div>

            <div>

                <label>Equipo</label>

                <select
                    id="equipo_id"
                    name="equipo_id"
                    class="w-full border rounded-lg p-3 mt-2">

                    <option value="">Seleccione</option>

                    @foreach($equipos as $equipo)

                        <option
                            value="{{ $equipo->id }}"
                            {{ old('equipo_id',$jugador->equipo_id ?? '')==$equipo->id ? 'selected':'' }}>

                            {{ $equipo->nombre }}

                        </option>

                    @endforeach

                </select>

            </div>

   <div>

    <label class="font-semibold">Posición</label>

    <select
        name="posicion"
        class="w-full mt-2 border rounded-lg p-3">

        <option value="">Seleccione una posición</option>

        <optgroup label="Portería">

            <option value="Arquero(a)"
                {{ old('posicion', $jugador->posicion ?? '') == 'Arquero(a)' ? 'selected' : '' }}>
                🧤 Arquero(a)
            </option>

        </optgroup>

        <optgroup label="Defensa">

            <option value="Defensa Central"
                {{ old('posicion', $jugador->posicion ?? '') == 'Defensa Central' ? 'selected' : '' }}>
                🛡️ Defensa Central
            </option>

            <option value="Lateral Derecho"
                {{ old('posicion', $jugador->posicion ?? '') == 'Lateral Derecho' ? 'selected' : '' }}>
                ➡️ Lateral Derecho
            </option>

            <option value="Lateral Izquierdo"
                {{ old('posicion', $jugador->posicion ?? '') == 'Lateral Izquierdo' ? 'selected' : '' }}>
                ⬅️ Lateral Izquierdo
            </option>

        </optgroup>

        <optgroup label="Mediocampo">

            <option value="Volante de Marca"
                {{ old('posicion', $jugador->posicion ?? '') == 'Volante de Marca' ? 'selected' : '' }}>
                ⚽ Volante de Marca
            </option>

            <option value="Volante Mixto"
                {{ old('posicion', $jugador->posicion ?? '') == 'Volante Mixto' ? 'selected' : '' }}>
                ⚽ Volante Mixto
            </option>

            <option value="Volante Ofensivo"
                {{ old('posicion', $jugador->posicion ?? '') == 'Volante Ofensivo' ? 'selected' : '' }}>
                🎯 Volante Ofensivo
            </option>

        </optgroup>

        <optgroup label="Ataque">

            <option value="Extremo Derecho"
                {{ old('posicion', $jugador->posicion ?? '') == 'Extremo Derecho' ? 'selected' : '' }}>
                ⚡ Extremo Derecho
            </option>

            <option value="Extremo Izquierdo"
                {{ old('posicion', $jugador->posicion ?? '') == 'Extremo Izquierdo' ? 'selected' : '' }}>
                ⚡ Extremo Izquierdo
            </option>

            <option value="Delantero Centro"
                {{ old('posicion', $jugador->posicion ?? '') == 'Delantero Centro' ? 'selected' : '' }}>
                🎯 Delantero Centro
            </option>

        </optgroup>

    </select>

</div>
            <div>

                <label>Pierna hábil</label>

                <select
                    name="pierna_habil"
                    class="w-full border rounded-lg p-3 mt-2">

                    <option value="">Seleccione</option>

                    @foreach(['Derecha','Izquierda','Ambas'] as $pierna)

                        <option
                            value="{{ $pierna }}"
                            {{ old('pierna_habil',$jugador->pierna_habil ?? '')==$pierna ? 'selected':'' }}>

                            {{ $pierna }}

                        </option>

                    @endforeach

                </select>

            </div>

            <div>

                <label class="font-semibold">Estatura (cm)</label>

                <input
        type="number"
        name="estatura"
        value="{{ old('estatura', $jugador->estatura ?? '') }}"
        class="w-full border rounded-lg px-4 py-3"
        placeholder="Ej: 161">

            </div>

            <div>

                 <label class="font-semibold">Peso (kg)</label>

                   <input
        type="number"
        step="0.1"
        name="peso"
        value="{{ old('peso', $jugador->peso ?? '') }}"
        class="w-full border rounded-lg px-4 py-3"
        placeholder="Ej: 50">
            </div>

        </div>

    </div>

    {{-- ================= INFORMACIÓN MÉDICA ================= --}}

    <div class="bg-white rounded-xl shadow">

        <div class="bg-red-700 text-white px-6 py-3 rounded-t-xl">
            <h2 class="text-lg font-bold">🏥 Información médica</h2>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>

                <label>EPS</label>

                <input
                    type="text"
                    name="eps"
                    value="{{ old('eps',$jugador->eps ?? '') }}"
                    class="w-full border rounded-lg p-3 mt-2">

            </div>

            <div>

    <label class="font-semibold">Tipo de sangre</label>

    <select
        name="tipo_sangre"
        class="w-full border rounded-lg p-3 mt-2">

        <option value="">Seleccione</option>

        @foreach(['O+','O-','A+','A-','B+','B-','AB+','AB-'] as $tipo)

            <option
                value="{{ $tipo }}"
                {{ old('tipo_sangre', $jugador->tipo_sangre ?? '') == $tipo ? 'selected' : '' }}>

                {{ $tipo }}

            </option>

        @endforeach

    </select>

</div>

            <div class="md:col-span-2">

                <label>Alergias</label>

                <textarea
                    name="alergias"
                    class="w-full border rounded-lg p-3 mt-2">{{ old('alergias',$jugador->alergias ?? '') }}</textarea>

            </div>

            <div class="md:col-span-2">

                <label>Observaciones médicas</label>

                <textarea
                    name="observaciones_medicas"
                    class="w-full border rounded-lg p-3 mt-2">{{ old('observaciones_medicas',$jugador->observaciones_medicas ?? '') }}</textarea>

            </div>

        </div>

    </div>

    {{-- ================= ACUDIENTE ================= --}}

    <div class="bg-white rounded-xl shadow">

        <div class="bg-indigo-700 text-white px-6 py-3 rounded-t-xl">
            <h2 class="text-lg font-bold">👨 Acudiente</h2>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">

            <div>

                <label>Nombre</label>

                <input
                    type="text"
                    name="acudiente"
                    value="{{ old('acudiente',$jugador->acudiente ?? '') }}"
                    class="w-full border rounded-lg p-3 mt-2">

            </div>

            <div>

                <label>Teléfono</label>

                <input
                    type="text"
                    name="telefono_acudiente"
                    value="{{ old('telefono_acudiente',$jugador->telefono_acudiente ?? '') }}"
                    class="w-full border rounded-lg p-3 mt-2">

            </div>

            <div>

                <label>Parentesco</label>

                <input
                    type="text"
                    name="parentesco"
                    value="{{ old('parentesco',$jugador->parentesco ?? '') }}"
                    class="w-full border rounded-lg p-3 mt-2">

            </div>

        </div>

    </div>

</div>
<script>

document.addEventListener('DOMContentLoaded', function () {

    const equipo = document.getElementById('equipo_id');
    const categoria = document.getElementById('categoria_id');

    function cargarCategorias(idEquipo){

        categoria.innerHTML='<option>Cargando...</option>';

      fetch('{{ url("equipo") }}/' + idEquipo + '/categorias')

        .then(r=>r.json())

        .then(data=>{
            console.log(data);

            categoria.innerHTML='<option value="">Seleccione una categoría</option>';

            data.forEach(function(item){

                categoria.innerHTML +=
                `<option value="${item.id}">
                    ${item.nombre}
                </option>`;

            });

        });

    }

    equipo.addEventListener('change',function(){

        if(this.value){

            cargarCategorias(this.value);

        }else{

            categoria.innerHTML='<option value="">Seleccione una categoría</option>';

        }

    });

});

</script>


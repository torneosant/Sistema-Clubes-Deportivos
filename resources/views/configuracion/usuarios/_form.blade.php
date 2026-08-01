@if ($errors->any())

<div class="mb-4 bg-red-100 border border-red-300 text-red-700 rounded-lg p-4">

    <ul class="list-disc pl-5">

        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach

    </ul>

</div>

@endif

<div class="p-6 space-y-5">

    <div>

        <label>Nombre</label>

        <input
            type="text"
            name="name"
            value="{{ old('name', $usuario->name ?? '') }}"
            class="w-full border rounded-lg p-3">

    </div>

    <div>

        <label>Correo</label>

        <input
            type="email"
            name="email"
            value="{{ old('email', $usuario->email ?? '') }}"
            class="w-full border rounded-lg p-3">

    </div>

    <div>

        <label>Contraseña</label>

<input
    type="password"
    name="password"
    class="w-full border rounded-lg p-3"
    placeholder="{{ isset($usuario) ? 'Dejar vacío para no cambiarla' : '' }}">

@if(isset($usuario))
    <p class="text-xs text-gray-500 mt-1">
        Si no deseas cambiar la contraseña, deja este campo vacío.
    </p>
@endif

        @isset($usuario)
            <small class="text-gray-500">
                Déjela vacía si no desea cambiarla.
            </small>
        @endisset

    </div>

    <div>

        <label>Rol</label>

        <select id="rol" name="rol_id" class="w-full border rounded-lg p-3">

            @foreach($roles as $rol)

                <option
                    value="{{ $rol->id }}"
                    @selected(old('rol_id', $usuario->rol_id ?? '') == $rol->id)>

                    {{ $rol->nombre }}

                </option>

            @endforeach

        </select>

    </div>

    <div id="bloqueJugador">

        <label>Jugador</label>

        <select
            name="jugador_id"
            class="w-full border rounded-lg p-3">

            <option value="">No aplica</option>

            @foreach($jugadores as $jugador)

                <option
                    value="{{ $jugador->id }}"
                    @selected(old('jugador_id', $usuario->jugador_id ?? '') == $jugador->id)>

                    {{ $jugador->nombres }} {{ $jugador->apellidos }}

                </option>

            @endforeach

        </select>

    </div>

    <div class="flex justify-end gap-3 mt-8">

        <a
            href="{{ route('usuarios.index') }}"
            class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg">

            Cancelar

        </a>

        <button
            type="submit"
            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">

            Guardar

        </button>

    </div>

</div>

<script>

const rol = document.getElementById('rol');
const bloque = document.getElementById('bloqueJugador');

function validarRol(){

    const texto = rol.options[rol.selectedIndex].text.toLowerCase();

    if(texto.includes('deportista')){

        bloque.style.display = 'block';

    }else{

        bloque.style.display = 'none';

    }

}

rol.addEventListener('change', validarRol);

validarRol();

</script>
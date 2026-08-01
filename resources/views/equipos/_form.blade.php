<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <div>

        <label class="font-semibold">
            Nombre del equipo
        </label>

        <input
            type="text"
            name="nombre"
            value="{{ old('nombre', $equipo->nombre ?? '') }}"
            class="w-full mt-2 border rounded-lg p-3"
            required>

    </div>

    <div class="md:col-span-2">

    <label class="font-semibold mb-3 block">
        Categorías del equipo
    </label>

    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">

        @foreach($categorias as $categoria)

            <label class="flex items-center gap-2 border rounded-lg p-2">

                <input
                    type="checkbox"
                    name="categorias[]"
                    value="{{ $categoria->id }}"

                    @checked(
                        isset($equipo)
                        ? $equipo->categorias->contains($categoria->id)
                        : in_array($categoria->id, old('categorias',[]))
                    )

                >

                {{ $categoria->nombre }}

            </label>

        @endforeach

    </div>

</div>


    <div>

        <label class="font-semibold">
            Color principal
        </label>

        <input
            type="text"
            name="color_principal"
            value="{{ old('color_principal', $equipo->color_principal ?? '') }}"
            class="w-full mt-2 border rounded-lg p-3"
            placeholder="Ej: Azul">

    </div>

    <div>

        <label class="font-semibold">
            Color secundario
        </label>

        <input
            type="text"
            name="color_secundario"
            value="{{ old('color_secundario', $equipo->color_secundario ?? '') }}"
            class="w-full mt-2 border rounded-lg p-3"
            placeholder="Ej: Blanco">

    </div>

    <div class="md:col-span-2">

        <label class="font-semibold">
            Escudo
        </label>

        <input
            type="file"
            name="escudo"
            class="w-full mt-2 border rounded-lg p-3">

        @isset($equipo)

            @if($equipo->escudo)

                <img
                    src="{{ asset('storage/'.$equipo->escudo) }}"
                    class="w-28 mt-4 rounded-lg shadow">

            @endif

        @endisset

    </div>

    <div class="md:col-span-2">

        <label class="font-semibold">
            Descripción
        </label>

        <textarea
            name="descripcion"
            rows="4"
            class="w-full mt-2 border rounded-lg p-3">{{ old('descripcion', $equipo->descripcion ?? '') }}</textarea>

    </div>

</div>
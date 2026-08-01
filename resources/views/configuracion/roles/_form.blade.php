@csrf

<div class="bg-white rounded-xl shadow">

    <div class="bg-slate-800 text-white px-6 py-4 rounded-t-xl">

        <h2 class="text-2xl font-bold">

            {{ isset($rol) ? 'Editar Rol' : 'Nuevo Rol' }}

        </h2>

    </div>

    <div class="p-6 space-y-6">

        <div class="bg-slate-100 border rounded-lg p-4">

    <span class="text-gray-500 text-sm">

        Rol del Sistema

    </span>

    <h2 class="text-2xl font-bold mt-1">

        {{ $rol->nombre }}

    </h2>

</div>

        <hr>

        <h3 class="text-lg font-bold">

            Permisos

        </h3>

        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">

            @foreach($permisos as $permiso)

                <label class="flex items-center gap-2">

                    <input
                        type="checkbox"
                        name="permisos[]"
                        value="{{ $permiso->id }}"

                        @if(isset($rol) && $rol->permisos->contains($permiso->id))
                            checked
                        @endif
                    >

                    {{ $permiso->nombre }}

                </label>

            @endforeach

        </div>

        <div class="flex justify-end gap-3 mt-8">

            <a
                href="{{ route('roles.index') }}"
                class="bg-gray-500 text-white px-6 py-3 rounded-lg">

                Cancelar

            </a>

            <button
                class="bg-blue-600 text-white px-6 py-3 rounded-lg">

                Guardar

            </button>

        </div>

    </div>

</div>  
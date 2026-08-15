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
    {{ preg_replace('/^\[CLUB:\d+\]\s*/', '', $rol->nombre) }}
</h2>
        </div>

        <hr>

        <h3 class="text-lg font-bold">
            Permisos por módulo
        </h3>

        @php

            $acciones = ['ver','crear','editar','eliminar'];

            // Solo permisos que tienen acción (inventario.ver)
            $permisosAcciones = $permisos->filter(function($permiso){
                return str_contains($permiso->slug,'.');
            });

            $grupos = $permisosAcciones->groupBy(function($permiso){
                return explode('.',$permiso->slug)[0];
            });

        @endphp

        <div class="overflow-x-auto">

            <table class="w-full border border-gray-300">

                <thead class="bg-gray-100">

                    <tr>

                        <th class="border p-3 text-left">
                            Módulo
                        </th>

                        <th class="border p-3 text-center">
                            👁 Ver
                        </th>

                        <th class="border p-3 text-center">
                            ➕ Crear
                        </th>

                        <th class="border p-3 text-center">
                            ✏ Editar
                        </th>

                        <th class="border p-3 text-center">
                            🗑 Eliminar
                        </th>

                    </tr>

                </thead>

                <tbody>

                @foreach($grupos as $modulo => $lista)

                    <tr>

                        <td class="border p-3 font-semibold">
                            {{ ucfirst(str_replace('_',' ',$modulo)) }}
                        </td>

                        @foreach($acciones as $accion)

                            @php
                                $permiso = $lista->firstWhere('slug',$modulo.'.'.$accion);
                            @endphp

                            <td class="border text-center">

                                @if($permiso)

                                    <input
                                        type="checkbox"
                                        name="permisos[]"
                                        value="{{ $permiso->id }}"
                                        @checked(isset($rol) && $rol->permisos->contains($permiso->id))
                                    >

                                @endif

                            </td>

                        @endforeach

                    </tr>

                @endforeach

                </tbody>

            </table>

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
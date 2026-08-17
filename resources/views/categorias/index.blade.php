@extends('layouts.app')

@section('titulo', 'Categorías')

@section('contenido')

<x-page-header
    title="📂 Listado de Categorías"
    subtitle="Administra las categorías de tu club."
>
    <div class="flex items-center gap-2">

        <x-stat
            label="Total"
            :value="$totalCategorias"
            icon="📂"
            color="blue"
        />

        <x-stat
            label="Activas"
            :value="$totalActivas"
            icon="🟢"
            color="green"
        />

    </div>
</x-page-header>


@if(session('success'))

    <div class="mb-6 rounded-lg bg-green-100 border border-green-300 text-green-700 px-4 py-3">
        {{ session('success') }}
    </div>

@endif


{{-- BOTONES --}}

<x-actions>

    <a
        href="{{ route('categorias.create') }}"
        <x-button color="blue">
            ➕ Nuevo Categoria
        </x-button>
        </a>

</x-actions>


{{-- FILTROS --}}

<x-filter
    :action="route('categorias.index')"
>

    <x-input
        name="buscar"
        value="{{ $buscar }}"
        placeholder="🔍 Buscar categoría..."
    />

    <x-button type="submit" color="blue">
        🔍 Buscar
    </x-button>

    <a
        href="{{ route('categorias.index') }}"
        class="inline-flex items-center justify-center bg-gray-600 hover:bg-gray-700 text-white px-5 py-2 rounded-xl font-semibold transition-all duration-300 shadow-sm hover:shadow-md"
    >
        Limpiar
    </a>

</x-filter>


{{-- TABLA --}}

<x-table>

    <x-table-header>

        <x-table-header-cell>
            Categoría
        </x-table-header-cell>

        <x-table-header-cell align="center">
            Acciones
        </x-table-header-cell>

    </x-table-header>


    <tbody>

        @forelse($categorias as $categoria)

            <x-table-row>

                {{-- CATEGORÍA --}}

                <x-table-cell>

                    <div class="font-semibold text-slate-800">
                        ⚽ {{ $categoria->nombre }}
                    </div>

                </x-table-cell>


                {{-- ACCIONES --}}

                <x-table-cell align="center">

                    <div class="flex justify-center items-center gap-2">

                        {{-- EDITAR --}}

                        <a
                            href="{{ route('categorias.edit', $categoria) }}"
                            class="w-9 h-9 flex items-center justify-center rounded-lg transition-all duration-300 shadow-sm hover:shadow-md bg-yellow-500 hover:bg-yellow-600 text-white"
                            title="Editar"
                        >
                            ✏️
                        </a>


                        {{-- ESTADO --}}

                        <form
                            action="{{ route('categorias.estado', $categoria) }}"
                            method="POST"
                            class="inline"
                        >

                            @csrf
                            @method('PATCH')

                            @if($categoria->activo)

                                <x-button
                                    type="button"
                                    color="green"
                                    icon
                                    onclick="confirmarEstado(this)"
                                    title="Desactivar"
                                >
                                    🟢
                                </x-button>

                            @else

                                <x-button
                                    type="button"
                                    color="gray"
                                    icon
                                    onclick="confirmarEstado(this)"
                                    title="Activar"
                                >
                                    ⚪
                                </x-button>

                            @endif

                        </form>


                        {{-- ELIMINAR --}}

                        <form
                            action="{{ route('categorias.destroy', $categoria) }}"
                            method="POST"
                            class="inline formulario-eliminar"
                        >

                            @csrf
                            @method('DELETE')

                            <x-button
                                type="submit"
                                color="red"
                                icon
                                title="Eliminar"
                            >
                                🗑️
                            </x-button>

                        </form>

                    </div>

                </x-table-cell>

            </x-table-row>

        @empty

            <tr>

                <td
                    colspan="2"
                    class="text-center py-10 text-gray-500"
                >
                    No hay categorías registradas.
                </td>

            </tr>

        @endforelse

    </tbody>

</x-table>


{{-- PAGINACIÓN --}}

<div class="mt-6">

    {{ $categorias->links() }}

</div>

@endsection

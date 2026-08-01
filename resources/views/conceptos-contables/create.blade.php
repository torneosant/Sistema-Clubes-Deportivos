@extends('layouts.app')

@section('titulo')
➕ Nuevo Concepto Contable
@endsection

@section('contenido')

<div class="max-w-3xl mx-auto">

    <div class="bg-white rounded-xl shadow-lg p-8">

        <h1 class="text-3xl font-bold text-slate-700 mb-8">
            ➕ Nuevo Concepto Contable
        </h1>

        <form action="{{ route('conceptos-contables.store') }}" method="POST">

            @csrf

            <div class="space-y-6">

                <div>

                    <label class="font-semibold">
                        Nombre
                    </label>

                    <input
                        type="text"
                        name="nombre"
                        class="w-full border rounded-lg p-3"
                        required>

                </div>

                <div>

                    <label class="font-semibold">
                        Tipo
                    </label>

                    <select
                        name="tipo"
                        class="w-full border rounded-lg p-3">

                        <option value="Ingreso">
                            💰 Ingreso
                        </option>

                        <option value="Gasto">
                            💸 Gasto
                        </option>

                    </select>

                </div>

                <div>

                    <label class="font-semibold">
                        Descripción
                    </label>

                    <textarea
                        name="descripcion"
                        rows="4"
                        class="w-full border rounded-lg p-3"></textarea>

                </div>

                <div>

                    <label class="flex items-center gap-2">

                        <input
                            type="checkbox"
                            name="activo"
                            value="1"
                            checked>

                        Activo

                    </label>

                </div>

            </div>

            <div class="mt-8 flex gap-3">

                <button
                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg">

                    💾 Guardar

                </button>

                <a href="{{ route('conceptos-contables.index') }}"
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg">

                    Cancelar

                </a>

            </div>

        </form>

    </div>

</div>

@endsection
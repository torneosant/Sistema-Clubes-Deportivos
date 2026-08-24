@extends('layouts.app')

@section('titulo','Generar Inscripción')

@section('contenido')

<div class="max-w-3xl mx-auto">

    <x-page-header
        title="📝 Generar Inscripción"
        subtitle="Crea un enlace para que una persona pueda solicitar su inscripción al club."
    />

    <x-card>

        <form
            action="{{ route('inscripciones.store') }}"
            method="POST">

            @csrf

            <div>

                <x-input-label
                    for="categoria_id"
                    value="Categoría"
                />

                <p class="text-sm text-gray-500 mt-1 mb-2">
                    Puedes generar una inscripción específica para una categoría
                    o dejarla como inscripción general.
                </p>

                <select
                    id="categoria_id"
                    name="categoria_id"
                    class="w-full mt-2 border rounded-lg px-4 py-2">

                    <option value="">
                        Inscripción general
                    </option>

                    @foreach($categorias as $categoria)

                        <option
                            value="{{ $categoria->id }}"
                            @selected(old('categoria_id') == $categoria->id)
                        >

                            {{ $categoria->nombre }}

                        </option>

                    @endforeach

                </select>

                @error('categoria_id')

                    <x-input-error
                        :messages="$errors->get('categoria_id')"
                        class="mt-2"
                    />

                @enderror

            </div>


            <div class="mt-8 flex items-center gap-3">

                <x-primary-button type="submit">

                    🔗 Generar enlace

                </x-primary-button>


                <a href="{{ route('inscripciones.index') }}">

                    <x-secondary-button type="button">

                        Cancelar

                    </x-secondary-button>

                </a>

            </div>

        </form>

    </x-card>

</div>

@endsection
@extends('layouts.app')

@section('titulo','Editar Registro Médico')

@section('contenido')

<div class="max-w-5xl mx-auto bg-white rounded-xl shadow p-8">

    <h1 class="text-3xl font-bold text-red-600 mb-8">
        ❤️ Editar Registro Médico
    </h1>

    @if($errors->any())
        <div class="mb-5 bg-red-100 border border-red-300 text-red-700 rounded-lg p-4">
            <ul>
                @foreach($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('historial-medico.update',$historialMedico) }}" method="POST">

        @csrf
        @method('PUT')

        <div class="grid grid-cols-2 gap-6">

            <div class="col-span-2">
                <label class="font-semibold">Jugador</label>

                <select name="jugador_id" class="w-full border rounded-lg p-2">

                    @foreach($jugadores as $jugador)

                        <option value="{{ $jugador->id }}"
                            {{ old('jugador_id',$historialMedico->jugador_id)==$jugador->id ? 'selected' : '' }}>

                            {{ $jugador->nombres }} {{ $jugador->apellidos }}

                        </option>

                    @endforeach

                </select>
            </div>

            <div>
                <label class="font-semibold">Fecha</label>

                <input
                    type="date"
                    name="fecha"
                    value="{{ old('fecha',$historialMedico->fecha) }}"
                    class="w-full border rounded-lg p-2">
            </div>

            <div>
                <label class="font-semibold">Estado</label>

                <select name="estado" class="w-full border rounded-lg p-2">

                    <option value="Activo"
                        {{ old('estado',$historialMedico->estado)=='Activo'?'selected':'' }}>
                        Activo
                    </option>

                    <option value="En recuperación"
                        {{ old('estado',$historialMedico->estado)=='En recuperación'?'selected':'' }}>
                        En recuperación
                    </option>

                    <option value="Alta médica"
                        {{ old('estado',$historialMedico->estado)=='Alta médica'?'selected':'' }}>
                        Alta médica
                    </option>

                </select>
            </div>

            <div>
                <label class="font-semibold">Tipo</label>

                <input
                    type="text"
                    name="tipo"
                    value="{{ old('tipo',$historialMedico->tipo) }}"
                    class="w-full border rounded-lg p-2">
            </div>

            <div>
                <label class="font-semibold">Zona</label>

                <input
                    type="text"
                    name="zona"
                    value="{{ old('zona',$historialMedico->zona) }}"
                    class="w-full border rounded-lg p-2">
            </div>

            <div>
                <label class="font-semibold">Días incapacidad</label>

                <input
                    type="number"
                    name="dias_incapacidad"
                    value="{{ old('dias_incapacidad',$historialMedico->dias_incapacidad) }}"
                    class="w-full border rounded-lg p-2">
            </div>

            <div>
                <label class="font-semibold">Fecha Alta</label>

                <input
                    type="date"
                    name="fecha_alta"
                    value="{{ old('fecha_alta',$historialMedico->fecha_alta) }}"
                    class="w-full border rounded-lg p-2">
            </div>

            <div class="col-span-2">

                <label class="font-semibold">Diagnóstico</label>

                <textarea
                    name="diagnostico"
                    rows="3"
                    class="w-full border rounded-lg p-2">{{ old('diagnostico',$historialMedico->diagnostico) }}</textarea>

            </div>

            <div class="col-span-2">

                <label class="font-semibold">Tratamiento</label>

                <textarea
                    name="tratamiento"
                    rows="3"
                    class="w-full border rounded-lg p-2">{{ old('tratamiento',$historialMedico->tratamiento) }}</textarea>

            </div>

            <div class="col-span-2">

                <label class="font-semibold">Observaciones</label>

                <textarea
                    name="observaciones"
                    rows="3"
                    class="w-full border rounded-lg p-2">{{ old('observaciones',$historialMedico->observaciones) }}</textarea>

            </div>

        </div>

        <div class="flex justify-end gap-3 mt-8">

            <a href="{{ route('historial-medico.index') }}"
               class="bg-gray-500 text-white px-5 py-2 rounded-lg">

                Cancelar

            </a>

            <button
                type="submit"
                class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg">

                💾 Actualizar Registro

            </button>

        </div>

    </form>

</div>

@endsection
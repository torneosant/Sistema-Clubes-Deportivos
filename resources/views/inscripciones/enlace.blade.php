@extends('layouts.app')

@section('titulo','Enlace de Inscripción')

@section('contenido')

<div class="max-w-3xl mx-auto">

    <x-page-header
        title="🔗 Enlace de Inscripción"
        subtitle="Comparte este enlace con la persona que deseas inscribir."
    />

    <x-card>

        <div class="text-center">

            <div class="text-5xl mb-4">
                ✅
            </div>

            <h2 class="text-2xl font-bold text-slate-700">
                Enlace generado correctamente
            </h2>

            <p class="text-gray-500 mt-2">
                La persona podrá abrir este enlace sin iniciar sesión.
            </p>

        </div>


        {{-- CATEGORÍA --}}

        <div class="mt-8 bg-slate-50 border rounded-xl p-5">

            <div class="text-sm text-gray-500">
                Categoría
            </div>

            <div class="text-lg font-semibold">

                {{ $inscripcion->categoria->nombre ?? 'Inscripción general' }}

            </div>

        </div>


        {{-- ENLACE --}}

        <div class="mt-6">

            <x-input-label
                for="enlace"
                value="Enlace de inscripción"
            />

            <div class="flex gap-2 mt-2">

                <input
                    id="enlace"
                    type="text"
                    value="{{ $url }}"
                    readonly
                    class="flex-1 border rounded-lg px-4 py-3 bg-gray-50">

                <x-primary-button
                    type="button"
                    onclick="copiarEnlace()">

                    📋 Copiar

                </x-primary-button>

            </div>

        </div>


        {{-- ABRIR --}}

        <div class="mt-6 flex justify-center">

            <a
                href="{{ $url }}"
                target="_blank">

                <x-secondary-button type="button">

                    🔗 Abrir formulario

                </x-secondary-button>

            </a>

        </div>


        {{-- ACCIONES --}}

        <div class="mt-8 flex justify-center">

            <a href="{{ route('inscripciones.index') }}">

                <x-secondary-button type="button">

                    ← Volver a inscripciones

                </x-secondary-button>

            </a>

        </div>

    </x-card>

</div>


@section('scripts')

<script>

function copiarEnlace() {

    const campo = document.getElementById('enlace');

    navigator.clipboard.writeText(campo.value)
        .then(() => {

            Swal.fire({
                icon: 'success',
                title: 'Enlace copiado',
                text: 'Ahora puedes enviarlo por WhatsApp u otro medio.',
                confirmButtonColor: '#16a34a'
            });

        })
        .catch(() => {

            campo.select();

            document.execCommand('copy');

            Swal.fire({
                icon: 'success',
                title: 'Enlace copiado',
                confirmButtonColor: '#16a34a'
            });

        });

}

</script>

@endsection

@endsection
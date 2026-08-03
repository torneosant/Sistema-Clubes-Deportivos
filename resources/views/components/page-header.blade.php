@props([
    'title',
    'subtitle' => ''
])

<div class="flex justify-between items-center mb-8">

    <div>

        <h1 class="text-3xl font-bold text-slate-800">
            {{ $title }}
        </h1>

        @if($subtitle)
            <p class="text-gray-500 mt-2">
                {{ $subtitle }}
            </p>
        @endif

    </div>

    <div>

        {{ $slot }}

    </div>

</div>
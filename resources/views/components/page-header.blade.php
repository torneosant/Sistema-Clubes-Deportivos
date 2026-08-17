@props([
    'title',
    'subtitle' => ''
])

<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">

    <div>
        <h2 class="text-2xl font-bold text-slate-800">
            {{ $title }}
        </h2>

        @if($subtitle)
            <p class="text-sm text-gray-500 mt-1">
                {{ $subtitle }}
            </p>
        @endif
    </div>

    <div class="flex items-center gap-2">
        {{ $slot }}
    </div>

</div>
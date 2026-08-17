@props([
    'label',
    'value',
    'icon' => null,
    'color' => 'blue'
])

@php

$colors = [
    'blue'   => 'text-blue-600 bg-blue-50',
    'green'  => 'text-green-600 bg-green-50',
    'red'    => 'text-red-600 bg-red-50',
    'yellow' => 'text-yellow-600 bg-yellow-50',
    'purple' => 'text-purple-600 bg-purple-50',
    'gray'   => 'text-gray-600 bg-gray-50',
];

@endphp

<div class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border border-gray-200 bg-white shadow-sm">

    @if($icon)
        <span class="w-7 h-7 rounded-md flex items-center justify-center text-sm {{ $colors[$color] ?? $colors['blue'] }}">
            {{ $icon }}
        </span>
    @endif

    <div class="leading-tight">

        <div class="text-[11px] uppercase tracking-wide text-gray-400 font-semibold">
            {{ $label }}
        </div>

        <div class="text-lg font-bold {{ explode(' ', $colors[$color] ?? $colors['blue'])[0] }}">
            {{ $value }}
        </div>

    </div>

</div>
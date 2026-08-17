@props([
    'color' => 'blue',
    'type' => 'button',
    'icon' => false
])

@php

$colors = [

    'blue' =>
        'bg-blue-600 hover:bg-blue-700',

    'green' =>
        'bg-green-600 hover:bg-green-700',

    'red' =>
        'bg-red-600 hover:bg-red-700',

    'yellow' =>
        'bg-yellow-500 hover:bg-yellow-600',

    'orange' =>
        'bg-orange-500 hover:bg-orange-600',

    'gray' =>
        'bg-gray-600 hover:bg-gray-700',

];

$base = $icon

    ? 'w-9 h-9 inline-flex items-center justify-center rounded-lg text-white text-sm shadow-sm hover:shadow-md transition-all duration-200'

    : 'inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg text-white text-sm font-semibold shadow-sm hover:shadow-md transition-all duration-200 whitespace-nowrap';

@endphp

<button
    type="{{ $type }}"
    {{ $attributes->merge([
        'class' => $base . ' ' . ($colors[$color] ?? $colors['blue'])
    ]) }}
>
    {{ $slot }}
</button>
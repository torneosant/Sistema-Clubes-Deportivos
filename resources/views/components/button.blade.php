@props([
    'color' => 'blue',
    'type' => 'button',
    'icon' => false
])

@php

$colors = [

'blue'   => 'bg-blue-600 hover:bg-blue-700 text-white',

'green'  => 'bg-green-600 hover:bg-green-700 text-white',

'red'    => 'bg-red-600 hover:bg-red-700 text-white',

'orange' => 'bg-orange-500 hover:bg-orange-600 text-white',

'gray'   => 'bg-gray-600 hover:bg-gray-700 text-white',

];

$base = $icon
    ? 'w-9 h-9 flex items-center justify-center rounded-lg transition-all duration-300 shadow-sm hover:shadow-md'
    : 'px-5 py-2 rounded-xl font-semibold transition-all duration-300 shadow-sm hover:shadow-md';

@endphp

<button
    type="{{ $type }}"
    {{ $attributes->merge([
        'class' => $base.' '.$colors[$color]
    ]) }}>

    {{ $slot }}

</button>
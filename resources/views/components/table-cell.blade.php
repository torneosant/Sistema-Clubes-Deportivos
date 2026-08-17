@props([
    'align' => 'left'
])

@php

$alignment = [
    'left'   => 'text-left',
    'center' => 'text-center',
    'right'  => 'text-right',
];

@endphp

<td
    {{ $attributes->merge([
        'class' => 'px-4 py-3 text-sm text-slate-700 ' . ($alignment[$align] ?? $alignment['left'])
    ]) }}
>
    {{ $slot }}

</td>
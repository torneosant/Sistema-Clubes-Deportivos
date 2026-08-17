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

<th
    {{ $attributes->merge([
        'class' =>
            'px-4 py-3 text-xs font-semibold uppercase tracking-wide text-slate-600 ' .
            ($alignment[$align] ?? $alignment['left'])
    ]) }}
>
    {{ $slot }}

</th>
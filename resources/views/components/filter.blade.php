@props([
    'action',
    'method' => 'GET',
])

<form
    action="{{ $action }}"
    method="{{ $method }}"
    {{ $attributes->merge([
        'class' => 'w-full'
    ]) }}
>

    <div
        class="
            w-full
            bg-white
            border border-slate-200
            rounded-xl
            shadow-sm
            p-4

            flex
            flex-col
            lg:flex-row
            lg:items-end
            gap-3
        "
    >

        {{ $slot }}

    </div>

</form>
@props([
    'label' => '',
    'name' => '',
    'type' => 'text'
])

<div class="min-w-[180px] flex-1">

    <div class="h-[18px] mb-1 flex items-center">

        @if($label)
            <label
                for="{{ $name }}"
                class="text-xs font-semibold text-slate-600"
            >
                {{ $label }}
            </label>
        @endif

    </div>

    <input
        id="{{ $name }}"
        type="{{ $type }}"
        name="{{ $name }}"
        {{ $attributes->merge([
            'class' =>
                'w-full h-10 rounded-lg border border-gray-300 bg-white px-3 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition'
        ]) }}
    >

</div>
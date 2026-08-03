@props([
    'label' => '',
    'name' => '',
    'type' => 'text'
])

<div>

    @if($label)
        <label class="block text-sm font-semibold text-slate-700 mb-2">
            {{ $label }}
        </label>
    @endif

    <input
        type="{{ $type }}"
        name="{{ $name }}"
        {{ $attributes->merge([
            'class' => 'w-full rounded-xl border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition'
        ]) }}>

</div>
@props([
    'name',
    'label' => '',
    'value' => '',
    'placeholder' => 'Todos',
    'options' => []
])

<div class="min-w-[180px] flex-1">

    @if($label)
        <label class="block text-xs font-semibold text-slate-600 mb-1">
            {{ $label }}
        </label>
    @endif

    <select
        name="{{ $name }}"
        {{ $attributes->merge([
            'class' => 'w-full h-10 rounded-lg border border-gray-300 bg-white px-3 text-sm text-slate-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition'
        ]) }}
    >

        <option value="">
            {{ $placeholder }}
        </option>

        @foreach($options as $key => $option)

            <option
                value="{{ $key }}"
                @selected((string)$value === (string)$key)
            >
                {{ $option }}
            </option>

        @endforeach

    </select>

</div>
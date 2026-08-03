<div {{ $attributes->merge([
'class' => 'bg-white rounded-2xl border border-gray-200 shadow-md hover:shadow-xl transition-all duration-300 p-6'
]) }}>

    {{ $slot }}

</div>
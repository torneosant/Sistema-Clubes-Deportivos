@props([
    'action' => '',
])

<div class="bg-white rounded-xl shadow-sm border border-gray-200 px-4 py-3 mb-6">

    <form
        method="GET"
        action="{{ $action }}"
        class="flex flex-wrap items-end gap-3"
    >

        {{ $slot }}

    </form>

</div>
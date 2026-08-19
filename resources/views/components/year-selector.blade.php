<div class="text-right">

    <div class="text-xs text-gray-500">
        Año de trabajo
    </div>

    <form method="GET" action="{{ url()->current() }}" class="mt-1">

        <select
            name="anio"
            onchange="this.form.submit()"
          class="w-24 border border-gray-300 rounded-lg px-3 py-1.5
       text-sm font-semibold text-slate-700
       bg-white focus:ring-2 focus:ring-blue-500"
        >

            @foreach($anios as $anio)
                <option
                    value="{{ $anio }}"
                    @selected((int)$anio === (int)$anioTrabajo)
                >
                    {{ $anio }}
                </option>
            @endforeach

        </select>

    </form>

</div>
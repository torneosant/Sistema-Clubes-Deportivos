<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <style>

        @page {
            margin: 25px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            color: #222;
        }

        .titulo {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .competencia {
            text-align: center;
            font-size: 13px;
            margin-bottom: 15px;
        }

        .info {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .info td {
            padding: 5px;
        }

        .planilla {
            width: 100%;
            border-collapse: collapse;
        }

        .planilla th {
            background-color: #e5e5e5;
            border: 1px solid #777;
            padding: 6px;
            font-weight: bold;
            text-align: left;
        }

        .planilla td {
            border: 1px solid #999;
            padding: 6px;
        }

        .numero {
            width: 25px;
            text-align: center;
        }

        .sin-datos {
            text-align: center;
            padding: 20px;
        }

        .pie {
            margin-top: 20px;
            font-size: 8px;
            text-align: center;
        }

    </style>

</head>

<body>

    <div class="titulo">
        PLANILLA DE PARTICIPANTES
    </div>

    <div class="competencia">
        {{ $competencia->nombre }}
    </div>


    <table class="info">

        <tr>

            <td>
                <strong>Categoría:</strong>
                {{ $competencia->categoria?->nombre ?? 'Todas' }}
            </td>

            <td>
                <strong>Lugar:</strong>
                {{ $competencia->lugar ?? '-' }}
            </td>

            <td>
                <strong>Participantes:</strong>
                {{ $jugadores->count() }}
            </td>

        </tr>

        <tr>

            <td>
                <strong>Fecha:</strong>
                {{ $competencia->fecha_inicio?->format('d/m/Y') ?? '-' }}
            </td>

            <td>
                <strong>Tipo:</strong>
                {{ ucfirst($competencia->tipo) }}
            </td>

            <td>
                <strong>Estado:</strong>
                {{ ucfirst($competencia->estado) }}
            </td>

        </tr>

    </table>


    @if($jugadores->count() > 0)

        <table class="planilla">

            <thead>

                <tr>

                    <th class="numero">
                        #
                    </th>

                    @foreach($encabezados as $encabezado)

                        <th>
                            {{ $encabezado }}
                        </th>

                    @endforeach

                </tr>

            </thead>

            <tbody>

                @foreach($jugadores as $index => $jugador)

                    <tr>

                        <td class="numero">
                            {{ $index + 1 }}
                        </td>


                        @foreach($campos as $campo)

                            <td>

                                @switch($campo)

                                    @case('nombres')
                                        {{ $jugador->nombres ?? '-' }}
                                        @break

                                    @case('apellidos')
                                        {{ $jugador->apellidos ?? '-' }}
                                        @break

                                    @case('documento')
                                        {{ $jugador->numero_documento ?? '-' }}
                                        @break

                                    @case('fecha_nacimiento')
                                        {{ $jugador->fecha_nacimiento?->format('d/m/Y') ?? '-' }}
                                        @break

                                    @case('telefono')
                                        {{ $jugador->telefono ?? '-' }}
                                        @break

                                    @case('email')
                                        {{ $jugador->email ?? '-' }}
                                        @break

                                    @case('direccion')
                                        {{ $jugador->direccion ?? '-' }}
                                        @break

                                    @case('eps')
                                        {{ $jugador->eps ?? '-' }}
                                        @break

                                    @case('tipo_sangre')
                                        {{ $jugador->tipo_sangre ?? '-' }}
                                        @break

                                    @case('acudiente')
                                        {{ $jugador->acudiente ?? '-' }}
                                        @break

                                    @case('documento_acudiente')
                                        {{ $jugador->documento_acudiente ?? '-' }}
                                        @break

                                    @case('telefono_acudiente')
                                        {{ $jugador->telefono_acudiente ?? '-' }}
                                        @break

                                    @case('email_acudiente')
                                        {{ $jugador->email_acudiente ?? '-' }}
                                        @break

                                    @case('parentesco')
                                        {{ $jugador->parentesco ?? '-' }}
                                        @break

                                    @default
                                        -

                                @endswitch

                            </td>

                        @endforeach

                    </tr>

                @endforeach

            </tbody>

        </table>

    @else

        <div class="sin-datos">
            No hay jugadores inscritos en esta competencia.
        </div>

    @endif


    <div class="pie">
        Documento generado desde Gestión Clubes
    </div>

</body>

</html>
<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <title>Pendientes de pago</title>

    <style>

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #1f2937;
            margin: 25px;
        }

        h1 {
            font-size: 20px;
            margin-bottom: 5px;
        }

        .subtitulo {
            color: #6b7280;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #1e293b;
            color: white;
            padding: 7px;
            font-size: 9px;
            text-align: left;
        }

        td {
            border: 1px solid #d1d5db;
            padding: 6px;
            font-size: 9px;
        }

        .derecha {
            text-align: right;
        }

        .pendiente {
            color: #b91c1c;
            font-weight: bold;
        }

        .total {
            margin-top: 15px;
            text-align: right;
            font-size: 14px;
            font-weight: bold;
        }

        .vacio {
            text-align: center;
            padding: 20px;
            color: #6b7280;
        }

    </style>

</head>

<body>

    <h1>
        ⏳ Pendientes de pago
    </h1>

    <div class="subtitulo">

        Año {{ $anio }}

        @if($periodoPendientes === 'todos')

            — Todos los meses

        @else

            — {{ $periodoPendientes }}

        @endif

    </div>


    @if($cargosPendientes->count())

        <table>

            <thead>

                <tr>

                    <th>Jugador</th>
                    <th>Concepto</th>
                    <th>Periodo</th>
                    <th>Fecha</th>
                    <th class="derecha">Valor</th>
                    <th class="derecha">Pagado</th>
                    <th class="derecha">Pendiente</th>

                </tr>

            </thead>

            <tbody>

                @foreach($cargosPendientes as $cargo)

                    @php

                        $pendiente = max(
                            0,
                            (float) $cargo->valor -
                            (float) $cargo->valor_pagado
                        );

                    @endphp

                    <tr>

                        <td>
                            {{ $cargo->jugador->apellidos ?? '' }}
                            {{ $cargo->jugador->nombres ?? '' }}
                        </td>

                        <td>
                            {{ $cargo->concepto->nombre ?? '-' }}
                        </td>

                        <td>
                            {{ $cargo->periodo ?? '-' }}
                        </td>

                        <td>
                            {{ $cargo->fecha
                                ? $cargo->fecha->format('d/m/Y')
                                : '-'
                            }}
                        </td>

                        <td class="derecha">
                            ${{ number_format(
                                $cargo->valor,
                                0,
                                ',',
                                '.'
                            ) }}
                        </td>

                        <td class="derecha">
                            ${{ number_format(
                                $cargo->valor_pagado,
                                0,
                                ',',
                                '.'
                            ) }}
                        </td>

                        <td class="derecha pendiente">
                            ${{ number_format(
                                $pendiente,
                                0,
                                ',',
                                '.'
                            ) }}
                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>


        <div class="total">

            Total pendiente:

            ${{ number_format(
                $totalPendiente,
                0,
                ',',
                '.'
            ) }}

        </div>

    @else

        <div class="vacio">

            🎉 No hay pendientes de pago.

        </div>

    @endif

</body>

</html>
<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <title>Movimientos contables</title>

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

        .ingreso {
            color: #15803d;
            font-weight: bold;
        }

        .egreso {
            color: #b91c1c;
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
        📒 Movimientos contables
    </h1>

    <div class="subtitulo">
        Año {{ $anio }}
    </div>


    @if($movimientos->count())

        <table>

            <thead>

                <tr>

                    <th>Fecha</th>
                    <th>Tipo</th>
                    <th>Concepto</th>
                    <th>Jugador / Tercero</th>
                    <th>Método</th>
                    <th class="derecha">Valor</th>

                </tr>

            </thead>


            <tbody>

                @foreach($movimientos as $movimiento)

                    <tr>

                        <td>
                            {{ $movimiento->fecha
                                ? $movimiento->fecha->format('d/m/Y')
                                : '-'
                            }}
                        </td>


                        <td>

                            @if($movimiento->tipo === 'Ingreso')

                                <span class="ingreso">
                                    Ingreso
                                </span>

                            @else

                                <span class="egreso">
                                    Egreso
                                </span>

                            @endif

                        </td>


                        <td>
                            {{ $movimiento->concepto->nombre ?? '-' }}
                        </td>


                        <td>

                            @if($movimiento->jugador)

                                {{ $movimiento->jugador->apellidos }}
                                {{ $movimiento->jugador->nombres }}

                            @elseif($movimiento->tercero)

                                {{ $movimiento->tercero }}

                            @else

                                -

                            @endif

                        </td>


                        <td>
                            {{ $movimiento->metodo_pago ?? '-' }}
                        </td>


                        <td class="derecha">

                            ${{ number_format(
                                $movimiento->valor,
                                0,
                                ',',
                                '.'
                            ) }}

                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    @else

        <div class="vacio">

            No hay movimientos para los filtros seleccionados.

        </div>

    @endif

</body>

</html>
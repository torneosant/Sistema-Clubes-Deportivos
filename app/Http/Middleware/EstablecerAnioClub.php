<?php

namespace App\Http\Middleware;

use App\Models\Configuracion;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EstablecerAnioClub
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {

            $clubId = auth()->user()->club_id;

            $configuracion = Configuracion::find($clubId);

            $anioConfigurado = $configuracion?->anio ?? date('Y');

            $anioTrabajo = session('anio_trabajo', $anioConfigurado);

$anios = collect(range(
    $anioConfigurado - 5,
    $anioConfigurado + 1
))->sortDesc()->values()->all();

view()->share('anioTrabajo', $anioTrabajo);
view()->share('anioConfigurado', $anioConfigurado);
view()->share('anios', $anios);
        }

        return $next($request);
    }
}
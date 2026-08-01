<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermisoMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $permiso): Response
    {
        if (!auth()->check()) {
            abort(403);
        }

        if (!auth()->user()->tienePermiso($permiso)) {

            abort(403, 'No tiene permisos para acceder a este módulo.');

        }

        return $next($request);
    }
}
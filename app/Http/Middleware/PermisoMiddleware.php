<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermisoMiddleware
{
    public function handle(Request $request, Closure $next, string $modulo): Response
    {
        if (!auth()->check()) {
            abort(403);
        }

        $accion = $this->obtenerAccion($request);

        $permiso = $modulo;

        // Si no viene un permiso específico (.ver, .crear, etc.)
        // lo construimos automáticamente.
        if (!str_contains($modulo, '.')) {
            $permiso = $modulo . '.' . $accion;
        }

        if (!auth()->user()->tienePermiso($permiso)) {
            abort(403, "No tiene permiso: {$permiso}");
        }

        return $next($request);
    }

    private function obtenerAccion(Request $request): string
    {
        $route = $request->route();

        if (!$route) {
            return 'ver';
        }

        $action = $route->getActionMethod();

        return match ($action) {

            // Ver
            'index',
            'show',
            'pdf',
            'excel',
            'print',
            'imprimir',
            'trazabilidad',
            'resultado',
            'general',
            'redes',
            'deportivo',
            'sistema'
                => 'ver',

            // Crear
            'create',
            'store'
                => 'crear',

            // Editar
            'edit',
            'update',
            'cambiarEstado',
            'guardarResultado',
            'devolver'
                => 'editar',

            // Eliminar
            'destroy'
                => 'eliminar',

            default => 'ver',
        };
    }
}
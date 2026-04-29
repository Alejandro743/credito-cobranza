<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Si el usuario tiene un rol desactivado (activo = false),
 * lo redirige a la página de acceso desactivado.
 * El rol "admin" nunca puede desactivarse, así que siempre pasa.
 */
class EnsureRoleIsActivo
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return $next($request);
        }

        // Permitir siempre la ruta de desactivado y logout (evita redireccionamiento infinito)
        if ($request->routeIs('access.desactivado', 'logout')) {
            return $next($request);
        }

        $role = $user->roles->first();

        // Sin rol asignado → dejar pasar (otro middleware lo controla)
        if (!$role) {
            return $next($request);
        }

        // Admin siempre activo
        if ($role->name === 'admin') {
            return $next($request);
        }

        // Rol desactivado → redirigir a página de aviso (sin cerrar sesión)
        if (isset($role->activo) && !$role->activo) {
            return redirect()->route('access.desactivado');
        }

        return $next($request);
    }
}

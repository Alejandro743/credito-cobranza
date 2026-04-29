<?php

namespace App\Http\Middleware;

use App\Services\PermisoService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Verifica que el usuario tenga acceso (ver) al submódulo
 * asociado a la ruta actual.
 */
class CheckSubmoduloPermiso
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->hasRole('admin')) {
            return $next($request);
        }

        $routeName = $request->route()?->getName() ?? '';

        if (!PermisoService::checkByRoute($user, $routeName)) {
            abort(403, 'No tenés acceso a esta sección.');
        }

        return $next($request);
    }
}

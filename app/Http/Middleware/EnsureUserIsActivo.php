<?php

namespace App\Http\Middleware;

use App\Models\Cliente;
use App\Models\Vendedor;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Bloquea el acceso si el usuario está vinculado a un vendedor o cliente inactivo.
 */
class EnsureUserIsActivo
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (!$user) return $next($request);

        $inactivo =
            Vendedor::where('user_id', $user->id)->where('activo', false)->exists() ||
            Cliente::where('user_id',  $user->id)->where('activo', false)->exists();

        if ($inactivo) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->withErrors(['email' => 'Tu cuenta está desactivada. Contactá al administrador.']);
        }

        return $next($request);
    }
}

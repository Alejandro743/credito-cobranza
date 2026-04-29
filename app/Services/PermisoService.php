<?php

namespace App\Services;

use App\Models\RolSubmoduloPermiso;
use App\Models\Submodulo;
use App\Models\User;

/**
 * Verifica permisos de la tabla rol_submodulo_permiso (campo puede_ver).
 *
 * El rol "admin" siempre pasa (bypass total).
 *
 * Caché por request: detecta cambio de objeto Request para resetear
 * automáticamente entre requests en el mismo proceso PHP.
 */
class PermisoService
{
    private static array $cache     = [];
    private static int   $requestId = 0;

    private static function boot(): void
    {
        $currentId = spl_object_id(request());
        if (self::$requestId !== $currentId) {
            self::$cache     = [];
            self::$requestId = $currentId;
        }
    }

    /** Mapa completo: submodulo_id → puede_ver (una sola query por request) */
    private static function mapaRol(int $roleId): array
    {
        $key = "map:{$roleId}";
        if (!array_key_exists($key, self::$cache)) {
            self::$cache[$key] = RolSubmoduloPermiso::where('role_id', $roleId)
                ->get()
                ->keyBy('submodulo_id')
                ->map(fn($p) => (bool) $p->puede_ver)
                ->toArray();
        }
        return self::$cache[$key];
    }

    private static function subIdPorSlug(string $slug): ?int
    {
        $key = "sub:{$slug}";
        if (!array_key_exists($key, self::$cache)) {
            $sub = Submodulo::where('slug', $slug)->where('active', true)->first();
            self::$cache[$key] = $sub?->id;
        }
        return self::$cache[$key];
    }

    private static function slugPorRoute(string $routeName): ?string
    {
        $key = "route:{$routeName}";
        if (!array_key_exists($key, self::$cache)) {
            $sub = Submodulo::where('route_name', $routeName)->where('active', true)->first();
            self::$cache[$key] = $sub?->slug;
        }
        return self::$cache[$key];
    }

    // ── API pública ──────────────────────────────────────────────────────────

    public static function check(User $user, string $submoduloSlug): bool
    {
        if ($user->hasRole('admin')) return true;

        self::boot();

        $roleId = $user->roles->first()?->id;
        if (!$roleId) return false;

        $subId = self::subIdPorSlug($submoduloSlug);
        if (!$subId) return false;

        return self::mapaRol($roleId)[$subId] ?? false;
    }

    /**
     * Verifica por route_name. Si la ruta no está en BD → pasa libre.
     */
    public static function checkByRoute(User $user, string $routeName): bool
    {
        if ($user->hasRole('admin')) return true;

        self::boot();

        $slug = self::slugPorRoute($routeName);
        if (!$slug) return true;

        return self::check($user, $slug);
    }

    public static function clearCache(): void
    {
        self::$cache     = [];
        self::$requestId = 0;
    }
}

<?php

namespace Database\Seeders;

use App\Models\RolSubmoduloPermiso;
use App\Models\Submodulo;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

/**
 * Siembra permisos puede_ver por defecto para cada rol.
 *
 * admin    → todos los submódulos con ruta (wildcard *)
 * credito  → submodulos del módulo Crédito/Cobranza
 * vendedor → submodulos del módulo Vendedor/EIE
 * cliente  → submodulos del módulo Cliente
 */
class RolesPermisosDefaultSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'admin'   => ['*'],
            'credito' => [
                'credito-pedidos', 'credito-aprobacion', 'credito-cobranza', 'credito-reprogramacion',
            ],
            'vendedor' => [
                'vendedor-clientes', 'vendedor-oferta', 'vendedor-pedidos', 'vendedor-pagos-saldos',
            ],
            'cliente' => [
                'cliente-cuenta', 'cliente-pedidos', 'cliente-plan', 'cliente-cuotas', 'cliente-pagos',
            ],
        ];

        foreach ($defaults as $roleName => $slugs) {
            $role = Role::where('name', $roleName)->first();
            if (!$role) continue;

            if ($slugs === ['*']) {
                // Admin: todos los submodulos hoja activos (los que tienen route_name)
                Submodulo::where('active', true)
                    ->whereNotNull('route_name')
                    ->each(function (Submodulo $sub) use ($role) {
                        RolSubmoduloPermiso::updateOrCreate(
                            ['role_id' => $role->id, 'submodulo_id' => $sub->id],
                            ['puede_ver' => true]
                        );
                    });
            } else {
                foreach ($slugs as $slug) {
                    $sub = Submodulo::where('slug', $slug)->first();
                    if (!$sub) continue;

                    RolSubmoduloPermiso::updateOrCreate(
                        ['role_id' => $role->id, 'submodulo_id' => $sub->id],
                        ['puede_ver' => true]
                    );
                }
            }
        }
    }
}

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
                'credito-clientes', 'credito-asignar', 'credito-historico', 'credito-cobranza', 'credito-espera', 'credito-revision',
                'credito-aprobado', 'credito-cerrado', 'credito-pagos-pasarela', 'credito-pagos-manuales',
                'credito-reprog-nueva', 'credito-reprog-historial', 'credito-pagos-historial',
                'credito-indicadores-calificacion', 'credito-indicadores-calificacion-clientes',
                'def-motivo-cierre',
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

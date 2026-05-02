<?php

namespace Database\Seeders;

use App\Models\Modulo;
use App\Models\Submodulo;
use Illuminate\Database\Seeder;

/**
 * Fuente de verdad de la estructura de menú.
 *
 * Estructura:
 *   Módulo → SubMódulo raíz (parent_id = null)
 *                → Hoja con ruta  (tiene route_name, parent_id = raíz)
 *   o
 *   Módulo → Submodulo hoja directa (route_name, parent_id = null)
 *
 * Las hojas con route_name son las que se asignan en rol_submodulo_permiso.
 */
class ModulosSubmodulosSeeder extends Seeder
{
    // Icono SVG path para módulos
    private const ICONS = [
        'admin'    => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065zM15 12a3 3 0 11-6 0 3 3 0 016 0z',
        'credito'  => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z',
        'vendedor' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z',
        'cliente'  => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
    ];

    public function run(): void
    {
        $estructura = [

            // ── MÓDULO ADMINISTRATIVO ─────────────────────────────────────────
            [
                'name'  => 'Administrativo', 'slug' => 'administrativo',
                'color' => 'lavanda', 'icon' => self::ICONS['admin'], 'sort_order' => 1,
                'submodulos' => [
                    // Grupo: Seguridad (sub-hojas protegidas por role:admin)
                    [
                        'name' => 'Seguridad', 'slug' => 'seguridad',
                        'route_name' => null, 'sort_order' => 1,
                        'children' => [
                            ['name' => 'Usuarios',        'slug' => 'seg-usuarios', 'route_name' => 'admin.security.users', 'sort_order' => 1],
                            ['name' => 'Roles y Permisos','slug' => 'seg-roles',    'route_name' => 'admin.security.roles', 'sort_order' => 2],
                        ],
                    ],
                    // Grupo: Catálogo
                    [
                        'name' => 'Catálogo', 'slug' => 'catalogo',
                        'route_name' => null, 'sort_order' => 2,
                        'children' => [
                            ['name' => 'Productos',        'slug' => 'cat-productos',  'route_name' => 'admin.catalogo.productos',  'sort_order' => 1],
                            ['name' => 'Categorías',       'slug' => 'cat-categorias', 'route_name' => 'admin.catalogo.categorias', 'sort_order' => 2],
                            ['name' => 'Unidades',         'slug' => 'cat-unidades',   'route_name' => 'admin.catalogo.unidades',   'sort_order' => 3],
                            ['name' => 'Listas de Precios','slug' => 'cat-listas',     'route_name' => 'admin.catalogo.listas',     'sort_order' => 4],
                        ],
                    ],
                    // Grupo: Definiciones
                    [
                        'name' => 'Definiciones', 'slug' => 'definiciones',
                        'route_name' => null, 'sort_order' => 3,
                        'children' => [
                            ['name' => 'Correlativo',           'slug' => 'def-correlativo',        'route_name' => 'admin.definiciones.correlativo',        'sort_order' => 1],
                            ['name' => 'Pesos de Indicadores',  'slug' => 'def-peso-indicadores',   'route_name' => 'admin.definiciones.peso-indicadores',   'sort_order' => 2],
                            ['name' => 'Rangos de Calificación','slug' => 'def-rango-calificacion', 'route_name' => 'admin.definiciones.rango-calificacion', 'sort_order' => 3],
                        ],
                    ],
                    // Grupo: Configuración del Ciclo
                    [
                        'name' => 'Configuración del Ciclo', 'slug' => 'config-ciclo',
                        'route_name' => null, 'sort_order' => 4,
                        'children' => [
                            ['name' => 'Ciclos Comerciales', 'slug' => 'ciclos-comerciales',  'route_name' => 'admin.ciclo.ciclos',   'sort_order' => 1],
                            ['name' => 'Puntos',             'slug' => 'ciclo-puntos',         'route_name' => 'admin.ciclo.puntos',   'sort_order' => 2],
                            ['name' => 'Matriz Financiera',  'slug' => 'matriz-financiera',    'route_name' => 'admin.finance.index',  'sort_order' => 3],
                        ],
                    ],
                ],
            ],

            // ── MÓDULO CRÉDITO / COBRANZA ─────────────────────────────────────
            [
                'name'  => 'Crédito / Cobranza', 'slug' => 'credito',
                'color' => 'mint', 'icon' => self::ICONS['credito'], 'sort_order' => 2,
                'submodulos' => [
                    [
                        'name' => 'Gestión de Crédito', 'slug' => 'credito-gestion',
                        'route_name' => null, 'sort_order' => 1,
                        'children' => [
                            ['name' => 'Clientes',                'slug' => 'credito-clientes',  'route_name' => 'admin.credito.clientes', 'sort_order' => 1],
                            ['name' => 'En Espera de Aprobación', 'slug' => 'credito-espera',    'route_name' => 'credito.espera',         'sort_order' => 2],
                            ['name' => 'En Revisión',             'slug' => 'credito-revision',  'route_name' => 'credito.revision',       'sort_order' => 3],
                            ['name' => 'Aprobado/Rechazado',      'slug' => 'credito-aprobado',  'route_name' => 'credito.aprobado',       'sort_order' => 4],
                        ],
                    ],
                    ['name' => 'Cobranza',       'slug' => 'credito-cobranza',       'route_name' => 'credito.cobranza',       'sort_order' => 2, 'children' => []],
                    [
                        'name' => 'Reprogramación', 'slug' => 'credito-reprogramacion',
                        'route_name' => null, 'sort_order' => 3,
                        'children' => [
                            ['name' => 'Nueva Reprogramación', 'slug' => 'credito-reprog-nueva',     'route_name' => 'credito.reprogramacion.nueva',     'sort_order' => 1],
                            ['name' => 'Historial',            'slug' => 'credito-reprog-historial',  'route_name' => 'credito.reprogramacion.historial',  'sort_order' => 2],
                        ],
                    ],
                    [
                        'name' => 'Gestión de Pagos', 'slug' => 'credito-pagos',
                        'route_name' => null, 'sort_order' => 4,
                        'children' => [
                            ['name' => 'Pagos por Pasarela', 'slug' => 'credito-pagos-pasarela', 'route_name' => 'credito.pagos-pasarela', 'sort_order' => 1],
                            ['name' => 'Pagos Manuales',     'slug' => 'credito-pagos-manuales', 'route_name' => 'credito.pagos-manuales', 'sort_order' => 2],
                            ['name' => 'Historial de Pagos', 'slug' => 'credito-pagos-historial', 'route_name' => 'credito.pagos-historial', 'sort_order' => 3],
                        ],
                    ],
                    [
                        'name' => 'Indicadores', 'slug' => 'credito-indicadores',
                        'route_name' => null, 'sort_order' => 5,
                        'children' => [
                            ['name' => 'Calificación de Cartera', 'slug' => 'credito-indicadores-calificacion', 'route_name' => 'credito.indicadores.calificacion', 'sort_order' => 1],
                        ],
                    ],
                ],
            ],

            // ── MÓDULO VENDEDOR / EIE ─────────────────────────────────────────
            [
                'name'  => 'Vendedor / EIE', 'slug' => 'vendedor',
                'color' => 'melocoton', 'icon' => self::ICONS['vendedor'], 'sort_order' => 3,
                'submodulos' => [
                    [
                        'name' => 'Gestión de Créditos', 'slug' => 'vendedor-gestion-planes',
                        'route_name' => null, 'sort_order' => 1,
                        'children' => [
                            ['name' => 'Mis Clientes',         'slug' => 'vendedor-clientes',     'route_name' => 'vendedor.clientes',     'sort_order' => 1],
                            ['name' => 'Registrar Nuevo Plan', 'slug' => 'vendedor-oferta',       'route_name' => 'vendedor.oferta',       'sort_order' => 2],
                            ['name' => 'Revisión del Crédito', 'slug' => 'vendedor-pedidos',      'route_name' => 'vendedor.pedidos',      'sort_order' => 3],
                            ['name' => 'Pagos y Saldos',       'slug' => 'vendedor-pagos-saldos', 'route_name' => 'vendedor.pagos-saldos', 'sort_order' => 4],
                        ],
                    ],
                ],
            ],

            // ── MÓDULO CLIENTE ────────────────────────────────────────────────
            [
                'name'  => 'Cliente', 'slug' => 'cliente',
                'color' => 'celeste', 'icon' => self::ICONS['cliente'], 'sort_order' => 4,
                'submodulos' => [
                    ['name' => 'Mi Cuenta',       'slug' => 'cliente-cuenta',  'route_name' => 'cliente.cuenta',  'sort_order' => 1, 'children' => []],
                    ['name' => 'Mis Pedidos',      'slug' => 'cliente-pedidos', 'route_name' => 'cliente.pedidos', 'sort_order' => 2, 'children' => []],
                    ['name' => 'Mi Plan de Pago',  'slug' => 'cliente-plan',    'route_name' => 'cliente.plan',    'sort_order' => 3, 'children' => []],
                    ['name' => 'Mis Cuotas',       'slug' => 'cliente-cuotas',  'route_name' => 'cliente.cuotas',  'sort_order' => 4, 'children' => []],
                    ['name' => 'Mis Pagos',        'slug' => 'cliente-pagos',   'route_name' => 'cliente.pagos',   'sort_order' => 5, 'children' => []],
                ],
            ],
        ];

        // Eliminar submodulos obsoletos que fueron reestructurados
        Submodulo::whereIn('slug', ['listas-precios', 'lista-maestra', 'listas-derivadas', 'grupos', 'reglas', 'cat-grupos', 'config-financiera', 'cat-clientes', 'admin-clientes', 'admin-clientes-gestion', 'credito-pedidos', 'credito-aprobacion'])->delete();

        foreach ($estructura as $moduloData) {
            $submodulosData = $moduloData['submodulos'];
            unset($moduloData['submodulos']);

            $modulo = Modulo::updateOrCreate(
                ['slug' => $moduloData['slug']],
                $moduloData
            );

            foreach ($submodulosData as $subData) {
                $children = $subData['children'] ?? [];
                unset($subData['children']);
                $subData['modulo_id'] = $modulo->id;
                $subData['parent_id'] = null;

                $submodulo = Submodulo::updateOrCreate(
                    ['modulo_id' => $modulo->id, 'slug' => $subData['slug']],
                    $subData
                );

                foreach ($children as $childData) {
                    $childData['modulo_id'] = $modulo->id;
                    $childData['parent_id'] = $submodulo->id;

                    Submodulo::updateOrCreate(
                        ['modulo_id' => $modulo->id, 'slug' => $childData['slug']],
                        $childData
                    );
                }
            }
        }
    }
}

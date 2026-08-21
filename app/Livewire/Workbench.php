<?php

namespace App\Livewire;

use App\Services\PermisoService;
use Livewire\Component;

class Workbench extends Component
{
    /** slug (submodulo) => pestaña abierta */
    public array $openTabs = [];
    public ?string $activeTab = null;

    /**
     * Registro de pestañas disponibles. Cada clave es el slug del submodulo
     * (tabla `submodulos`) y controla el permiso vía PermisoService.
     *
     * 'admin' => true fuerza además el rol Spatie admin (pantallas de Seguridad),
     * igual que la ruta original protegida por el middleware role:admin.
     *
     * Las pantallas "en desarrollo" (sin componente Livewire real) no están
     * listadas acá a propósito: para esas, el menú sigue navegando normal.
     */
    private const TABS = [
        // ── Administrativo / Seguridad ──────────────────────────────────────
        'seg-usuarios'    => ['label' => 'Usuarios',          'component' => 'admin.security.user-manager',       'admin' => true],
        'seg-vendedores'  => ['label' => 'Vendedores',        'component' => 'admin.vendedores.vendedor-manager', 'admin' => true],
        'seg-roles'       => ['label' => 'Roles y Permisos',  'component' => 'admin.security.role-manager',       'admin' => true],

        // ── Administrativo / Catálogo ────────────────────────────────────────
        'cat-categorias'        => ['label' => 'Categorías',        'component' => 'admin.categorias.categoria-manager'],
        'cat-unidades'          => ['label' => 'Unidades',          'component' => 'admin.unidades.unidad-manager'],
        'cat-maestro-articulos' => ['label' => 'Maestro Artículos', 'component' => 'admin.listas.maestro-articulos-manager'],
        'cat-listas'            => ['label' => 'Listas de Precios', 'component' => 'admin.listas.lista-maestra-manager'],

        // ── Administrativo / Definiciones ────────────────────────────────────
        'def-correlativo'          => ['label' => 'Correlativo',            'component' => 'admin.definiciones.correlativo-manager'],
        'def-matrices-financieras' => ['label' => 'Matrices Financieras',   'component' => 'admin.definiciones.matriz-financiera-manager'],
        'def-peso-indicadores'     => ['label' => 'Pesos de Indicadores',   'component' => 'admin.definiciones.peso-indicador-manager'],
        'def-rango-calificacion'   => ['label' => 'Rangos de Calificación', 'component' => 'admin.definiciones.rango-calificacion-manager'],
        'def-motivo-cierre'        => ['label' => 'Motivos de Cierre',      'component' => 'admin.definiciones.motivo-cierre-manager'],

        // ── Administrativo / Configuración del Ciclo ─────────────────────────
        'ciclos-comerciales' => ['label' => 'Ciclos Comerciales', 'component' => 'admin.cycles.cycle-manager'],
        'ciclo-puntos'       => ['label' => 'Puntos',             'component' => 'admin.ciclo.configuracion-puntos-manager'],

        // ── Crédito/Cobranza / Gestión de Crédito ────────────────────────────
        'credito-clientes' => ['label' => 'Datos Clientes',                 'component' => 'credito.cliente-manager'],
        'credito-asignar'  => ['label' => 'Asignar Credito',                'component' => 'credito.asignar-credito-manager'],
        'credito-historico'=> ['label' => 'Histórico de Créditos',          'component' => 'credito.historico-creditos-manager'],
        'credito-espera'   => ['label' => 'Pendiente de Revisión',          'component' => 'credito.espera-manager'],
        'credito-revision' => ['label' => 'En Proceso de Revisión',         'component' => 'credito.revision-manager'],
        'credito-aprobado' => ['label' => 'Credito Aprobado/Rechazado/Cerrado', 'component' => 'credito.aprobado-manager'],

        // ── Crédito/Cobranza / Cobranza ──────────────────────────────────────
        'cobranza-bandeja'             => ['label' => 'Bandeja de Asignación', 'component' => 'credito.bandeja-asignacion'],
        'cobranza-mis-casos'           => ['label' => 'Mis Casos',             'component' => 'credito.mis-casos'],
        'cobranza-mis-actividades'     => ['label' => 'Mis Actividades',       'component' => 'credito.mis-actividades'],
        'cobranza-actividades-masivas' => ['label' => 'Actividades Masivas',   'component' => 'credito.actividades-masivas'],

        // ── Crédito/Cobranza / Definiciones Cobranza (mismo componente, distinto tipo) ──
        'cob-def-tipos-contacto'      => ['label' => 'Tipos de Contacto',     'component' => 'credito.definicion-manager', 'params' => ['tipo' => 'tipo_contacto',      'titulo' => 'Tipos de Contacto']],
        'cob-def-acciones'            => ['label' => 'Acciones',              'component' => 'credito.definicion-manager', 'params' => ['tipo' => 'accion',              'titulo' => 'Acciones']],
        'cob-def-tipos-respuesta'     => ['label' => 'Tipos de Respuesta',    'component' => 'credito.definicion-manager', 'params' => ['tipo' => 'tipo_respuesta',      'titulo' => 'Tipos de Respuesta']],
        'cob-def-motivos-cierre'      => ['label' => 'Motivos de Cierre',     'component' => 'credito.definicion-manager', 'params' => ['tipo' => 'motivo_cierre',       'titulo' => 'Motivos de Cierre']],
        'cob-def-motivos-cancelacion' => ['label' => 'Motivos de Cancelación','component' => 'credito.definicion-manager', 'params' => ['tipo' => 'motivo_cancelacion',  'titulo' => 'Motivos de Cancelación']],
        'cob-def-estados-caso'        => ['label' => 'Estados de Caso',       'component' => 'credito.definicion-manager', 'params' => ['tipo' => 'estado_caso',         'titulo' => 'Estados de Caso']],
        'cob-def-estados-activ'       => ['label' => 'Estados de Actividad',  'component' => 'credito.definicion-manager', 'params' => ['tipo' => 'estado_actividad',    'titulo' => 'Estados de Actividad']],
        'cob-def-tipos-cancelacion'   => ['label' => 'Tipos de Cancelación',  'component' => 'credito.definicion-manager', 'params' => ['tipo' => 'tipo_cancelacion',    'titulo' => 'Tipos de Cancelación']],

        // ── Crédito/Cobranza / Reprogramación ────────────────────────────────
        'credito-reprog-nueva'     => ['label' => 'Nueva Reprogramación', 'component' => 'credito.reprogramacion-nueva'],
        'credito-reprog-historial' => ['label' => 'Historial',            'component' => 'credito.reprogramacion-historial'],

        // ── Crédito/Cobranza / Gestión de Pagos ──────────────────────────────
        // credito-pagos-pasarela: pantalla "en desarrollo", sin componente real.
        'credito-pagos-manuales'  => ['label' => 'Pagos Manuales',     'component' => 'credito.pago-manual'],
        'credito-pagos-historial' => ['label' => 'Historial de Pagos', 'component' => 'credito.pago-historial'],

        // ── Crédito/Cobranza / Indicadores ───────────────────────────────────
        'credito-indicadores-calificacion'          => ['label' => 'Calificación de Cartera',  'component' => 'credito.indicadores.calificacion-vendedor'],
        'credito-indicadores-calificacion-clientes' => ['label' => 'Calificación de Clientes', 'component' => 'credito.indicadores.calificacion-cliente'],

        // ── Vendedor / EIE ────────────────────────────────────────────────────
        'vendedor-clientes'     => ['label' => 'Mis Clientes',         'component' => 'vendedor.cliente-manager'],
        'vendedor-oferta'       => ['label' => 'Registrar Nuevo Plan', 'component' => 'vendedor.oferta-manager'],
        'vendedor-pedidos'      => ['label' => 'Revisión del Crédito', 'component' => 'vendedor.pedido-manager', 'params' => ['enWorkbench' => true]],
        'vendedor-pagos-saldos' => ['label' => 'Historial de Pagos',   'component' => 'vendedor.pago-historial'],

        // ── Cliente ───────────────────────────────────────────────────────────
        // cliente-cuenta, cliente-pagos: pantallas "en desarrollo", sin componente real.
        'cliente-pedidos'         => ['label' => 'Mis Pedidos',     'component' => 'cliente.mis-pedidos'],
        'cliente-plan'            => ['label' => 'Mi Plan de Pago', 'component' => 'cliente.mis-pedidos'],
        'cliente-cuotas'          => ['label' => 'Mis Cuotas',      'component' => 'cliente.mis-pedidos'],
        'cliente-mi-calificacion' => ['label' => 'Mi Calificación', 'component' => 'cliente.mi-calificacion'],
    ];

    /** Slugs que el menú lateral debe abrir como pestaña en vez de navegar. */
    public static function slugsDisponibles(): array
    {
        return array_keys(self::TABS);
    }

    public function mount(): void
    {
        $tieneAlgunAcceso = collect(array_keys(self::TABS))->contains(fn ($key) => $this->puedeAbrir($key));
        abort_unless($tieneAlgunAcceso, 403);

        $inicial = (string) request()->query('tab', '');
        if ($inicial !== '' && $this->puedeAbrir($inicial)) {
            $this->abrirPestana($inicial);
        }
    }

    private function puedeAbrir(string $key): bool
    {
        if (!isset(self::TABS[$key])) return false;

        $tab  = self::TABS[$key];
        $user = auth()->user();

        if (!empty($tab['admin']) && !$user->hasRole('admin')) {
            return false;
        }

        return PermisoService::check($user, $key);
    }

    public function abrirPestana(string $key): void
    {
        if (!$this->puedeAbrir($key)) return;

        if (!in_array($key, $this->openTabs, true)) {
            $this->openTabs[] = $key;
        }
        $this->activeTab = $key;
        $this->dispatch('workbench-tab-changed', slug: $this->activeTab ?? '');
    }

    public function cerrarPestana(string $key): void
    {
        $this->openTabs = array_values(array_filter($this->openTabs, fn ($k) => $k !== $key));

        if ($this->activeTab === $key) {
            $this->activeTab = $this->openTabs[count($this->openTabs) - 1] ?? null;
        }
        $this->dispatch('workbench-tab-changed', slug: $this->activeTab ?? '');
    }

    public function render()
    {
        return view('livewire.workbench', [
            'tabsInfo' => self::TABS,
        ]);
    }
}

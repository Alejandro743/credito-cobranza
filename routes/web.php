<?php

use App\Http\Controllers\ProfileController;
use App\Models\RolSubmoduloPermiso;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('welcome'));

// ─── Redirect dinámico según permisos ─────────────────────────────────────────
Route::middleware('auth')->get('/administrativo/dashboard', fn() => view('modules.administrativo.dashboard'))->name('administrativo.dashboard');

Route::get('/dashboard', function () {
    $user = auth()->user();

    return match($user->tipo) {
        'vendedor' => redirect()->route('vendedor.dashboard'),
        'cliente'  => redirect()->route('cliente.dashboard'),
        default    => redirect()->route('administrativo.dashboard'),
    };

})->middleware(['auth', 'verified'])->name('dashboard');

// ─── Admin: seguridad (solo rol admin) ───────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');

    Route::prefix('security')->name('security.')->group(function () {
        Route::get('/users', fn() => view('admin.security.users'))->name('users');
        Route::get('/roles', fn() => view('admin.security.roles'))->name('roles');
    });
});

// ─── Admin: contenido (cualquier rol con permiso en BD) ───────────────────────
Route::middleware(['auth', 'submodulo.permiso'])->prefix('admin')->name('admin.')->group(function () {

    // Catálogo
    Route::prefix('catalogo')->name('catalogo.')->group(function () {
        Route::get('/productos',  fn() => view('admin.catalogo.productos.index'))->name('productos');
        Route::get('/categorias', fn() => view('admin.catalogo.categorias.index'))->name('categorias');
        Route::get('/unidades',   fn() => view('admin.catalogo.unidades.index'))->name('unidades');
        Route::get('/listas',           fn() => view('admin.catalogo.listas.index'))->name('listas');
        Route::get('/maestro-articulos',  fn() => view('admin.catalogo.maestro-articulos.index'))->name('maestro-articulos');
        Route::get('/stock-articulos',    fn() => view('admin.catalogo.stock-articulos.index'))->name('stock-articulos');
    });

    // Configuración del Ciclo
    Route::prefix('ciclo')->name('ciclo.')->group(function () {
        Route::get('/ciclos', fn() => view('admin.ciclo.ciclos.index'))->name('ciclos');
        Route::get('/puntos', fn() => view('admin.ciclo.puntos.index'))->name('puntos');
    });

    // Config. Financiera (deshabilitada)
    Route::prefix('finance')->name('finance.')->group(function () {
        Route::get('/', fn() => abort(404))->name('index');
    });

    // Definiciones
    Route::prefix('definiciones')->name('definiciones.')->group(function () {
        Route::get('/correlativo',          fn() => view('admin.definiciones.correlativo.index'))->name('correlativo');
        Route::get('/matrices-financieras', fn() => view('admin.definiciones.matrices-financieras.index'))->name('matrices-financieras');
        Route::get('/peso-indicadores',     fn() => view('admin.definiciones.peso-indicadores.index'))->name('peso-indicadores');
        Route::get('/rango-calificacion',   fn() => view('admin.definiciones.rango-calificacion.index'))->name('rango-calificacion');
        Route::get('/motivo-cierre',        fn() => view('admin.definiciones.motivo-cierre.index'))->name('motivo-cierre');
    });

    // Clientes (admin)
    Route::get('/clientes', fn() => view('admin.clientes.index'))->name('clientes.index');

    // Clientes (crédito/cobranza - ruta admin.credito.clientes)
    Route::get('/credito/clientes', fn() => view('modules.credito.clientes'))->name('credito.clientes');
});

// ─── Módulo Crédito / Cobranza ────────────────────────────────────────────────
Route::middleware(['auth', 'submodulo.permiso'])->prefix('credito')->name('credito.')->group(function () {
    Route::get('/dashboard',     fn() => view('modules.credito.dashboard'))->name('dashboard');
    Route::get('/espera',        fn() => view('modules.credito.espera'))->name('espera');
    Route::get('/revision',      fn() => view('modules.credito.revision'))->name('revision');
    Route::get('/aprobado',      fn() => view('modules.credito.aprobado'))->name('aprobado');
    Route::get('/cerrado',       fn() => redirect()->route('credito.aprobado'))->name('cerrado');
    Route::get('/cobranza',        fn() => view('modules.credito.cobranza'))->name('cobranza');
    Route::get('/cobranza/bandeja', fn() => view('modules.credito.bandeja-asignacion'))->name('cobranza.bandeja');
    Route::get('/cobranza/mis-casos',        fn() => view('modules.credito.mis-casos'))->name('cobranza.mis-casos');
    Route::get('/cobranza/mis-actividades', fn() => view('modules.credito.mis-actividades'))->name('cobranza.mis-actividades');
    Route::get('/cobranza/actividades-masivas', fn() => view('modules.credito.actividades-masivas'))->name('cobranza.actividades-masivas');

    // Definiciones Cobranza
    Route::get('/cobranza/def/tipos-contacto',  fn() => view('modules.credito.cobranza-def', ['tipo' => 'tipo_contacto',  'titulo' => 'Tipos de Contacto']))->name('cobranza.def.tipos-contacto');
    Route::get('/cobranza/def/acciones',         fn() => view('modules.credito.cobranza-def', ['tipo' => 'accion',          'titulo' => 'Acciones']))->name('cobranza.def.acciones');
    Route::get('/cobranza/def/tipos-respuesta',  fn() => view('modules.credito.cobranza-def', ['tipo' => 'tipo_respuesta',  'titulo' => 'Tipos de Respuesta']))->name('cobranza.def.tipos-respuesta');
    Route::get('/cobranza/def/motivos-cierre',       fn() => view('modules.credito.cobranza-def', ['tipo' => 'motivo_cierre',      'titulo' => 'Motivos de Cierre']))->name('cobranza.def.motivos-cierre');
    Route::get('/cobranza/def/motivos-cancelacion',  fn() => view('modules.credito.cobranza-def', ['tipo' => 'motivo_cancelacion',  'titulo' => 'Motivos de Cancelación']))->name('cobranza.def.motivos-cancelacion');
    Route::get('/cobranza/def/estados-caso',     fn() => view('modules.credito.cobranza-def', ['tipo' => 'estado_caso',     'titulo' => 'Estados de Caso']))->name('cobranza.def.estados-caso');
    Route::get('/cobranza/def/estados-actividad',  fn() => view('modules.credito.cobranza-def', ['tipo' => 'estado_actividad',  'titulo' => 'Estados de Actividad']))->name('cobranza.def.estados-actividad');
    Route::get('/cobranza/def/tipos-cancelacion',  fn() => view('modules.credito.cobranza-def', ['tipo' => 'tipo_cancelacion',  'titulo' => 'Tipos de Cancelación']))->name('cobranza.def.tipos-cancelacion');
    Route::get('/reprogramacion/nueva',    fn() => view('modules.credito.reprogramacion-nueva'))->name('reprogramacion.nueva');
    Route::get('/reprogramacion/historial',fn() => view('modules.credito.reprogramacion-historial'))->name('reprogramacion.historial');
    Route::get('/pagos/pasarela',  fn() => view('modules.credito.pagos-pasarela'))->name('pagos-pasarela');
    Route::get('/pagos/manuales',  fn() => view('modules.credito.pagos-manuales'))->name('pagos-manuales');
    Route::get('/pagos/historial', fn() => view('modules.credito.pagos-historial'))->name('pagos-historial');

    // Indicadores
    Route::get('/indicadores/calificacion', fn() => view('modules.credito.indicadores.calificacion-vendedor'))->name('indicadores.calificacion');
    Route::get('/indicadores/calificacion-clientes', fn() => view('modules.credito.indicadores.calificacion-cliente'))->name('indicadores.calificacion-clientes');
});

// ─── Módulo Vendedor / EIE ────────────────────────────────────────────────────
Route::middleware(['auth', 'submodulo.permiso'])->prefix('vendedor')->name('vendedor.')->group(function () {
    Route::get('/dashboard',     fn() => view('modules.vendedor.dashboard'))->name('dashboard');
    Route::get('/clientes',      fn() => view('modules.vendedor.clientes'))->name('clientes');
    Route::get('/oferta',        fn() => view('modules.vendedor.oferta'))->name('oferta');
    Route::get('/pedidos',        fn() => view('modules.vendedor.pedidos'))->name('pedidos');
    Route::get('/pedidos/{id}',   fn($id) => view('modules.vendedor.pedido-detalle', compact('id')))->name('pedido.detalle');
    Route::get('/pagos-saldos',  fn() => view('modules.vendedor.pagos-saldos'))->name('pagos-saldos');
});

// ─── Módulo Cliente ───────────────────────────────────────────────────────────
Route::middleware(['auth', 'submodulo.permiso'])->prefix('cliente')->name('cliente.')->group(function () {
    Route::get('/dashboard',     fn() => view('modules.cliente.dashboard'))->name('dashboard');
    Route::get('/cuenta',        fn() => view('modules.cliente.cuenta'))->name('cuenta');
    Route::get('/pedidos',       fn() => view('modules.cliente.pedidos'))->name('pedidos');
    Route::get('/plan',          fn() => view('modules.cliente.plan'))->name('plan');
    Route::get('/cuotas',        fn() => view('modules.cliente.cuotas'))->name('cuotas');
    Route::get('/pagos',         fn() => view('modules.cliente.pagos'))->name('pagos');
    Route::get('/mi-calificacion', fn() => view('modules.cliente.mi-calificacion'))->name('mi-calificacion');
});

// ─── Perfil ───────────────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/perfil', fn() => view('perfil'))->name('perfil');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ─── Acceso desactivado ────────────────────────────────────────────────────────
Route::middleware('auth')->get('/access/desactivado', fn() => view('auth.access-desactivado'))->name('access.desactivado');

require __DIR__.'/auth.php';

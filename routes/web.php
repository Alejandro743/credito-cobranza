<?php

use App\Http\Controllers\ProfileController;
use App\Models\RolSubmoduloPermiso;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('welcome'));

// ─── Redirect dinámico según permisos ─────────────────────────────────────────
Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    }

    $roleId = $user->roles->first()?->id;
    if ($roleId) {
        $primer = RolSubmoduloPermiso::where('role_id', $roleId)
            ->where('puede_ver', true)
            ->with(['submodulo' => fn($q) => $q->where('active', true)->whereNotNull('route_name')->orderBy('sort_order')])
            ->get()
            ->filter(fn($p) => $p->submodulo?->route_name && Route::has($p->submodulo->route_name))
            ->sortBy('submodulo.sort_order')
            ->first();

        if ($primer) {
            return redirect()->route($primer->submodulo->route_name);
        }
    }

    abort(403, 'No tenés módulos asignados. Contactá al administrador.');

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
        Route::get('/listas',     fn() => view('admin.catalogo.listas.index'))->name('listas');
    });

    // Configuración del Ciclo
    Route::prefix('ciclo')->name('ciclo.')->group(function () {
        Route::get('/ciclos', fn() => view('admin.ciclo.ciclos.index'))->name('ciclos');
        Route::get('/puntos', fn() => view('admin.ciclo.puntos.index'))->name('puntos');
    });

    // Config. Financiera
    Route::prefix('finance')->name('finance.')->group(function () {
        Route::get('/', fn() => view('admin.finance.index'))->name('index');
    });

    // Definiciones
    Route::prefix('definiciones')->name('definiciones.')->group(function () {
        Route::get('/correlativo',        fn() => view('admin.definiciones.correlativo.index'))->name('correlativo');
        Route::get('/peso-indicadores',   fn() => view('admin.definiciones.peso-indicadores.index'))->name('peso-indicadores');
        Route::get('/rango-calificacion', fn() => view('admin.definiciones.rango-calificacion.index'))->name('rango-calificacion');
    });

    // Clientes (admin)
    Route::get('/clientes', fn() => view('admin.clientes.index'))->name('clientes.index');

    // Clientes (crédito/cobranza - ruta admin.credito.clientes)
    Route::get('/credito/clientes', fn() => view('modules.credito.clientes'))->name('credito.clientes');
});

// ─── Módulo Crédito / Cobranza ────────────────────────────────────────────────
Route::middleware(['auth', 'submodulo.permiso'])->prefix('credito')->name('credito.')->group(function () {
    Route::get('/espera',        fn() => view('modules.credito.espera'))->name('espera');
    Route::get('/revision',      fn() => view('modules.credito.revision'))->name('revision');
    Route::get('/aprobado',      fn() => view('modules.credito.aprobado'))->name('aprobado');
    Route::get('/cobranza',        fn() => view('modules.credito.cobranza'))->name('cobranza');
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
    Route::get('/clientes',      fn() => view('modules.vendedor.clientes'))->name('clientes');
    Route::get('/oferta',        fn() => view('modules.vendedor.oferta'))->name('oferta');
    Route::get('/pedidos',       fn() => view('modules.vendedor.pedidos'))->name('pedidos');
    Route::get('/pagos-saldos',  fn() => view('modules.vendedor.pagos-saldos'))->name('pagos-saldos');
});

// ─── Módulo Cliente ───────────────────────────────────────────────────────────
Route::middleware(['auth', 'submodulo.permiso'])->prefix('cliente')->name('cliente.')->group(function () {
    Route::get('/cuenta',  fn() => view('modules.cliente.cuenta'))->name('cuenta');
    Route::get('/pedidos', fn() => view('modules.cliente.pedidos'))->name('pedidos');
    Route::get('/plan',    fn() => view('modules.cliente.plan'))->name('plan');
    Route::get('/cuotas',  fn() => view('modules.cliente.cuotas'))->name('cuotas');
    Route::get('/pagos',   fn() => view('modules.cliente.pagos'))->name('pagos');
});

// ─── Perfil ───────────────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ─── Acceso desactivado ────────────────────────────────────────────────────────
Route::middleware('auth')->get('/access/desactivado', fn() => view('auth.access-desactivado'))->name('access.desactivado');

require __DIR__.'/auth.php';

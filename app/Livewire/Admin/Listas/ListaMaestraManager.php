<?php

namespace App\Livewire\Admin\Listas;

use App\Models\Categoria;
use App\Models\CommercialCycle;
use App\Models\ListaAcceso;
use App\Models\ListaMaestra;
use App\Models\ListaMaestraItem;
use App\Models\Product;
use App\Models\Unidad;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Livewire\Concerns\HasModuleColor;
use Livewire\Component;
use Livewire\WithPagination;

class ListaMaestraManager extends Component
{
    use WithPagination, HasModuleColor;

    public string $mode      = 'list';  // list | items | acceso
    public ?int   $viewingId = null;

    // ── Filtros list ──────────────────────────────────────────────────────────
    public string $search        = '';
    public string $filterCycleId = '';
    public string $filterStatus  = '';

    // ── Inline add ────────────────────────────────────────────────────────────
    public bool   $showAddForm          = false;
    public string $newCode              = '';
    public string $newName              = '';
    public ?int   $newCycleId           = null;
    public bool   $newActive            = true;
    public string $newTipoIncremento    = '';
    public string $newValorIncremento   = '0';
    public string $newCantidadCuotas    = '';
    public string $newDiasEntreCuotas   = '30';
    public string $newTipoCuotaInicial  = 'ninguna';
    public string $newValorCuotaInicial = '0';

    // ── Inline edit ───────────────────────────────────────────────────────────
    public ?int   $editingId             = null;
    public string $editName              = '';
    public ?int   $editCycleId           = null;
    public bool   $editActive            = true;
    public string $editTipoIncremento    = '';
    public string $editValorIncremento   = '0';
    public string $editCantidadCuotas    = '';
    public string $editDiasEntreCuotas   = '30';
    public string $editTipoCuotaInicial  = 'ninguna';
    public string $editValorCuotaInicial = '0';

    // ── Items mode ────────────────────────────────────────────────────────────
    public string $filterCodigo   = '';
    public string $filterProducto = '';
    public string $filterEnLista  = '';

    public bool   $showAddItemForm  = false;
    public string $newItemCode      = '';
    public string $newItemNombre    = '';
    public ?int   $newItemUnidadId  = null;
    public ?int   $newItemCatId     = null;
    public string $newItemPrecio    = '0';
    public string $newItemPuntos    = '0';
    public string $newItemStock     = '0';
    public bool   $newItemActive    = true;

    public ?int   $quickAddProductId = null;
    public string $quickAddPrecio    = '0';
    public string $quickAddPuntos    = '0';
    public string $quickAddStock     = '0';

    public ?int   $editItemId              = null;
    public string $editItemPrecio          = '0';
    public string $editItemPuntos          = '0';
    public string $editItemStock           = '0';
    public bool   $editItemActive          = true;
    public string $editItemTipoIncremento  = '';
    public string $editItemFactorIncremento = '0';

    // ── Acceso mode ───────────────────────────────────────────────────────────
    // Clientes
    public string  $sqlCliente        = '';
    public ?array  $sqlClienteResult  = null;
    public string  $sqlClienteError   = '';
    public string  $searchCliente     = '';
    public ?array  $manualClienteResult = null;

    // Vendedores
    public string  $sqlVendedor       = '';
    public ?array  $sqlVendedorResult = null;
    public string  $sqlVendedorError  = '';
    public string  $searchVendedor    = '';
    public ?array  $manualVendedorResult = null;

    public function mount(): void
    {
        $this->initModuleColor();
    }

    public function updatingSearch(): void { $this->resetPage(); }

    // ── List: inline add ─────────────────────────────────────────────────────

    public function showAdd(): void
    {
        $this->showAddForm          = true;
        $this->newCode              = '';
        $this->newName              = '';
        $this->newCycleId           = null;
        $this->newActive            = true;
        $this->newTipoIncremento    = '';
        $this->newValorIncremento   = '0';
        $this->newCantidadCuotas    = '';
        $this->newDiasEntreCuotas   = '30';
        $this->newTipoCuotaInicial  = 'ninguna';
        $this->newValorCuotaInicial = '0';
        $this->editingId            = null;
        $this->resetValidation();
    }

    public function cancelAdd(): void
    {
        $this->showAddForm = false;
        $this->resetValidation();
    }

    public function saveNew(): void
    {
        $this->validate([
            'newCode'    => ['required', 'string', 'max:30',
                             Rule::unique('lista_maestra', 'code')->whereNull('deleted_at')],
            'newName'    => 'required|string|min:2',
            'newCycleId' => ['required', 'integer', 'exists:commercial_cycles,id'],
        ], [], [
            'newCode'    => 'código',
            'newName'    => 'nombre',
            'newCycleId' => 'ciclo',
        ]);

        ListaMaestra::create([
            'code'                => strtoupper(trim($this->newCode)),
            'cycle_id'            => $this->newCycleId,
            'name'                => $this->newName,
            'active'              => $this->newActive,
            'estado'              => $this->newActive ? 'activa' : 'cerrada',
            'tipo_incremento'     => $this->newTipoIncremento ?: null,
            'valor_incremento'    => (float) $this->newValorIncremento,
            'cantidad_cuotas'     => $this->newCantidadCuotas !== '' ? (int) $this->newCantidadCuotas : null,
            'dias_entre_cuotas'   => $this->newDiasEntreCuotas !== '' ? (int) $this->newDiasEntreCuotas : null,
            'usa_cuota_inicial'   => $this->newTipoCuotaInicial !== 'ninguna',
            'tipo_cuota_inicial'  => $this->newTipoCuotaInicial !== 'ninguna' ? $this->newTipoCuotaInicial : null,
            'valor_cuota_inicial' => $this->newTipoCuotaInicial !== 'ninguna' ? (float) $this->newValorCuotaInicial : null,
        ]);

        $this->showAddForm = false;
        session()->flash('success', 'Lista de precios creada.');
    }

    // ── List: inline edit ────────────────────────────────────────────────────

    public function startEdit(int $id): void
    {
        $m = ListaMaestra::findOrFail($id);
        $this->editingId             = $id;
        $this->editName              = $m->name;
        $this->editCycleId           = $m->cycle_id;
        $this->editActive            = (bool) $m->active;
        $this->editTipoIncremento    = (string) ($m->tipo_incremento ?? '');
        $this->editValorIncremento   = (string) ($m->valor_incremento ?? '0');
        $this->editCantidadCuotas    = (string) ($m->cantidad_cuotas ?? '');
        $this->editDiasEntreCuotas   = (string) ($m->dias_entre_cuotas ?? '30');
        $this->editTipoCuotaInicial  = $m->tipo_cuota_inicial ?: 'ninguna';
        $this->editValorCuotaInicial = (string) ($m->valor_cuota_inicial ?? '0');
        $this->showAddForm           = false;
        $this->resetValidation();
    }

    public function cancelEdit(): void
    {
        $this->editingId = null;
        $this->resetValidation();
    }

    public function saveEdit(): void
    {
        $this->validate([
            'editName'    => 'required|string|min:2',
            'editCycleId' => ['required', 'integer', 'exists:commercial_cycles,id'],
        ], [], [
            'editName'    => 'nombre',
            'editCycleId' => 'ciclo',
        ]);

        $tipoInc   = $this->editTipoIncremento ?: null;
        $valorInc  = (float) $this->editValorIncremento;

        ListaMaestra::findOrFail($this->editingId)->update([
            'cycle_id'            => $this->editCycleId,
            'name'                => $this->editName,
            'active'              => $this->editActive,
            'estado'              => $this->editActive ? 'activa' : 'cerrada',
            'tipo_incremento'     => $tipoInc,
            'valor_incremento'    => $valorInc,
            'cantidad_cuotas'     => $this->editCantidadCuotas !== '' ? (int) $this->editCantidadCuotas : null,
            'dias_entre_cuotas'   => $this->editDiasEntreCuotas !== '' ? (int) $this->editDiasEntreCuotas : null,
            'usa_cuota_inicial'   => $this->editTipoCuotaInicial !== 'ninguna',
            'tipo_cuota_inicial'  => $this->editTipoCuotaInicial !== 'ninguna' ? $this->editTipoCuotaInicial : null,
            'valor_cuota_inicial' => $this->editTipoCuotaInicial !== 'ninguna' ? (float) $this->editValorCuotaInicial : null,
        ]);

        // Propagar incremento a todos los ítems de la lista
        if ($tipoInc && $valorInc > 0) {
            ListaMaestraItem::where('lista_maestra_id', $this->editingId)->get()
                ->each(function ($item) use ($tipoInc, $valorInc) {
                    $monto = $tipoInc === 'porcentaje'
                        ? round((float) $item->precio_base * $valorInc / 100, 2)
                        : $valorInc;
                    $item->update([
                        'tipo_incremento'   => $tipoInc,
                        'factor_incremento' => $valorInc,
                        'monto_incremento'  => $monto,
                    ]);
                });
        }

        $this->editingId = null;
        session()->flash('success', 'Lista actualizada.');
    }

    public function toggleActive(int $id): void
    {
        $m = ListaMaestra::findOrFail($id);
        $newActive = !$m->active;
        $m->update([
            'active' => $newActive,
            'estado' => $newActive ? 'activa' : 'cerrada',
        ]);
    }

    // ── Items mode ────────────────────────────────────────────────────────────

    public function viewItems(int $id): void
    {
        $this->viewingId       = $id;
        $this->mode            = 'items';
        $this->showAddItemForm = false;
        $this->editItemId      = null;
        $this->quickAddProductId = null;
        $this->filterCodigo    = '';
        $this->filterProducto  = '';
        $this->filterEnLista   = '';
        $this->resetValidation();
    }

    public function showAddItem(): void
    {
        $this->showAddItemForm = true;
        $this->newItemCode     = '';
        $this->newItemNombre   = '';
        $this->newItemUnidadId = null;
        $this->newItemCatId    = null;
        $this->newItemPrecio   = '0';
        $this->newItemPuntos   = '0';
        $this->newItemStock    = '0';
        $this->newItemActive   = true;
        $this->editItemId      = null;
        $this->quickAddProductId = null;
        $this->resetValidation();
    }

    public function cancelAddItem(): void
    {
        $this->showAddItemForm = false;
        $this->resetValidation();
    }

    public function saveNewItem(): void
    {
        $this->validate([
            'newItemCode'   => ['required', 'string', 'max:30',
                                Rule::unique('products', 'code')->whereNull('deleted_at')],
            'newItemNombre' => ['required', 'string', 'min:2',
                                Rule::unique('products', 'name')->whereNull('deleted_at')],
            'newItemUnidadId' => 'nullable|integer|exists:unidades,id',
            'newItemCatId'    => 'nullable|integer|exists:categorias,id',
            'newItemPrecio'   => 'required|numeric|min:0',
            'newItemPuntos'   => 'required|integer|min:0',
            'newItemStock'    => 'required|numeric|min:0',
        ], [], [
            'newItemCode'     => 'código',
            'newItemNombre'   => 'nombre',
            'newItemUnidadId' => 'unidad',
            'newItemCatId'    => 'categoría',
            'newItemPrecio'   => 'precio',
            'newItemPuntos'   => 'puntos',
            'newItemStock'    => 'stock inicial',
        ]);

        $product = Product::create([
            'code'         => strtoupper(trim($this->newItemCode)),
            'name'         => $this->newItemNombre,
            'unidad_id'    => $this->newItemUnidadId,
            'categoria_id' => $this->newItemCatId,
            'active'       => $this->newItemActive,
        ]);

        [$tipoInc, $factorInc, $montoInc] = $this->calcIncrementoFromLista((float) $this->newItemPrecio);

        ListaMaestraItem::create([
            'lista_maestra_id' => $this->viewingId,
            'product_id'       => $product->id,
            'precio_base'      => $this->newItemPrecio,
            'puntos'           => (int) $this->newItemPuntos,
            'stock_inicial'    => $this->newItemStock,
            'stock_consumido'  => 0,
            'stock_actual'     => $this->newItemStock,
            'descuento'        => 0,
            'active'           => $this->newItemActive,
            'tipo_incremento'  => $tipoInc,
            'factor_incremento'=> $factorInc,
            'monto_incremento' => $montoInc,
        ]);

        $this->showAddItemForm = false;
        session()->flash('success', "Producto \"{$product->name}\" creado y agregado.");
    }

    public function startQuickAdd(int $productId): void
    {
        $this->quickAddProductId = $productId;
        $this->quickAddPrecio    = '0';
        $this->quickAddPuntos    = '0';
        $this->quickAddStock     = '0';
        $this->showAddItemForm   = false;
        $this->editItemId        = null;
        $this->resetValidation();
    }

    public function cancelQuickAdd(): void
    {
        $this->quickAddProductId = null;
        $this->resetValidation();
    }

    public function saveQuickAdd(): void
    {
        $this->validate([
            'quickAddPrecio' => 'required|numeric|min:0',
            'quickAddPuntos' => 'required|integer|min:0',
            'quickAddStock'  => 'required|numeric|min:0',
        ], [], [
            'quickAddPrecio' => 'precio',
            'quickAddPuntos' => 'puntos',
            'quickAddStock'  => 'stock inicial',
        ]);

        [$tipoInc, $factorInc, $montoInc] = $this->calcIncrementoFromLista((float) $this->quickAddPrecio);

        ListaMaestraItem::create([
            'lista_maestra_id'  => $this->viewingId,
            'product_id'        => $this->quickAddProductId,
            'precio_base'       => $this->quickAddPrecio,
            'puntos'            => (int) $this->quickAddPuntos,
            'stock_inicial'     => $this->quickAddStock,
            'stock_consumido'   => 0,
            'stock_actual'      => $this->quickAddStock,
            'descuento'         => 0,
            'active'            => true,
            'tipo_incremento'   => $tipoInc,
            'factor_incremento' => $factorInc,
            'monto_incremento'  => $montoInc,
        ]);

        $this->quickAddProductId = null;
        session()->flash('success', 'Producto agregado a la lista.');
    }

    public function startEditItem(int $id): void
    {
        $item = ListaMaestraItem::findOrFail($id);
        $this->editItemId              = $id;
        $this->editItemPrecio          = (string) $item->precio_base;
        $this->editItemPuntos          = (string) $item->puntos;
        $this->editItemStock           = (string) $item->stock_inicial;
        $this->editItemActive          = (bool) $item->active;
        $this->editItemTipoIncremento   = (string) ($item->tipo_incremento ?? '');
        $this->editItemFactorIncremento = (string) ($item->factor_incremento ?? '0');

        $this->showAddItemForm   = false;
        $this->quickAddProductId = null;
        $this->resetValidation();
    }

    public function cancelEditItem(): void
    {
        $this->editItemId = null;
        $this->resetValidation();
    }

    public function saveEditItem(): void
    {
        $this->validate([
            'editItemPrecio'           => 'required|numeric|min:0',
            'editItemPuntos'           => 'required|integer|min:0',
            'editItemStock'            => 'required|numeric|min:0',
            'editItemTipoIncremento'   => 'nullable|in:porcentaje,monto_fijo',
            'editItemFactorIncremento' => 'required|numeric|min:0',
        ], [], [
            'editItemPrecio'           => 'precio',
            'editItemPuntos'           => 'puntos',
            'editItemStock'            => 'stock inicial',
            'editItemFactorIncremento' => 'factor incremento',
        ]);

        $item        = ListaMaestraItem::findOrFail($this->editItemId);
        $nuevoStock  = (float) $this->editItemStock;
        $nuevoActual = max(0, $nuevoStock - (float) $item->stock_consumido);
        $precioBase  = (float) $this->editItemPrecio;
        $tipo        = $this->editItemTipoIncremento ?: null;
        $factor      = (float) $this->editItemFactorIncremento;
        $monto       = 0.0;
        if ($tipo && $factor > 0) {
            $monto = $tipo === 'porcentaje'
                ? round($precioBase * $factor / 100, 2)
                : $factor;
        }

        $item->update([
            'precio_base'       => $precioBase,
            'puntos'            => (int) $this->editItemPuntos,
            'stock_inicial'     => $nuevoStock,
            'stock_actual'      => $nuevoActual,
            'active'            => $this->editItemActive,
            'tipo_incremento'   => $tipo,
            'factor_incremento' => $factor,
            'monto_incremento'  => $monto,
        ]);

        $this->editItemId = null;
        session()->flash('success', 'Ítem actualizado.');
    }

    public function toggleItemActive(int $itemId): void
    {
        $item = ListaMaestraItem::findOrFail($itemId);
        $item->update(['active' => !$item->active]);
    }

    public function removeItem(int $itemId): void
    {
        ListaMaestraItem::destroy($itemId);
    }

    public function refreshFromCatalog(): void
    {
        // Agrega al items map todos los productos activos que aún no estén en la lista
        $enLista = ListaMaestraItem::where('lista_maestra_id', $this->viewingId)->pluck('product_id');
        $nuevos  = Product::where('active', true)->whereNotIn('id', $enLista)->get();

        foreach ($nuevos as $p) {
            ListaMaestraItem::create([
                'lista_maestra_id' => $this->viewingId,
                'product_id'       => $p->id,
                'precio_base'      => 0,
                'puntos'           => 0,
                'stock_inicial'    => 0,
                'stock_consumido'  => 0,
                'stock_actual'     => 0,
                'descuento'        => 0,
                'active'           => false,
            ]);
        }

        session()->flash('success', $nuevos->count() > 0
            ? "{$nuevos->count()} producto(s) agregados desde el catálogo."
            : 'El catálogo ya está sincronizado.');
    }

    // ── Acceso mode ───────────────────────────────────────────────────────────

    public function viewAcceso(int $id): void
    {
        $this->viewingId             = $id;
        $this->mode                  = 'acceso';
        $this->sqlCliente            = '';
        $this->sqlClienteResult      = null;
        $this->sqlClienteError       = '';
        $this->searchCliente         = '';
        $this->manualClienteResult   = null;
        $this->sqlVendedor           = '';
        $this->sqlVendedorResult     = null;
        $this->sqlVendedorError      = '';
        $this->searchVendedor        = '';
        $this->manualVendedorResult  = null;
    }

    public function runSqlCliente(): void
    {
        $this->sqlClienteError  = '';
        $this->sqlClienteResult = null;
        try {
            $where   = trim($this->sqlCliente) ?: '1=1';
            $results = DB::select(
                "SELECT id, name, email FROM users WHERE tipo = 'cliente' AND ({$where}) LIMIT 100"
            );
            $this->sqlClienteResult = array_map(fn($r) => (array) $r, $results);
        } catch (\Throwable $e) {
            $this->sqlClienteError = 'Error en la consulta: ' . $e->getMessage();
        }
    }

    public function addAllFromSqlCliente(): void
    {
        if (empty($this->sqlClienteResult)) return;
        foreach ($this->sqlClienteResult as $row) {
            ListaAcceso::firstOrCreate(
                ['lista_maestra_id' => $this->viewingId, 'user_id' => $row['id'], 'tipo' => 'cliente'],
                ['origen' => 'sql']
            );
        }
        $this->sqlClienteResult = null;
        session()->flash('success', 'Clientes agregados al acceso.');
    }

    public function addClienteManual(int $userId): void
    {
        ListaAcceso::firstOrCreate(
            ['lista_maestra_id' => $this->viewingId, 'user_id' => $userId, 'tipo' => 'cliente'],
            ['origen' => 'manual']
        );
        $this->manualClienteResult = null;
        $this->searchCliente       = '';
        session()->flash('success', 'Cliente agregado.');
    }

    public function updatingSearchCliente(): void
    {
        $this->searchManualCliente();
    }

    public function searchManualCliente(): void
    {
        $q = trim($this->searchCliente);
        if (!$q) { $this->manualClienteResult = null; return; }

        $existingIds = ListaAcceso::where('lista_maestra_id', $this->viewingId)
            ->where('tipo', 'cliente')->pluck('user_id');

        $this->manualClienteResult = User::where('tipo', 'cliente')
            ->whereNotIn('id', $existingIds)
            ->where(fn($q2) => $q2->where('id', 'like', "%{$q}%")
                                  ->orWhere('name', 'like', "%{$q}%")
                                  )
            ->limit(10)
            ->get(['id', 'name'])
            ->toArray();
    }

    public function runSqlVendedor(): void
    {
        $this->sqlVendedorError  = '';
        $this->sqlVendedorResult = null;
        try {
            $where   = trim($this->sqlVendedor) ?: '1=1';
            $results = DB::select(
                "SELECT id, name, email FROM users WHERE tipo = 'vendedor' AND ({$where}) LIMIT 100"
            );
            $this->sqlVendedorResult = array_map(fn($r) => (array) $r, $results);
        } catch (\Throwable $e) {
            $this->sqlVendedorError = 'Error en la consulta: ' . $e->getMessage();
        }
    }

    public function addAllFromSqlVendedor(): void
    {
        if (empty($this->sqlVendedorResult)) return;
        foreach ($this->sqlVendedorResult as $row) {
            ListaAcceso::firstOrCreate(
                ['lista_maestra_id' => $this->viewingId, 'user_id' => $row['id'], 'tipo' => 'vendedor'],
                ['origen' => 'sql']
            );
        }
        $this->sqlVendedorResult = null;
        session()->flash('success', 'Vendedores agregados al acceso.');
    }

    public function addVendedorManual(int $userId): void
    {
        ListaAcceso::firstOrCreate(
            ['lista_maestra_id' => $this->viewingId, 'user_id' => $userId, 'tipo' => 'vendedor'],
            ['origen' => 'manual']
        );
        $this->manualVendedorResult = null;
        $this->searchVendedor       = '';
        session()->flash('success', 'Vendedor agregado.');
    }

    public function updatingSearchVendedor(): void
    {
        $this->searchManualVendedor();
    }

    public function searchManualVendedor(): void
    {
        $q = trim($this->searchVendedor);
        if (!$q) { $this->manualVendedorResult = null; return; }

        $existingIds = ListaAcceso::where('lista_maestra_id', $this->viewingId)
            ->where('tipo', 'vendedor')->pluck('user_id');

        $this->manualVendedorResult = User::where('tipo', 'vendedor')
            ->whereNotIn('id', $existingIds)
            ->where(fn($q2) => $q2->where('id', 'like', "%{$q}%")
                                  ->orWhere('name', 'like', "%{$q}%")
                                  )
            ->limit(10)
            ->get(['id', 'name'])
            ->toArray();
    }

    public function removeAcceso(int $accesoId): void
    {
        ListaAcceso::destroy($accesoId);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function calcIncrementoFromLista(float $precioBase): array
    {
        $lista    = ListaMaestra::find($this->viewingId);
        $tipoInc  = $lista?->tipo_incremento ?: null;
        $factorInc = (float) ($lista?->valor_incremento ?? 0);
        $montoInc = 0.0;
        if ($tipoInc && $factorInc > 0) {
            $montoInc = $tipoInc === 'porcentaje'
                ? round($precioBase * $factorInc / 100, 2)
                : $factorInc;
        }
        return [$tipoInc, $factorInc, $montoInc];
    }

    // ── Navigation ────────────────────────────────────────────────────────────

    public function backToList(): void
    {
        $this->mode      = 'list';
        $this->viewingId = null;
        $this->editingId = null;
        $this->showAddItemForm    = false;
        $this->quickAddProductId = null;
        $this->editItemId        = null;
        $this->sqlClienteResult  = null;
        $this->sqlVendedorResult = null;
        $this->manualClienteResult  = null;
        $this->manualVendedorResult = null;
        $this->resetValidation();
    }

    // ── Render ────────────────────────────────────────────────────────────────

    public function render()
    {
        $cycles = CommercialCycle::orderByDesc('start_date')->get();

        // List mode
        $maestras = ListaMaestra::with('cycle')
            ->when($this->search, fn($q) =>
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('code', 'like', "%{$this->search}%"))
            ->when($this->filterCycleId, fn($q) => $q->where('cycle_id', $this->filterCycleId))
            ->when($this->filterStatus !== '', fn($q) => $q->where('active', (bool) $this->filterStatus))
            ->orderByDesc('created_at')
            ->paginate(15);

        // Items mode
        $viewingMaestra = null;
        $products       = collect();
        $itemsMap       = collect();
        $categorias     = collect();
        $unidades       = collect();

        if ($this->viewingId && in_array($this->mode, ['items', 'acceso'])) {
            $viewingMaestra = ListaMaestra::with('cycle')->find($this->viewingId);
        }

        if ($this->viewingId && $this->mode === 'items') {
            $itemsMap = ListaMaestraItem::where('lista_maestra_id', $this->viewingId)
                ->get()->keyBy('product_id');

            $inListaIds = $itemsMap->keys();

            $products = Product::with(['categoria', 'unidad'])
                ->when($this->filterCodigo, fn($q) =>
                    $q->where('code', 'like', "%{$this->filterCodigo}%"))
                ->when($this->filterProducto, fn($q) =>
                    $q->where('name', 'like', "%{$this->filterProducto}%"))
                ->when($this->filterEnLista === '1', fn($q) => $q->whereIn('id', $inListaIds))
                ->when($this->filterEnLista === '0', fn($q) => $q->whereNotIn('id', $inListaIds))
                ->orderBy('name')
                ->get();

            $categorias = Categoria::where('active', true)->orderBy('name')->get();
            $unidades   = Unidad::where('active', true)->orderBy('name')->get();
        }

        // Acceso mode
        $accesosClientes  = collect();
        $accesosVendedores = collect();

        if ($this->viewingId && $this->mode === 'acceso') {
            $accesosClientes  = ListaAcceso::with('user')
                ->where('lista_maestra_id', $this->viewingId)
                ->where('tipo', 'cliente')
                ->orderBy('created_at')
                ->get();

            $accesosVendedores = ListaAcceso::with('user')
                ->where('lista_maestra_id', $this->viewingId)
                ->where('tipo', 'vendedor')
                ->orderBy('created_at')
                ->get();
        }

        return view('livewire.admin.listas.lista-maestra-manager', compact(
            'maestras', 'cycles', 'viewingMaestra', 'products', 'itemsMap',
            'categorias', 'unidades', 'accesosClientes', 'accesosVendedores'
        ));
    }
}

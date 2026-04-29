<?php

namespace App\Livewire\Admin\Listas;

use App\Models\Categoria;
use App\Models\CommercialCycle;
use App\Models\ListaMaestra;
use App\Models\ListaMaestraItem;
use App\Models\Product;
use App\Models\Unidad;
use Illuminate\Validation\Rule;
use App\Livewire\Concerns\HasModuleColor;
use Livewire\Component;
use Livewire\WithPagination;

class ListaPreciosManager extends Component
{
    use WithPagination, HasModuleColor;

    public string $mode   = 'list';
    public string $search = '';

    public bool  $editing   = false;
    public ?int  $editingId = null;
    public ?int  $viewingId = null;

    // Cabecera lista
    public ?int   $cycle_id = null;
    public string $name     = '';
    public string $estado   = 'activa';

    // Filtros del catálogo en modo items
    public string $filterCodigo   = '';
    public string $filterProducto = '';
    public string $filterEnLista  = '';   // '' = todos | '1' = en lista | '0' = disponibles

    // Formulario "+ Nuevo Producto": crea en catálogo + agrega a lista
    public bool   $showAddItemForm = false;
    public string $newCode         = '';
    public string $newNombre       = '';
    public ?int   $newUnidadId     = null;
    public ?int   $newCategoriaId  = null;
    public string $newPrecio       = '0';
    public string $newPuntos       = '0';
    public string $newStockInicial = '0';
    public bool   $newActive       = true;

    // Quick-add: agregar producto existente del catálogo a la lista
    public ?int   $quickAddProductId    = null;
    public string $quickAddPrecio       = '0';
    public string $quickAddPuntos       = '0';
    public string $quickAddStockInicial = '0';

    // Edición inline de ítem ya en la lista
    public ?int   $editItemId       = null;
    public string $editPrecio       = '0';
    public string $editPuntos       = '0';
    public string $editStockInicial = '0';

    public function mount(): void
    {
        $this->initModuleColor();
    }

    public function updatingSearch(): void { $this->resetPage(); }

    // ── CRUD cabecera ─────────────────────────────────────────────────────────

    public function create(): void { $this->resetForm(); $this->mode = 'form'; }

    public function edit(int $id): void
    {
        $m = ListaMaestra::findOrFail($id);
        $this->editingId = $id;
        $this->editing   = true;
        $this->cycle_id  = $m->cycle_id;
        $this->name      = $m->name;
        $this->estado    = $m->estado;
        $this->mode = 'form';
    }

    public function save(): void
    {
        $this->validate([
            'cycle_id' => 'required|integer|exists:commercial_cycles,id',
            'name'     => 'required|string|min:2',
            'estado'   => 'required|in:activa,cerrada',
        ]);

        if (!$this->editing) {
            if (ListaMaestra::where('cycle_id', $this->cycle_id)->exists()) {
                $this->addError('cycle_id', 'Ya existe una Lista de Precios para ese ciclo.');
                return;
            }
        }

        $data = ['cycle_id' => $this->cycle_id, 'name' => $this->name, 'estado' => $this->estado];

        $this->editing
            ? ListaMaestra::findOrFail($this->editingId)->update($data)
            : ListaMaestra::create($data);

        session()->flash('success', 'Lista de Precios guardada.');
        $this->backToList();
    }

    // ── Vista de ítems ────────────────────────────────────────────────────────

    public function viewItems(int $id): void
    {
        $this->viewingId         = $id;
        $this->showAddItemForm   = false;
        $this->editItemId        = null;
        $this->quickAddProductId = null;
        $this->resetItemFilters();
        $this->mode = 'items';
    }

    // ── Formulario "+ Nuevo Producto": crea en catálogo y agrega a lista ──────

    public function showAddItem(): void
    {
        $this->showAddItemForm = true;
        $this->newCode         = '';
        $this->newNombre       = '';
        $this->newUnidadId     = null;
        $this->newCategoriaId  = null;
        $this->newPrecio       = '0';
        $this->newPuntos       = '0';
        $this->newStockInicial = '0';
        $this->newActive       = true;
        $this->editItemId        = null;
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
            'newCode'   => ['required', 'string', 'max:30',
                            Rule::unique('products', 'code')->whereNull('deleted_at')],
            'newNombre' => ['required', 'string', 'min:2',
                            Rule::unique('products', 'name')->whereNull('deleted_at')],
            'newUnidadId'     => 'nullable|integer|exists:unidades,id',
            'newCategoriaId'  => 'nullable|integer|exists:categorias,id',
            'newPrecio'       => 'required|numeric|min:0',
            'newPuntos'       => 'required|integer|min:0',
            'newStockInicial' => 'required|numeric|min:0',
        ], [], [
            'newCode'         => 'código',
            'newNombre'       => 'nombre',
            'newUnidadId'     => 'unidad',
            'newCategoriaId'  => 'categoría',
            'newPrecio'       => 'precio',
            'newPuntos'       => 'puntos',
            'newStockInicial' => 'stock inicial',
        ]);

        $product = Product::create([
            'code'         => strtoupper(trim($this->newCode)),
            'name'         => $this->newNombre,
            'unidad_id'    => $this->newUnidadId,
            'categoria_id' => $this->newCategoriaId,
            'active'       => $this->newActive,
        ]);

        ListaMaestraItem::create([
            'lista_maestra_id' => $this->viewingId,
            'product_id'       => $product->id,
            'precio_base'      => $this->newPrecio,
            'puntos'           => (int) $this->newPuntos,
            'stock_inicial'    => $this->newStockInicial,
            'stock_consumido'  => 0,
            'stock_actual'     => $this->newStockInicial,
            'descuento'        => 0,
            'active'           => $this->newActive,
        ]);

        $this->showAddItemForm = false;
        session()->flash('success', "Producto \"{$product->name}\" creado y agregado.");
    }

    // ── Quick-add: agregar producto existente del catálogo a la lista ─────────

    public function startQuickAdd(int $productId): void
    {
        $this->quickAddProductId    = $productId;
        $this->quickAddPrecio       = '0';
        $this->quickAddPuntos       = '0';
        $this->quickAddStockInicial = '0';
        $this->showAddItemForm      = false;
        $this->editItemId           = null;
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
            'quickAddPrecio'       => 'required|numeric|min:0',
            'quickAddPuntos'       => 'required|integer|min:0',
            'quickAddStockInicial' => 'required|numeric|min:0',
        ], [], [
            'quickAddPrecio'       => 'precio',
            'quickAddPuntos'       => 'puntos',
            'quickAddStockInicial' => 'stock inicial',
        ]);

        ListaMaestraItem::create([
            'lista_maestra_id' => $this->viewingId,
            'product_id'       => $this->quickAddProductId,
            'precio_base'      => $this->quickAddPrecio,
            'puntos'           => (int) $this->quickAddPuntos,
            'stock_inicial'    => $this->quickAddStockInicial,
            'stock_consumido'  => 0,
            'stock_actual'     => $this->quickAddStockInicial,
            'descuento'        => 0,
            'active'           => true,
        ]);

        $this->quickAddProductId = null;
        session()->flash('success', 'Producto agregado a la lista.');
    }

    // ── Edición inline de ítem ya en la lista ─────────────────────────────────

    public function startEditItem(int $id): void
    {
        $item = ListaMaestraItem::findOrFail($id);
        $this->editItemId       = $id;
        $this->editPrecio       = (string) $item->precio_base;
        $this->editPuntos       = (string) $item->puntos;
        $this->editStockInicial = (string) $item->stock_inicial;
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
            'editPrecio'       => 'required|numeric|min:0',
            'editPuntos'       => 'required|integer|min:0',
            'editStockInicial' => 'required|numeric|min:0',
        ], [], [
            'editPrecio'       => 'precio',
            'editPuntos'       => 'puntos',
            'editStockInicial' => 'stock inicial',
        ]);

        $item         = ListaMaestraItem::findOrFail($this->editItemId);
        $nuevoInicial = (float) $this->editStockInicial;
        $nuevoActual  = $nuevoInicial - (float) $item->stock_consumido;

        $item->update([
            'precio_base'   => $this->editPrecio,
            'puntos'        => (int) $this->editPuntos,
            'stock_inicial' => $nuevoInicial,
            'stock_actual'  => max(0, $nuevoActual),
        ]);

        $this->editItemId = null;
        session()->flash('success', 'Ítem actualizado.');
    }

    public function removeItem(int $itemId): void
    {
        ListaMaestraItem::destroy($itemId);
    }

    public function toggleItemActive(int $itemId): void
    {
        $item = ListaMaestraItem::findOrFail($itemId);
        $item->update(['active' => !$item->active]);
    }

    // ─────────────────────────────────────────────────────────────────────────

    public function backToList(): void { $this->resetForm(); $this->mode = 'list'; }

    private function resetForm(): void
    {
        $this->reset(['cycle_id', 'name', 'editingId', 'editing', 'viewingId']);
        $this->estado            = 'activa';
        $this->showAddItemForm   = false;
        $this->editItemId        = null;
        $this->quickAddProductId = null;
        $this->resetItemFilters();
    }

    private function resetItemFilters(): void
    {
        $this->filterCodigo   = '';
        $this->filterProducto = '';
        $this->filterEnLista  = '';
    }

    public function render()
    {
        $listas = ListaMaestra::with('cycle')
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->orderByDesc('created_at')
            ->paginate(15);

        $cycles = CommercialCycle::where('status', '!=', 'cerrado')
            ->orderByDesc('start_date')->get();

        $viewingLista = null;
        $products     = collect();
        $itemsMap     = collect();
        $categorias   = collect();
        $unidades     = collect();

        if ($this->viewingId && $this->mode === 'items') {
            $viewingLista = ListaMaestra::with('cycle')->find($this->viewingId);

            $itemsMap = ListaMaestraItem::where('lista_maestra_id', $this->viewingId)
                ->get()
                ->keyBy('product_id');

            $inListaIds = $itemsMap->keys();

            $products = Product::with(['categoria', 'unidad'])
                ->when($this->filterCodigo, fn($q) =>
                    $q->where('code', 'like', "%{$this->filterCodigo}%"))
                ->when($this->filterProducto, fn($q) =>
                    $q->where('name', 'like', "%{$this->filterProducto}%"))
                ->when($this->filterEnLista === '1', fn($q) =>
                    $q->whereIn('id', $inListaIds))
                ->when($this->filterEnLista === '0', fn($q) =>
                    $q->whereNotIn('id', $inListaIds))
                ->orderBy('name')
                ->get();

            $categorias = Categoria::where('active', true)->orderBy('name')->get();
            $unidades   = Unidad::where('active', true)->orderBy('name')->get();
        }

        return view('livewire.admin.listas.lista-precios-manager',
            compact('listas', 'cycles', 'viewingLista', 'products', 'itemsMap', 'categorias', 'unidades'));
    }
}

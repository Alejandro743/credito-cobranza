<?php

namespace App\Livewire\Admin\Products;

use App\Models\Categoria;
use App\Models\ListaMaestra;
use App\Models\ListaMaestraItem;
use App\Models\Product;
use App\Models\Unidad;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Livewire\Concerns\HasModuleColor;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ProductManager extends Component
{
    use WithPagination, HasModuleColor, WithFileUploads;

    public string $search            = '';
    public string $filterStatus      = '';
    public string $filterCategoriaId = '';
    public string $sortBy            = 'code';
    public string $sortDir           = 'asc';

    // Formulario de agregar (inline arriba de tabla)
    public bool   $showAddForm    = false;
    public string $newCode        = '';
    public string $newName        = '';
    public ?int   $newUnidadId    = null;
    public ?int   $newCategoriaId = null;
    public bool   $newActive      = true;
    public string $newCicloId      = '';
    public string $newStockInicial = '';
    public        $newImage        = null;

    // Edición inline en fila
    public ?int   $editingId       = null;
    public string $editCode        = '';
    public string $editName        = '';
    public ?int   $editUnidadId    = null;
    public ?int   $editCategoriaId = null;
    public bool   $editActive      = true;
    public string $editCicloId     = '';
    public string $editStockInicial = '';
    public        $editImage       = null;
    public string $editCurrentImage = '';

    // Modal stock en listas
    public ?int   $stockModalProductId = null;
    public array  $stockEdits          = [];

    public function mount(): void
    {
        $this->initModuleColor();
    }

    public function updatingSearch(): void { $this->resetPage(); }

    public function toggleSort(string $col): void
    {
        if ($this->sortBy === $col) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy  = $col;
            $this->sortDir = 'asc';
        }
        $this->resetPage();
    }

    // ── Agregar ───────────────────────────────────────────────────────────────

    public function showAdd(): void
    {
        $this->showAddForm    = true;
        $this->newCode        = '';
        $this->newName        = '';
        $this->newUnidadId    = null;
        $this->newCategoriaId = null;
        $this->newActive      = true;
        $this->newCicloId      = (string)(\App\Models\CommercialCycle::vigente()?->id ?? '');
        $this->newStockInicial = '';
        $this->newImage        = null;
        $this->cancelEdit();
    }

    public function cancelAdd(): void
    {
        $this->showAddForm = false;
        $this->newImage    = null;
        $this->resetValidation();
    }

    public function saveNew(): void
    {
        $this->validate([
            'newCode' => ['required', 'string', 'max:30',
                          Rule::unique('products', 'code')->whereNull('deleted_at')],
            'newName' => ['required', 'string', 'min:2',
                          Rule::unique('products', 'name')->whereNull('deleted_at')],
            'newUnidadId'    => 'nullable|integer|exists:unidades,id',
            'newCategoriaId' => 'nullable|integer|exists:categorias,id',
            'newImage'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [], [
            'newCode'        => 'código',
            'newName'        => 'nombre',
            'newUnidadId'    => 'unidad',
            'newCategoriaId' => 'categoría',
            'newImage'       => 'imagen',
        ]);

        $imagePath = $this->newImage
            ? $this->newImage->store('productos', 'public')
            : null;

        $product = Product::create([
            'code'         => strtoupper(trim($this->newCode)),
            'name'         => $this->newName,
            'unidad_id'    => $this->newUnidadId,
            'categoria_id' => $this->newCategoriaId,
            'active'       => $this->newActive,
            'image'        => $imagePath,
        ]);

        if ($this->newCicloId) {
            $stock = is_numeric($this->newStockInicial) ? (float)$this->newStockInicial : 0;
            $product->ciclos()->syncWithoutDetaching([(int)$this->newCicloId => ['stock_total' => $stock]]);
        }

        $this->showAddForm     = false;
        $this->newImage        = null;
        $this->newCicloId      = '';
        $this->newStockInicial = '';
        session()->flash('success', 'Producto agregado.');
    }

    // ── Edición inline ────────────────────────────────────────────────────────

    public function startEdit(int $id): void
    {
        $p = Product::with('ciclos')->findOrFail($id);
        $this->editingId        = $id;
        $this->editCode         = $p->code;
        $this->editName         = $p->name;
        $this->editUnidadId     = $p->unidad_id;
        $this->editCategoriaId  = $p->categoria_id;
        $this->editActive       = $p->active;
        $this->editCurrentImage = $p->image ?? '';
        $this->editImage        = null;
        $this->editCicloId      = (string)($p->ciclos->first()?->id ?? '');
        $this->editStockInicial = (string)($p->ciclos->first()?->pivot->stock_total ?? '');
        $this->showAddForm      = false;
        $this->resetValidation();
    }

    public function cancelEdit(): void
    {
        $this->editingId        = null;
        $this->editImage        = null;
        $this->editCurrentImage = '';
        $this->resetValidation();
    }

    public function saveEdit(): void
    {
        $this->validate([
            'editCode' => ['required', 'string', 'max:30',
                           Rule::unique('products', 'code')->ignore($this->editingId)->whereNull('deleted_at')],
            'editName' => ['required', 'string', 'min:2',
                           Rule::unique('products', 'name')->ignore($this->editingId)->whereNull('deleted_at')],
            'editUnidadId'    => 'nullable|integer|exists:unidades,id',
            'editCategoriaId' => 'nullable|integer|exists:categorias,id',
            'editImage'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [], [
            'editCode'        => 'código',
            'editName'        => 'nombre',
            'editUnidadId'    => 'unidad',
            'editCategoriaId' => 'categoría',
            'editImage'       => 'imagen',
        ]);

        $p = Product::findOrFail($this->editingId);

        $imagePath = $p->image;
        if ($this->editImage) {
            if ($p->image) {
                Storage::disk('public')->delete($p->image);
            }
            $imagePath = $this->editImage->store('productos', 'public');
        }

        $p->update([
            'code'         => strtoupper(trim($this->editCode)),
            'name'         => $this->editName,
            'unidad_id'    => $this->editUnidadId,
            'categoria_id' => $this->editCategoriaId,
            'active'       => $this->editActive,
            'image'        => $imagePath,
        ]);

        if ($this->editCicloId) {
            $stock = is_numeric($this->editStockInicial) ? (float)$this->editStockInicial : 0;
            $p->ciclos()->sync([(int)$this->editCicloId => ['stock_total' => $stock]]);
        } else {
            $p->ciclos()->detach();
        }

        $this->editingId        = null;
        $this->editImage        = null;
        $this->editCurrentImage = '';
        $this->editCicloId      = '';
        $this->editStockInicial = '';
        session()->flash('success', 'Producto actualizado.');
    }

    // ── Toggle activo ─────────────────────────────────────────────────────────

    public function toggleActive(int $id): void
    {
        $p = Product::findOrFail($id);
        $p->update(['active' => !$p->active]);
    }

    // ── Modal: stock en listas ────────────────────────────────────────────────

    public function openStockModal(int $productId): void
    {
        $this->stockModalProductId = $productId;
        $this->stockEdits          = [];
        $this->resetValidation();

        ListaMaestraItem::where('product_id', $productId)
            ->get()
            ->each(fn($item) => $this->stockEdits[$item->id] = (string) $item->stock_inicial);
    }

    public function closeStockModal(): void
    {
        $this->stockModalProductId = null;
        $this->stockEdits          = [];
        $this->resetValidation();
    }

    public function saveStockModal(): void
    {
        $productId = $this->stockModalProductId;
        if (!$productId) return;

        try {
            DB::transaction(function () use ($productId) {
                foreach ($this->stockEdits as $rawId => $rawStock) {
                    $itemId   = (int) $rawId;
                    $newStock = max(0, (float) $rawStock);

                    $item = ListaMaestraItem::with('listaMaestra')
                        ->lockForUpdate()
                        ->findOrFail($itemId);

                    if ($item->product_id !== $productId) continue;

                    $cicloId = $item->listaMaestra->cycle_id;

                    $cicloRow = DB::table('ciclo_productos')
                        ->where('commercial_cycle_id', $cicloId)
                        ->where('product_id', $productId)
                        ->lockForUpdate()
                        ->first();

                    if (!$cicloRow) continue;

                    $listaIds     = ListaMaestra::where('cycle_id', $cicloId)->pluck('id');
                    $otroAsignado = (float) ListaMaestraItem::whereIn('lista_maestra_id', $listaIds)
                        ->where('product_id', $productId)
                        ->where('id', '!=', $itemId)
                        ->lockForUpdate()
                        ->sum('stock_inicial');

                    $maxInput = max(0, (float) $cicloRow->stock_total - $otroAsignado);

                    if ($newStock > $maxInput) {
                        $listaNombre = $item->listaMaestra->name;
                        throw new \RuntimeException(
                            "Stock insuficiente en \"{$listaNombre}\": máximo disponible " . number_format($maxInput, 0) . "."
                        );
                    }

                    $item->update(['stock_inicial' => $newStock]);
                }
            });
        } catch (\RuntimeException $e) {
            $this->addError('stockModal', $e->getMessage());
            return;
        }

        $this->closeStockModal();
        session()->flash('success', 'Stock actualizado correctamente.');
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function generarCode(string $name, ?int $ignoreId = null): string
    {
        $base = strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $name), 0, 8));
        $code = $base;
        $i    = 1;
        while (
            Product::where('code', $code)
                ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
                ->whereNull('deleted_at')
                ->exists()
        ) {
            $code = $base . $i++;
        }
        return $code;
    }

    public function render()
    {
        $cicloVigente = \App\Models\CommercialCycle::vigente();
        $ciclos       = \App\Models\CommercialCycle::where('status', 'abierto')->orderByDesc('start_date')->get();

        $products = Product::with(['categoria', 'unidad', 'ciclos', 'listaMaestraItems'])
            ->when($this->search, fn($q) =>
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('code', 'like', "%{$this->search}%"))
            ->when($this->filterStatus !== '', fn($q) => $q->where('active', (bool) $this->filterStatus))
            ->when($this->filterCategoriaId, fn($q) => $q->where('categoria_id', $this->filterCategoriaId))
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate(20);

        $categorias = Categoria::where('active', true)->orderBy('name')->get();
        $unidades   = Unidad::where('active', true)->orderBy('name')->get();

        // Modal stock en listas
        $stockModalProduct = null;
        $stockModalRows    = collect();
        if ($this->stockModalProductId) {
            $stockModalProduct = Product::find($this->stockModalProductId);
            $pid   = $this->stockModalProductId;
            $items = ListaMaestraItem::with(['listaMaestra.cycle'])
                ->where('product_id', $pid)
                ->get();

            if ($items->isNotEmpty()) {
                $cicloIds = $items->pluck('listaMaestra.cycle_id')->unique()->filter();

                $stkTotalMap = DB::table('ciclo_productos')
                    ->where('product_id', $pid)
                    ->whereIn('commercial_cycle_id', $cicloIds)
                    ->pluck('stock_total', 'commercial_cycle_id')
                    ->map(fn($v) => (float)$v);

                $asignadoPerCiclo = [];
                foreach ($cicloIds as $cid) {
                    $lIds = ListaMaestra::where('cycle_id', $cid)->pluck('id');
                    $asignadoPerCiclo[$cid] = (float) ListaMaestraItem::whereIn('lista_maestra_id', $lIds)
                        ->where('product_id', $pid)
                        ->sum('stock_inicial');
                }

                foreach ($items as $item) {
                    $cid      = $item->listaMaestra->cycle_id;
                    $total    = $stkTotalMap->get($cid, 0);
                    $asig     = $asignadoPerCiclo[$cid] ?? 0;
                    $disp     = max(0, $total - $asig);
                    $maxInput = max(0, $total - $asig + (float)$item->stock_inicial);

                    $stockModalRows->push([
                        'item_id'    => $item->id,
                        'ciclo_code' => $item->listaMaestra->cycle?->code ?? '—',
                        'lista_name' => $item->listaMaestra->name,
                        'stock_max'  => $total,
                        'stock_disp' => $disp,
                        'max_input'  => $maxInput,
                        'stock_ini'  => $item->stock_inicial,
                    ]);
                }
            }
        }

        return view('livewire.admin.products.product-manager', [
            'products'          => $products,
            'categorias'        => $categorias,
            'unidades'          => $unidades,
            'sortBy'            => $this->sortBy,
            'sortDir'           => $this->sortDir,
            'cicloVigente'      => $cicloVigente,
            'ciclos'            => $ciclos,
            'stockModalProduct' => $stockModalProduct,
            'stockModalRows'    => $stockModalRows,
        ]);
    }
}

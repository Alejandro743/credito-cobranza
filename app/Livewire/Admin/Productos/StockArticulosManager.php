<?php

namespace App\Livewire\Admin\Productos;

use App\Models\ListaDerivadaItem;
use App\Models\ListaMaestra;
use App\Models\ListaMaestraItem;
use App\Models\MaestroArticulo;
use App\Livewire\Concerns\HasModuleColor;
use Livewire\Component;
use Livewire\WithPagination;

class StockArticulosManager extends Component
{
    use WithPagination, HasModuleColor;

    public string $search        = '';
    public string $filterListaId = '';
    public string $sortBy        = 'lista_maestra_id';
    public string $sortDir       = 'asc';

    // Selección en grilla
    public ?int $selectedItemId = null;

    // Edición inline
    public ?int   $editingItemId    = null;
    public string $editStockInicial = '';
    public bool   $editActive       = true;

    // Modal listas de precios
    public ?int $stockModalItemId = null;

    // Formulario agregar
    public bool   $showAddForm        = false;
    public ?int   $formListaMaestraId = null;
    public ?int   $selectedMaestroId  = null;
    public string $stockInicial       = '0';

    // Datos del maestro seleccionado (solo lectura)
    public string $maestroCategoria = '';
    public string $maestroUnidad    = '';

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

    public function selectItem(int $id): void
    {
        if ($this->editingItemId && $this->editingItemId !== $id) {
            $this->editingItemId = null;
            $this->resetValidation();
        }
        $this->selectedItemId   = $this->selectedItemId === $id ? null : $id;
        $this->stockModalItemId = null;
    }

    // ── Edición inline ────────────────────────────────────────────────────────

    public function startEdit(int $id): void
    {
        $item = ListaMaestraItem::findOrFail($id);
        $this->editingItemId    = $id;
        $this->editStockInicial = (string) $item->stock_inicial;
        $this->editActive       = (bool)   $item->active;
        $this->showAddForm      = false;
        $this->stockModalItemId = null;
        $this->resetValidation();
    }

    public function cancelEdit(): void
    {
        $this->editingItemId = null;
        $this->resetValidation();
    }

    public function saveEdit(): void
    {
        $this->validate([
            'editStockInicial' => 'required|numeric|min:0',
        ], [
            'editStockInicial.required' => 'El stock inicial es obligatorio.',
            'editStockInicial.numeric'  => 'El stock inicial debe ser un número.',
            'editStockInicial.min'      => 'El stock inicial no puede ser negativo.',
        ]);

        $item = ListaMaestraItem::findOrFail($this->editingItemId);
        $item->update([
            'stock_inicial' => (float) $this->editStockInicial,
            'active'        => $this->editActive,
        ]);

        $this->editingItemId = null;
        session()->flash('success', 'Stock actualizado.');
    }

    // ── Modal listas de precios ───────────────────────────────────────────────

    public function openStockModal(int $id): void
    {
        $this->stockModalItemId = $id;
        $this->selectedItemId   = $id;
        $this->editingItemId    = null;
        $this->resetValidation();
    }

    public function closeStockModal(): void
    {
        $this->stockModalItemId = null;
        $this->resetValidation();
    }

    // ── Al cambiar la lista en el form ────────────────────────────────────────

    public function updatedFormListaMaestraId(): void
    {
        $this->selectedMaestroId = null;
        $this->maestroCategoria  = '';
        $this->maestroUnidad     = '';
    }

    // ── Al seleccionar maestro articulo ───────────────────────────────────────

    public function selectMaestro(int|string $id): void
    {
        $this->selectedMaestroId = (int) $id;
        $m = MaestroArticulo::with(['categoria', 'unidad'])->find($id);
        $this->maestroCategoria = $m?->categoria?->descripcion ?? '—';
        $this->maestroUnidad    = $m?->unidad?->name ?? '—';
    }

    // ── Form ──────────────────────────────────────────────────────────────────

    public function showAdd(): void
    {
        $this->showAddForm        = true;
        $this->formListaMaestraId = null;
        $this->selectedMaestroId  = null;
        $this->stockInicial       = '0';
        $this->maestroCategoria   = '';
        $this->maestroUnidad      = '';
        $this->selectedItemId     = null;
        $this->resetValidation();
    }

    public function cancelAdd(): void
    {
        $this->showAddForm = false;
        $this->resetValidation();
    }

    // ── Guardar ───────────────────────────────────────────────────────────────

    public function saveNew(): void
    {
        $this->validate([
            'formListaMaestraId' => 'required|exists:lista_maestra,id',
            'selectedMaestroId'  => 'required|exists:maestro_articulos,id',
            'stockInicial'       => 'required|numeric|min:0',
        ], [
            'formListaMaestraId.required' => 'Debe seleccionar un ciclo.',
            'formListaMaestraId.exists'   => 'El ciclo seleccionado no es válido.',
            'selectedMaestroId.required'  => 'Debe seleccionar un artículo.',
            'selectedMaestroId.exists'    => 'El artículo seleccionado no es válido.',
            'stockInicial.required'       => 'El stock inicial es obligatorio.',
            'stockInicial.numeric'        => 'El stock inicial debe ser un número.',
            'stockInicial.min'            => 'El stock inicial no puede ser negativo.',
        ]);

        $existe = ListaMaestraItem::where('lista_maestra_id', $this->formListaMaestraId)
            ->where('maestro_articulo_id', $this->selectedMaestroId)
            ->exists();

        if ($existe) {
            $this->addError('selectedMaestroId', 'Este artículo ya existe en la lista seleccionada.');
            return;
        }

        ListaMaestraItem::create([
            'lista_maestra_id'    => $this->formListaMaestraId,
            'maestro_articulo_id' => $this->selectedMaestroId,
            'stock_inicial'       => $this->stockInicial,
            'stock_actual'        => $this->stockInicial,
            'stock_consumido'     => 0,
            'stock_comprometido'  => 0,
            'precio_base'         => 0,
            'puntos'              => 0,
            'descuento'           => 0,
            'active'              => true,
        ]);

        $this->showAddForm = false;
        session()->flash('success', 'Artículo agregado al stock.');
    }

    // ── Render ────────────────────────────────────────────────────────────────

    public function render()
    {
        $items = ListaMaestraItem::with(['maestroArticulo.categoria', 'maestroArticulo.unidad', 'listaMaestra.cycle'])
            ->whereNotNull('maestro_articulo_id')
            ->when($this->filterListaId !== '', fn($q) => $q->where('lista_maestra_id', $this->filterListaId))
            ->when($this->search, fn($q) =>
                $q->whereHas('maestroArticulo', fn($sq) =>
                    $sq->where('codigo', 'like', "%{$this->search}%")
                       ->orWhere('nombre', 'like', "%{$this->search}%")
                ))
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate(20);

        $listas = ListaMaestra::with('cycle')->orderBy('id')->get();

        $maestrosDisponibles = collect();
        if ($this->formListaMaestraId) {
            $usados = ListaMaestraItem::where('lista_maestra_id', $this->formListaMaestraId)
                ->whereNotNull('maestro_articulo_id')
                ->pluck('maestro_articulo_id');

            $maestrosDisponibles = MaestroArticulo::whereNotIn('id', $usados)
                ->where('active', true)
                ->orderBy('codigo')
                ->get();
        }

        $stockModalItem = null;
        $stockModalRows = collect();
        if ($this->stockModalItemId) {
            $stockModalItem = ListaMaestraItem::with(['maestroArticulo', 'listaMaestra.cycle'])
                ->find($this->stockModalItemId);
            if ($stockModalItem) {
                $stockModalRows = ListaDerivadaItem::with('listaDerivada')
                    ->where('lista_maestra_item_id', $this->stockModalItemId)
                    ->get();
            }
        }

        return view('livewire.admin.productos.stock-articulos-manager', compact(
            'items', 'listas', 'maestrosDisponibles', 'stockModalItem', 'stockModalRows'
        ));
    }
}

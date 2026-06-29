<?php

namespace App\Livewire\Admin\Productos;

use App\Models\CommercialCycle;
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
    public string $filterCicloId = '';
    public string $sortBy        = 'lista_maestra_id';
    public string $sortDir       = 'asc';

    // Selección en grilla
    public ?int $selectedItemId = null;

    // Edición inline
    public ?int   $editingItemId    = null;
    public string $editStockInicial = '';
    public bool   $editActive       = true;

    // Modal stock por ciclo
    public ?int $stockModalItemId = null;

    // Formulario agregar
    public bool   $showAddForm       = false;
    public string $formCicloId       = '';
    public ?int   $selectedMaestroId = null;
    public string $stockInicial      = '0';

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

    // ── Modal: stock del artículo por ciclo ──────────────────────────────────

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

    // ── Al cambiar el ciclo en el form ────────────────────────────────────────

    public function updatedFormCicloId(): void
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
        $this->showAddForm       = true;
        $this->formCicloId       = '';
        $this->selectedMaestroId = null;
        $this->stockInicial      = '0';
        $this->maestroCategoria  = '';
        $this->maestroUnidad     = '';
        $this->selectedItemId    = null;
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
            'formCicloId'       => 'required|exists:commercial_cycles,id',
            'selectedMaestroId' => 'required|exists:maestro_articulos,id',
            'stockInicial'      => 'required|numeric|min:0',
        ], [
            'formCicloId.required'       => 'Debe seleccionar un ciclo.',
            'formCicloId.exists'         => 'El ciclo seleccionado no es válido.',
            'selectedMaestroId.required' => 'Debe seleccionar un artículo.',
            'selectedMaestroId.exists'   => 'El artículo seleccionado no es válido.',
            'stockInicial.required'      => 'El stock inicial es obligatorio.',
            'stockInicial.numeric'       => 'El stock inicial debe ser un número.',
            'stockInicial.min'           => 'El stock inicial no puede ser negativo.',
        ]);

        $listaMaestra = ListaMaestra::where('cycle_id', $this->formCicloId)->first();
        if (!$listaMaestra) {
            $this->addError('formCicloId', 'Este ciclo no tiene una lista maestra configurada.');
            return;
        }

        $cicloListaIds = ListaMaestra::where('cycle_id', $this->formCicloId)->pluck('id');
        $existe = ListaMaestraItem::whereIn('lista_maestra_id', $cicloListaIds)
            ->where('maestro_articulo_id', $this->selectedMaestroId)
            ->exists();

        if ($existe) {
            $this->addError('selectedMaestroId', 'Este artículo ya tiene stock en el ciclo seleccionado.');
            return;
        }

        ListaMaestraItem::create([
            'lista_maestra_id'    => $listaMaestra->id,
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
            ->when($this->filterCicloId !== '', fn($q) =>
                $q->whereHas('listaMaestra', fn($sq) => $sq->where('cycle_id', $this->filterCicloId))
            )
            ->when($this->search, fn($q) =>
                $q->whereHas('maestroArticulo', fn($sq) =>
                    $sq->where('codigo', 'like', "%{$this->search}%")
                       ->orWhere('nombre', 'like', "%{$this->search}%")
                ))
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate(20);

        $ciclos = CommercialCycle::orderByDesc('start_date')->get();

        $maestrosDisponibles = collect();
        if ($this->formCicloId) {
            $cicloListaIds = ListaMaestra::where('cycle_id', $this->formCicloId)->pluck('id');
            $usados = ListaMaestraItem::whereIn('lista_maestra_id', $cicloListaIds)
                ->whereNotNull('maestro_articulo_id')
                ->pluck('maestro_articulo_id');

            $maestrosDisponibles = MaestroArticulo::whereNotIn('id', $usados)
                ->where('active', true)
                ->orderBy('codigo')
                ->get();
        }

        // Modal: listas de precios (lista_derivada) que referencian este item de stock
        $stockModalItem = null;
        $stockModalRows = collect();
        if ($this->stockModalItemId) {
            $stockModalItem = ListaMaestraItem::with(['maestroArticulo', 'listaMaestra.cycle'])
                ->find($this->stockModalItemId);
            if ($stockModalItem) {
                $stockModalRows = ListaDerivadaItem::with(['listaDerivada'])
                    ->where('lista_maestra_item_id', $this->stockModalItemId)
                    ->get();
            }
        }

        return view('livewire.admin.productos.stock-articulos-manager', compact(
            'items', 'ciclos', 'maestrosDisponibles', 'stockModalItem', 'stockModalRows'
        ));
    }
}

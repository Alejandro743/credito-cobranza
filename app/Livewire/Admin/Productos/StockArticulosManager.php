<?php

namespace App\Livewire\Admin\Productos;

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

    // ── Al cambiar la lista seleccionada en el form ───────────────────────────

    public function updatedFormListaMaestraId(): void
    {
        $this->selectedMaestroId = null;
        $this->maestroCategoria  = '';
        $this->maestroUnidad     = '';
    }

    // ── Al seleccionar un maestro articulo ────────────────────────────────────

    public function selectMaestro(int|string $id): void
    {
        $this->selectedMaestroId = (int) $id;
        $m = MaestroArticulo::with(['categoria', 'unidad'])->find($id);
        $this->maestroCategoria = $m?->categoria?->descripcion ?? '—';
        $this->maestroUnidad    = $m?->unidad?->name ?? '—';
    }

    // ── Mostrar / ocultar form ────────────────────────────────────────────────

    public function showAdd(): void
    {
        $this->showAddForm        = true;
        $this->formListaMaestraId = null;
        $this->selectedMaestroId  = null;
        $this->stockInicial       = '0';
        $this->maestroCategoria   = '';
        $this->maestroUnidad      = '';
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
        ], [], [
            'formListaMaestraId' => 'lista / ciclo',
            'selectedMaestroId'  => 'artículo',
            'stockInicial'       => 'stock inicial',
        ]);

        // Verificar que no exista ya en esa lista
        $existe = ListaMaestraItem::where('lista_maestra_id', $this->formListaMaestraId)
            ->where('maestro_articulo_id', $this->selectedMaestroId)
            ->exists();

        if ($existe) {
            $this->addError('selectedMaestroId', 'Este artículo ya existe en la lista seleccionada.');
            return;
        }

        $maestro = MaestroArticulo::findOrFail($this->selectedMaestroId);

        ListaMaestraItem::create([
            'lista_maestra_id'   => $this->formListaMaestraId,
            'maestro_articulo_id'=> $this->selectedMaestroId,
            'stock_inicial'      => $this->stockInicial,
            'stock_actual'       => $this->stockInicial,
            'stock_consumido'    => 0,
            'stock_comprometido' => 0,
            'precio_base'        => 0,
            'puntos'             => 0,
            'descuento'          => 0,
            'active'             => true,
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
            ->orderBy('lista_maestra_id')
            ->paginate(20);

        $listas = ListaMaestra::with('cycle')->orderBy('id')->get();

        // Maestros disponibles para el form (no están ya en la lista seleccionada)
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

        return view('livewire.admin.productos.stock-articulos-manager', compact('items', 'listas', 'maestrosDisponibles'));
    }
}

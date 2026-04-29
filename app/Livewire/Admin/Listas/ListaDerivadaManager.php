<?php

namespace App\Livewire\Admin\Listas;

use App\Models\Group;
use App\Models\ListaDerivada;
use App\Models\ListaDerivadaItem;
use App\Models\ListaMaestra;
use App\Models\ListaMaestraItem;
use App\Livewire\Concerns\HasModuleColor;
use Livewire\Component;
use Livewire\WithPagination;

class ListaDerivadaManager extends Component
{
    use WithPagination, HasModuleColor;

    public string $mode   = 'list';   // list | form | items | grupos
    public string $search = '';

    public bool  $editing   = false;
    public ?int  $editingId = null;
    public ?int  $viewingId = null;

    // Cabecera
    public ?int  $lista_maestra_id = null;
    public string $name   = '';
    public string $estado = 'activa';

    // Agregar ítem derivado
    public ?int   $addMaestraItemId = null;
    public string $addDescuento     = '0';
    public string $addStockAsignado = '0';

    // Gestión de grupos
    public ?int $addGroupId = null;

    public function mount(): void
    {
        $this->initModuleColor();
    }

    public function updatingSearch(): void { $this->resetPage(); }

    protected $rules = [
        'lista_maestra_id' => 'required|integer|exists:lista_maestra,id',
        'name'             => 'required|string|min:2',
        'estado'           => 'required|in:activa,cerrada',
    ];

    // ── CRUD cabecera ─────────────────────────────────────────────────────────

    public function create(): void
    {
        $this->resetForm();
        $this->mode = 'form';
    }

    public function edit(int $id): void
    {
        $d = ListaDerivada::findOrFail($id);
        $this->editingId        = $id; $this->editing = true;
        $this->lista_maestra_id = $d->lista_maestra_id;
        $this->name             = $d->name;
        $this->estado           = $d->estado;
        $this->mode = 'form';
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'lista_maestra_id' => $this->lista_maestra_id,
            'name'             => $this->name,
            'estado'           => $this->estado,
        ];

        $this->editing
            ? ListaDerivada::findOrFail($this->editingId)->update($data)
            : ListaDerivada::create($data);

        session()->flash('success', 'Lista Derivada guardada.');
        $this->backToList();
    }

    // ── Gestión de ítems ──────────────────────────────────────────────────────

    public function viewItems(int $id): void
    {
        $this->viewingId        = $id;
        $this->addMaestraItemId = null;
        $this->addDescuento     = '0';
        $this->addStockAsignado = '0';
        $this->mode = 'items';
    }

    public function addItem(): void
    {
        $this->validate([
            'addMaestraItemId' => 'required|integer|exists:lista_maestra_items,id',
            'addDescuento'     => 'required|numeric|min:0',
            'addStockAsignado' => 'required|numeric|min:0',
        ]);

        $exists = ListaDerivadaItem::where('lista_derivada_id', $this->viewingId)
            ->where('lista_maestra_item_id', $this->addMaestraItemId)
            ->exists();

        if ($exists) {
            $this->addError('addMaestraItemId', 'Ese producto ya está en la lista derivada.');
            return;
        }

        ListaDerivadaItem::create([
            'lista_derivada_id'    => $this->viewingId,
            'lista_maestra_item_id' => $this->addMaestraItemId,
            'descuento'            => $this->addDescuento,
            'stock_asignado'       => $this->addStockAsignado,
            'active'               => true,
        ]);

        $this->reset(['addMaestraItemId', 'addDescuento', 'addStockAsignado']);
        $this->addDescuento = '0'; $this->addStockAsignado = '0';
    }

    public function removeItem(int $itemId): void
    {
        ListaDerivadaItem::destroy($itemId);
    }

    public function toggleItemActive(int $itemId): void
    {
        $item = ListaDerivadaItem::findOrFail($itemId);
        $item->update(['active' => !$item->active]);
    }

    // ── Gestión de grupos ─────────────────────────────────────────────────────

    public function viewGrupos(int $id): void
    {
        $this->viewingId  = $id;
        $this->addGroupId = null;
        $this->mode = 'grupos';
    }

    public function addGrupo(): void
    {
        $this->validate(['addGroupId' => 'required|integer|exists:groups,id']);

        $lista = ListaDerivada::findOrFail($this->viewingId);
        if ($lista->groups()->where('group_id', $this->addGroupId)->exists()) {
            $this->addError('addGroupId', 'Ese grupo ya tiene esta lista asignada.');
            return;
        }
        $lista->groups()->attach($this->addGroupId);
        $this->addGroupId = null;
    }

    public function removeGrupo(int $groupId): void
    {
        ListaDerivada::findOrFail($this->viewingId)->groups()->detach($groupId);
    }

    // ─────────────────────────────────────────────────────────────────────────

    public function backToList(): void { $this->resetForm(); $this->mode = 'list'; }

    private function resetForm(): void
    {
        $this->reset(['lista_maestra_id', 'name', 'editingId', 'editing', 'viewingId', 'addGroupId']);
        $this->estado = 'activa';
    }

    public function render()
    {
        $derivadas = ListaDerivada::with('listaMaestra')
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->orderByDesc('created_at')
            ->paginate(15);

        $stats = [
            'total'    => ListaDerivada::count(),
            'activa'   => ListaDerivada::where('estado', 'activa')->count(),
            'activa'   => ListaDerivada::where('estado', 'activa')->count(),
            'cerrada'  => ListaDerivada::where('estado', 'cerrada')->count(),
        ];

        $maestras = ListaMaestra::orderByDesc('created_at')->get();

        $viewingDerivada   = null;
        $items             = collect();
        $maestraItems      = collect();
        $assignedItemIds   = collect();
        $assignedGroups    = collect();
        $availableGroups   = collect();

        if ($this->viewingId) {
            $viewingDerivada = ListaDerivada::with('listaMaestra')->find($this->viewingId);

            if ($this->mode === 'items') {
                $items = ListaDerivadaItem::with('maestraItem.product')
                    ->where('lista_derivada_id', $this->viewingId)
                    ->orderBy('id')
                    ->get();

                $assignedItemIds = $items->pluck('lista_maestra_item_id');

                if ($viewingDerivada?->lista_maestra_id) {
                    $maestraItems = ListaMaestraItem::with('product')
                        ->where('lista_maestra_id', $viewingDerivada->lista_maestra_id)
                        ->whereNotIn('id', $assignedItemIds)
                        ->where('active', true)
                        ->orderBy('id')
                        ->get();
                }
            } elseif ($this->mode === 'grupos') {
                $assignedGroups  = $viewingDerivada?->groups()->orderBy('name')->get() ?? collect();
                $assignedIds     = $assignedGroups->pluck('id');
                $availableGroups = Group::where('active', true)
                    ->whereNotIn('id', $assignedIds)
                    ->orderBy('name')
                    ->get();
            }
        }

        return view('livewire.admin.listas.lista-derivada-manager',
            compact('derivadas', 'stats', 'maestras', 'viewingDerivada', 'items', 'maestraItems', 'assignedGroups', 'availableGroups'));
    }
}

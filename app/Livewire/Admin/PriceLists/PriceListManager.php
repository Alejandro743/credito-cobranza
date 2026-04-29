<?php
namespace App\Livewire\Admin\PriceLists;

use App\Models\PriceList;
use App\Models\PriceListItem;
use App\Models\Product;
use App\Livewire\Concerns\HasModuleColor;
use Livewire\Component;
use Livewire\WithPagination;

class PriceListManager extends Component
{
    use WithPagination, HasModuleColor;

    public string $search = '';
    public bool $showModal = false;
    public bool $showItemsModal = false;
    public bool $editing = false;
    public ?int $editingId = null;
    public ?int $viewingListId = null;

    public string $listName = '';
    public string $description = '';
    public string $valid_from = '';
    public string $valid_to = '';
    public bool $active = true;

    public ?int $itemProductId = null;
    public string $itemPrice = '';
    public string $itemDiscount = '0';

    protected $rules = [
        'listName'   => 'required|string|min:2',
        'valid_from' => 'nullable|date',
        'valid_to'   => 'nullable|date|after_or_equal:valid_from',
        'active'     => 'boolean',
    ];

    public function mount(): void
    {
        $this->initModuleColor();
    }

    public function openCreate(): void
    {
        $this->reset(['listName','description','valid_from','valid_to','editingId','editing']);
        $this->active = true;
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $pl = PriceList::findOrFail($id);
        $this->editingId   = $id;
        $this->editing     = true;
        $this->listName    = $pl->name;
        $this->description = $pl->description ?? '';
        $this->valid_from  = $pl->valid_from?->format('Y-m-d') ?? '';
        $this->valid_to    = $pl->valid_to?->format('Y-m-d') ?? '';
        $this->active      = $pl->active;
        $this->showModal   = true;
    }

    public function save(): void
    {
        $this->validate();
        $data = [
            'name'        => $this->listName,
            'description' => $this->description,
            'valid_from'  => $this->valid_from ?: null,
            'valid_to'    => $this->valid_to ?: null,
            'active'      => $this->active,
        ];
        if ($this->editing) {
            PriceList::findOrFail($this->editingId)->update($data);
        } else {
            PriceList::create($data);
        }
        $this->showModal = false;
        session()->flash('success', 'Lista de precios guardada.');
    }

    public function openItems(int $id): void
    {
        $this->viewingListId = $id;
        $this->reset(['itemProductId','itemPrice','itemDiscount']);
        $this->itemDiscount = '0';
        $this->showItemsModal = true;
    }

    public function addItem(): void
    {
        $this->validate(['itemProductId' => 'required|integer', 'itemPrice' => 'required|numeric|min:0']);
        PriceListItem::updateOrCreate(
            ['price_list_id' => $this->viewingListId, 'product_id' => $this->itemProductId],
            ['price' => $this->itemPrice, 'discount_pct' => $this->itemDiscount ?: 0]
        );
        $this->reset(['itemProductId','itemPrice','itemDiscount']);
        $this->itemDiscount = '0';
    }

    public function removeItem(int $itemId): void
    {
        PriceListItem::destroy($itemId);
    }

    public function toggleActive(int $id): void
    {
        $pl = PriceList::findOrFail($id);
        $pl->update(['active' => !$pl->active]);
    }

    public function render()
    {
        $lists = PriceList::withCount('items')->with('groups')
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->orderByDesc('created_at')
            ->paginate(10);

        $viewingList = $this->viewingListId
            ? PriceList::with('items.product')->find($this->viewingListId)
            : null;

        $products = Product::where('active', true)->orderBy('name')->get();

        return view('livewire.admin.price-lists.price-list-manager', compact('lists','viewingList','products'));
    }
}

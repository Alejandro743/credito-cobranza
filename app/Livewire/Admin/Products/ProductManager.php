<?php

namespace App\Livewire\Admin\Products;

use App\Models\Categoria;
use App\Models\Product;
use App\Models\Unidad;
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

    // Formulario de agregar (inline arriba de tabla)
    public bool   $showAddForm    = false;
    public string $newCode        = '';
    public string $newName        = '';
    public ?int   $newUnidadId    = null;
    public ?int   $newCategoriaId = null;
    public bool   $newActive      = true;
    public        $newImage       = null;

    // Edición inline en fila
    public ?int   $editingId       = null;
    public string $editName        = '';
    public ?int   $editUnidadId    = null;
    public ?int   $editCategoriaId = null;
    public bool   $editActive      = true;
    public        $editImage       = null;
    public string $editCurrentImage = '';

    public function mount(): void
    {
        $this->initModuleColor();
    }

    public function updatingSearch(): void { $this->resetPage(); }

    // ── Agregar ───────────────────────────────────────────────────────────────

    public function showAdd(): void
    {
        $this->showAddForm    = true;
        $this->newCode        = '';
        $this->newName        = '';
        $this->newUnidadId    = null;
        $this->newCategoriaId = null;
        $this->newActive      = true;
        $this->newImage       = null;
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

        Product::create([
            'code'         => strtoupper(trim($this->newCode)),
            'name'         => $this->newName,
            'unidad_id'    => $this->newUnidadId,
            'categoria_id' => $this->newCategoriaId,
            'active'       => $this->newActive,
            'image'        => $imagePath,
        ]);

        $this->showAddForm = false;
        $this->newImage    = null;
        session()->flash('success', 'Producto agregado.');
    }

    // ── Edición inline ────────────────────────────────────────────────────────

    public function startEdit(int $id): void
    {
        $p = Product::findOrFail($id);
        $this->editingId        = $id;
        $this->editName         = $p->name;
        $this->editUnidadId     = $p->unidad_id;
        $this->editCategoriaId  = $p->categoria_id;
        $this->editActive       = $p->active;
        $this->editCurrentImage = $p->image ?? '';
        $this->editImage        = null;
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
            'editName' => ['required', 'string', 'min:2',
                           Rule::unique('products', 'name')->ignore($this->editingId)->whereNull('deleted_at')],
            'editUnidadId'    => 'nullable|integer|exists:unidades,id',
            'editCategoriaId' => 'nullable|integer|exists:categorias,id',
            'editImage'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [], [
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
            'code'         => $this->generarCode($this->editName, $this->editingId),
            'name'         => $this->editName,
            'unidad_id'    => $this->editUnidadId,
            'categoria_id' => $this->editCategoriaId,
            'active'       => $this->editActive,
            'image'        => $imagePath,
        ]);

        $this->editingId        = null;
        $this->editImage        = null;
        $this->editCurrentImage = '';
        session()->flash('success', 'Producto actualizado.');
    }

    // ── Toggle activo ─────────────────────────────────────────────────────────

    public function toggleActive(int $id): void
    {
        $p = Product::findOrFail($id);
        $p->update(['active' => !$p->active]);
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
        $products = Product::with(['categoria', 'unidad'])
            ->when($this->search, fn($q) =>
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('code', 'like', "%{$this->search}%"))
            ->when($this->filterStatus !== '', fn($q) => $q->where('active', (bool) $this->filterStatus))
            ->when($this->filterCategoriaId, fn($q) => $q->where('categoria_id', $this->filterCategoriaId))
            ->orderBy('name')
            ->paginate(20);

        $categorias = Categoria::where('active', true)->orderBy('name')->get();
        $unidades   = Unidad::where('active', true)->orderBy('name')->get();

        return view('livewire.admin.products.product-manager',
            compact('products', 'categorias', 'unidades'));
    }
}

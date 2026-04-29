<?php

namespace App\Livewire\Admin\Categorias;

use App\Models\Categoria;
use Illuminate\Validation\Rule;
use App\Livewire\Concerns\HasModuleColor;
use Livewire\Component;
use Livewire\WithPagination;

class CategoriaManager extends Component
{
    use WithPagination, HasModuleColor;

    public string $search       = '';
    public string $filterStatus = '';

    // Formulario de agregar (inline)
    public bool   $showAddForm    = false;
    public string $newName        = '';
    public string $newDescripcion = '';
    public bool   $newActive      = true;

    // Edición inline en fila
    public ?int   $editingId       = null;
    public string $editName        = '';
    public string $editDescripcion = '';
    public bool   $editActive      = true;

    public function mount(): void
    {
        $this->initModuleColor();
    }

    public function updatingSearch(): void { $this->resetPage(); }

    // ── Agregar ───────────────────────────────────────────────────────────────

    public function showAdd(): void
    {
        $this->showAddForm    = true;
        $this->newName        = '';
        $this->newDescripcion = '';
        $this->newActive      = true;
        $this->cancelEdit();
    }

    public function cancelAdd(): void
    {
        $this->showAddForm = false;
        $this->resetValidation();
    }

    public function saveNew(): void
    {
        $this->validate([
            'newName' => ['required', 'string', 'min:2', 'max:100',
                          Rule::unique('categorias', 'name')],
        ], [], [
            'newName' => 'nombre',
        ]);

        Categoria::create([
            'code'        => $this->generarCode($this->newName),
            'name'        => $this->newName,
            'descripcion' => $this->newDescripcion ?: null,
            'active'      => $this->newActive,
        ]);

        $this->showAddForm = false;
        session()->flash('success', 'Categoría agregada.');
    }

    // ── Edición inline ────────────────────────────────────────────────────────

    public function startEdit(int $id): void
    {
        $c = Categoria::findOrFail($id);
        $this->editingId       = $id;
        $this->editName        = $c->name;
        $this->editDescripcion = $c->descripcion ?? '';
        $this->editActive     = $c->active;
        $this->showAddForm    = false;
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
            'editName' => ['required', 'string', 'min:2', 'max:100',
                           Rule::unique('categorias', 'name')->ignore($this->editingId)],
        ], [], [
            'editName' => 'nombre',
        ]);

        $c = Categoria::findOrFail($this->editingId);

        $c->update([
            'code'        => $this->generarCode($this->editName, $this->editingId),
            'name'        => $this->editName,
            'descripcion' => $this->editDescripcion ?: null,
            'active'      => $this->editActive,
        ]);

        $this->editingId = null;
        session()->flash('success', 'Categoría actualizada.');
    }

    // ── Toggle activo ─────────────────────────────────────────────────────────

    public function toggleActive(int $id): void
    {
        $c = Categoria::findOrFail($id);
        $c->update(['active' => !$c->active]);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function generarCode(string $name, ?int $ignoreId = null): string
    {
        $base = strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $name), 0, 6));
        $code = $base;
        $i    = 1;
        while (
            Categoria::where('code', $code)
                ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $code = $base . $i++;
        }
        return $code;
    }

    public function render()
    {
        $categorias = Categoria::withCount('products')
            ->when($this->search, fn($q) =>
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('code', 'like', "%{$this->search}%"))
            ->when($this->filterStatus !== '', fn($q) => $q->where('active', (bool) $this->filterStatus))
            ->orderBy('name')
            ->paginate(20);

        return view('livewire.admin.categorias.categoria-manager', compact('categorias'));
    }
}

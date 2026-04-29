<?php

namespace App\Livewire\Admin\Unidades;

use App\Models\Unidad;
use Illuminate\Validation\Rule;
use App\Livewire\Concerns\HasModuleColor;
use Livewire\Component;
use Livewire\WithPagination;

class UnidadManager extends Component
{
    use WithPagination, HasModuleColor;

    public string $search       = '';
    public string $filterStatus = '';

    // Formulario de agregar (inline)
    public bool   $showAddForm     = false;
    public string $newName         = '';
    public string $newAbreviatura  = '';
    public bool   $newActive       = true;

    // Edición inline en fila
    public ?int   $editingId       = null;
    public string $editName        = '';
    public string $editAbreviatura = '';
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
        $this->newAbreviatura = '';
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
            'newName'        => ['required', 'string', 'min:2', 'max:100',
                                 Rule::unique('unidades', 'name')],
            'newAbreviatura' => 'nullable|string|max:20',
        ], [], [
            'newName'        => 'nombre',
            'newAbreviatura' => 'abreviatura',
        ]);

        Unidad::create([
            'code'        => $this->generarCode($this->newName),
            'name'        => $this->newName,
            'abreviatura' => $this->newAbreviatura ?: null,
            'active'      => $this->newActive,
        ]);

        $this->showAddForm = false;
        session()->flash('success', 'Unidad agregada.');
    }

    // ── Edición inline ────────────────────────────────────────────────────────

    public function startEdit(int $id): void
    {
        $u = Unidad::findOrFail($id);
        $this->editingId       = $id;
        $this->editName        = $u->name;
        $this->editAbreviatura = $u->abreviatura ?? '';
        $this->editActive     = $u->active;
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
            'editName'        => ['required', 'string', 'min:2', 'max:100',
                                  Rule::unique('unidades', 'name')->ignore($this->editingId)],
            'editAbreviatura' => 'nullable|string|max:20',
        ], [], [
            'editName'        => 'nombre',
            'editAbreviatura' => 'abreviatura',
        ]);

        $u = Unidad::findOrFail($this->editingId);

        $u->update([
            'code'        => $this->generarCode($this->editName, $this->editingId),
            'name'        => $this->editName,
            'abreviatura' => $this->editAbreviatura ?: null,
            'active'      => $this->editActive,
        ]);

        $this->editingId = null;
        session()->flash('success', 'Unidad actualizada.');
    }

    // ── Toggle activo ─────────────────────────────────────────────────────────

    public function toggleActive(int $id): void
    {
        $u = Unidad::findOrFail($id);
        $u->update(['active' => !$u->active]);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function generarCode(string $name, ?int $ignoreId = null): string
    {
        $base = strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $name), 0, 6));
        $code = $base;
        $i    = 1;
        while (
            Unidad::where('code', $code)
                ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $code = $base . $i++;
        }
        return $code;
    }

    public function render()
    {
        $unidades = Unidad::withCount('products')
            ->when($this->search, fn($q) =>
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('code', 'like', "%{$this->search}%"))
            ->when($this->filterStatus !== '', fn($q) => $q->where('active', (bool) $this->filterStatus))
            ->orderBy('name')
            ->paginate(20);

        return view('livewire.admin.unidades.unidad-manager', compact('unidades'));
    }
}

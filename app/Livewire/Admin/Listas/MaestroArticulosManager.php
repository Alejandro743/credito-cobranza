<?php

namespace App\Livewire\Admin\Listas;

use App\Models\Categoria;
use App\Models\MaestroArticulo;
use App\Models\Unidad;
use Illuminate\Validation\Rule;
use App\Livewire\Concerns\HasModuleColor;
use Livewire\Component;
use Livewire\WithPagination;

class MaestroArticulosManager extends Component
{
    use WithPagination, HasModuleColor;

    public string $colFilterCodigo      = '';
    public string $colFilterNombre      = '';
    public string $colFilterCategoriaId = '';
    public string $colFilterUnidadId    = '';
    public string $colFilterEstado      = '';
    public string $sortBy               = 'nombre';
    public string $sortDir      = 'asc';

    // Formulario agregar
    public bool   $showAddForm    = false;
    public string $newCodigo      = '';
    public string $newNombre      = '';
    public string $newDescripcion = '';
    public ?int   $newCategoriaId = null;
    public ?int   $newUnidadId    = null;
    public bool   $newActive      = true;

    // Edición inline
    public ?int   $editingId      = null;
    public string $editCodigo     = '';
    public string $editNombre     = '';
    public string $editDescripcion= '';
    public ?int   $editCategoriaId= null;
    public ?int   $editUnidadId   = null;
    public bool   $editActive     = true;

    // Selección
    public ?int $selectedId = null;

    public function mount(): void
    {
        $this->initModuleColor();
    }

    public function updatingColFilterCodigo(): void      { $this->resetPage(); }
    public function updatingColFilterNombre(): void       { $this->resetPage(); }
    public function updatingColFilterCategoriaId(): void  { $this->resetPage(); }
    public function updatingColFilterUnidadId(): void     { $this->resetPage(); }
    public function updatingColFilterEstado(): void       { $this->resetPage(); }

    public function refrescarGrilla(): void {}

    public function toggleSort(string $col): void
    {
        $this->sortBy  = $this->sortBy === $col ? $this->sortBy : $col;
        $this->sortDir = $this->sortBy === $col && $this->sortDir === 'asc' ? 'desc' : 'asc';
        if ($this->sortBy !== $col) { $this->sortBy = $col; $this->sortDir = 'asc'; }
        $this->resetPage();
    }

    public function selectArticulo(int $id): void
    {
        $this->selectedId = $this->selectedId === $id ? null : $id;
    }

    // ── Agregar ───────────────────────────────────────────────────────────────

    public function showAdd(): void
    {
        $this->showAddForm     = true;
        $this->newCodigo       = '';
        $this->newNombre       = '';
        $this->newDescripcion  = '';
        $this->newCategoriaId  = null;
        $this->newUnidadId     = null;
        $this->newActive       = true;
        $this->cancelEdit();
        $this->resetValidation();
    }

    public function cancelAdd(): void
    {
        $this->showAddForm = false;
        $this->resetValidation();
    }

    public function saveNew(): void
    {
        $this->validate([
            'newCodigo'  => ['required', 'string', 'max:50', Rule::unique('maestro_articulos', 'codigo')],
            'newNombre'  => 'required|string|max:255',
        ], [], [
            'newCodigo' => 'código',
            'newNombre' => 'nombre',
        ]);

        MaestroArticulo::create([
            'codigo'       => strtoupper(trim($this->newCodigo)),
            'nombre'       => trim($this->newNombre),
            'descripcion'  => trim($this->newDescripcion) ?: null,
            'categoria_id' => $this->newCategoriaId,
            'unidad_id'    => $this->newUnidadId,
            'active'       => $this->newActive,
        ]);

        $this->showAddForm = false;
        session()->flash('success', 'Artículo maestro agregado.');
    }

    // ── Edición inline ────────────────────────────────────────────────────────

    public function startEdit(int $id): void
    {
        $a = MaestroArticulo::findOrFail($id);
        $this->editingId       = $id;
        $this->editCodigo      = $a->codigo;
        $this->editNombre      = $a->nombre;
        $this->editDescripcion = $a->descripcion ?? '';
        $this->editCategoriaId = $a->categoria_id;
        $this->editUnidadId    = $a->unidad_id;
        $this->editActive      = $a->active;
        $this->showAddForm     = false;
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
            'editCodigo' => ['required', 'string', 'max:50', Rule::unique('maestro_articulos', 'codigo')->ignore($this->editingId)],
            'editNombre' => 'required|string|max:255',
        ], [], [
            'editCodigo' => 'código',
            'editNombre' => 'nombre',
        ]);

        MaestroArticulo::findOrFail($this->editingId)->update([
            'codigo'       => strtoupper(trim($this->editCodigo)),
            'nombre'       => trim($this->editNombre),
            'descripcion'  => trim($this->editDescripcion) ?: null,
            'categoria_id' => $this->editCategoriaId,
            'unidad_id'    => $this->editUnidadId,
            'active'       => $this->editActive,
        ]);

        $this->editingId = null;
        session()->flash('success', 'Artículo maestro actualizado.');
    }

    public function toggleActive(int $id): void
    {
        $a = MaestroArticulo::findOrFail($id);
        $a->update(['active' => !$a->active]);
    }

    public function render()
    {
        $articulos = MaestroArticulo::with(['categoria', 'unidad'])
            ->when($this->colFilterCodigo,       fn($q) => $q->where('codigo',      'like', "%{$this->colFilterCodigo}%"))
            ->when($this->colFilterNombre,       fn($q) => $q->where('nombre',      'like', "%{$this->colFilterNombre}%"))
            ->when($this->colFilterCategoriaId,  fn($q) => $q->where('categoria_id', $this->colFilterCategoriaId))
            ->when($this->colFilterUnidadId,     fn($q) => $q->where('unidad_id',   $this->colFilterUnidadId))
            ->when($this->colFilterEstado !== '', fn($q) => $q->where('active', (bool)$this->colFilterEstado))
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate(20);

        $categorias = Categoria::orderBy('descripcion')->get();
        $unidades   = Unidad::orderBy('name')->get();

        return view('livewire.admin.listas.maestro-articulos-manager', compact('articulos', 'categorias', 'unidades'));
    }
}

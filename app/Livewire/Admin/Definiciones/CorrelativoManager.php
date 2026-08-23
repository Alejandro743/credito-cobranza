<?php

namespace App\Livewire\Admin\Definiciones;

use App\Livewire\Concerns\HasModuleColor;
use App\Models\ConfiguracionCorrelativo;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class CorrelativoManager extends Component
{
    use WithPagination, HasModuleColor;

    // ── Inline add ────────────────────────────────────────────────────────────
    public bool   $showAddForm       = false;
    public string $newPrefijo        = '';
    public string $newSiguienteNumero= '1';
    public string $newLongitud       = '6';
    public string $newDescripcion    = '';
    public bool   $newActivo         = true;

    // ── Inline edit ───────────────────────────────────────────────────────────
    public ?int   $editingId          = null;
    public string $editPrefijo        = '';
    public string $editSiguienteNumero= '';
    public string $editLongitud       = '';
    public string $editDescripcion    = '';
    public bool   $editActivo         = true;

    // ── Sort ──────────────────────────────────────────────────────────────────
    public string $sortBy  = 'id';
    public string $sortDir = 'asc';

    // ── Selección / filtros por columna ─────────────────────────────────────────
    public ?int $selectedCorrelativoId = null;

    public string $colFilterPrefijo     = '';
    public string $colFilterDescripcion = '';
    public string $colFilterEstado      = '';

    public function updatingColFilterPrefijo(): void     { $this->resetPage(); }
    public function updatingColFilterDescripcion(): void { $this->resetPage(); }
    public function updatingColFilterEstado(): void      { $this->resetPage(); }

    public function selectCorrelativo(int $id): void
    {
        $this->selectedCorrelativoId = $id;
    }

    public function refrescarGrilla(): void {}

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

    // ─────────────────────────────────────────────────────────────────────────

    public function mount(): void
    {
        $this->initModuleColor();
    }

    // ── Inline add ────────────────────────────────────────────────────────────

    public function showAdd(): void
    {
        $this->showAddForm        = true;
        $this->editingId          = null;
        $this->newPrefijo         = '';
        $this->newSiguienteNumero = '1';
        $this->newLongitud        = '6';
        $this->newDescripcion     = '';
        $this->newActivo          = true;
    }

    public function cancelAdd(): void
    {
        $this->showAddForm = false;
    }

    public function saveNew(): void
    {
        $this->validate([
            'newPrefijo'         => ['required','string','max:10','regex:/^[A-Za-z0-9]+$/'],
            'newSiguienteNumero' => ['required','integer','min:1'],
            'newLongitud'        => ['required','integer','min:1','max:10'],
            'newDescripcion'     => ['nullable','string','max:200'],
        ]);

        $c = ConfiguracionCorrelativo::create([
            'prefijo'           => strtoupper($this->newPrefijo),
            'siguiente_numero'  => (int) $this->newSiguienteNumero,
            'longitud'          => (int) $this->newLongitud,
            'descripcion'       => $this->newDescripcion ?: null,
            'activo'            => $this->newActivo,
        ]);

        if ($this->newActivo) {
            $this->desactivarOtros($c->id);
        }

        $this->showAddForm = false;
        session()->flash('success', 'Correlativo creado.');
    }

    // ── Inline edit ───────────────────────────────────────────────────────────

    public function startEdit(int $id): void
    {
        $c = ConfiguracionCorrelativo::findOrFail($id);
        $this->editingId           = $id;
        $this->editPrefijo         = $c->prefijo;
        $this->editSiguienteNumero = (string) $c->siguiente_numero;
        $this->editLongitud        = (string) $c->longitud;
        $this->editDescripcion     = $c->descripcion ?? '';
        $this->editActivo          = $c->activo;
        $this->showAddForm         = false;
    }

    public function cancelEdit(): void
    {
        $this->editingId = null;
    }

    public function saveEdit(): void
    {
        $this->validate([
            'editPrefijo'         => ['required','string','max:10','regex:/^[A-Za-z0-9]+$/'],
            'editSiguienteNumero' => ['required','integer','min:1'],
            'editLongitud'        => ['required','integer','min:1','max:10'],
            'editDescripcion'     => ['nullable','string','max:200'],
        ]);

        $c = ConfiguracionCorrelativo::findOrFail($this->editingId);
        $c->update([
            'prefijo'          => strtoupper($this->editPrefijo),
            'siguiente_numero' => (int) $this->editSiguienteNumero,
            'longitud'         => (int) $this->editLongitud,
            'descripcion'      => $this->editDescripcion ?: null,
            'activo'           => $this->editActivo,
        ]);

        if ($this->editActivo) {
            $this->desactivarOtros($c->id);
        }

        $this->editingId = null;
        session()->flash('success', 'Correlativo actualizado.');
    }

    // ── Toggle activo ─────────────────────────────────────────────────────────

    public function toggleActivo(int $id): void
    {
        $c = ConfiguracionCorrelativo::findOrFail($id);
        $nuevoEstado = !$c->activo;

        if (!$nuevoEstado && ConfiguracionCorrelativo::where('activo', true)->count() === 1) {
            session()->flash('error', 'Debe haber siempre un correlativo activo.');
            return;
        }

        $c->update(['activo' => $nuevoEstado]);
        if ($nuevoEstado) {
            $this->desactivarOtros($id);
        }
    }

    // ─────────────────────────────────────────────────────────────────────────

    private function desactivarOtros(int $exceptId): void
    {
        ConfiguracionCorrelativo::where('id', '!=', $exceptId)->update(['activo' => false]);
    }

    // ── Delete ────────────────────────────────────────────────────────────────

    public function delete(int $id): void
    {
        if (ConfiguracionCorrelativo::count() === 1) {
            session()->flash('error', 'No se puede eliminar el único correlativo registrado.');
            return;
        }

        ConfiguracionCorrelativo::findOrFail($id)->delete();
        session()->flash('success', 'Correlativo eliminado.');
    }

    // ── Render ────────────────────────────────────────────────────────────────

    public function render()
    {
        $allowed = ['prefijo', 'descripcion', 'siguiente_numero', 'longitud', 'activo'];
        $col = in_array($this->sortBy, $allowed) ? $this->sortBy : 'id';
        $correlativos = ConfiguracionCorrelativo::query()
            ->when($this->colFilterPrefijo,      fn ($q) => $q->where('prefijo', 'like', '%' . $this->colFilterPrefijo . '%'))
            ->when($this->colFilterDescripcion,  fn ($q) => $q->where('descripcion', 'like', '%' . $this->colFilterDescripcion . '%'))
            ->when($this->colFilterEstado !== '', fn ($q) => $q->where('activo', (bool) $this->colFilterEstado))
            ->orderBy($col, $this->sortDir)
            ->paginate(20);
        return view('livewire.admin.definiciones.correlativo-manager', compact('correlativos'));
    }
}

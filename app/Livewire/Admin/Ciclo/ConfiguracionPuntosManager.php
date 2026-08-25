<?php

namespace App\Livewire\Admin\Ciclo;

use App\Models\CommercialCycle;
use App\Models\ConfiguracionPuntos;
use Illuminate\Validation\Rule;
use App\Livewire\Concerns\HasModuleColor;
use Livewire\Component;
use Livewire\WithPagination;

class ConfiguracionPuntosManager extends Component
{
    use WithPagination, HasModuleColor;

    public string $sortBy       = 'code';
    public string $sortDir      = 'asc';

    public string $colFilterCiclo       = '';
    public string $colFilterValor       = '';
    public string $colFilterDescripcion = '';
    public string $colFilterEstado      = '';

    public ?int $selectedPuntoId = null;

    public function selectPunto(int $id): void
    {
        $this->selectedPuntoId = $this->selectedPuntoId === $id ? null : $id;
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

    public function updatingColFilterCiclo(): void       { $this->resetPage(); }
    public function updatingColFilterValor(): void        { $this->resetPage(); }
    public function updatingColFilterDescripcion(): void  { $this->resetPage(); }
    public function updatingColFilterEstado(): void       { $this->resetPage(); }

    // Inline add
    public bool   $showAddForm     = false;
    public ?int   $newCycleId      = null;
    public string $newValorPunto   = '1.00';
    public string $newDescription  = '';
    public int    $newActive        = 1;

    // Inline row edit
    public ?int   $editingId       = null;
    public ?int   $editCycleId     = null;
    public string $editValorPunto  = '1.00';
    public string $editDescription = '';
    public int    $editActive      = 1;

    public function mount(): void
    {
        $this->initModuleColor();
    }

    // ── Inline add ────────────────────────────────────────────────────────────

    public function showAdd(): void
    {
        $this->showAddForm    = true;
        $this->newCycleId     = null;
        $this->newValorPunto  = '1.00';
        $this->newDescription = '';
        $this->newActive      = 1;
        $this->editingId      = null;
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
            'newCycleId'    => 'required|integer|exists:commercial_cycles,id|unique:configuracion_puntos,cycle_id',
            'newValorPunto' => 'required|numeric|min:0.01',
        ], [
            'newCycleId.unique' => 'Ese ciclo ya tiene un valor de punto configurado.',
        ], [
            'newCycleId'    => 'ciclo',
            'newValorPunto' => 'valor del punto',
        ]);

        ConfiguracionPuntos::create([
            'cycle_id'    => $this->newCycleId,
            'valor_punto' => $this->newValorPunto,
            'description' => $this->newDescription ?: null,
            'active'      => $this->newActive,
        ]);

        $this->showAddForm = false;
        session()->flash('success', 'Configuración de puntos creada.');
    }

    // ── Inline row edit ───────────────────────────────────────────────────────

    public function startEdit(int $id): void
    {
        $p = ConfiguracionPuntos::findOrFail($id);
        $this->editingId       = $id;
        $this->editCycleId     = $p->cycle_id;
        $this->editValorPunto  = number_format((float) $p->valor_punto, 2, '.', '');
        $this->editDescription = $p->description ?? '';
        $this->editActive      = $p->active ? 1 : 0;
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
            'editCycleId'    => ['required', 'integer', 'exists:commercial_cycles,id', Rule::unique('configuracion_puntos', 'cycle_id')->ignore($this->editingId)],
            'editValorPunto' => 'required|numeric|min:0.01',
        ], [
            'editCycleId.unique' => 'Ese ciclo ya tiene un valor de punto configurado.',
        ], [
            'editCycleId'    => 'ciclo',
            'editValorPunto' => 'valor del punto',
        ]);

        ConfiguracionPuntos::findOrFail($this->editingId)->update([
            'cycle_id'    => $this->editCycleId,
            'valor_punto' => $this->editValorPunto,
            'description' => $this->editDescription ?: null,
            'active'      => $this->editActive,
        ]);

        $this->editingId = null;
        session()->flash('success', 'Configuración actualizada.');
    }

    public function toggleActive(int $id): void
    {
        $p = ConfiguracionPuntos::findOrFail($id);
        $p->update(['active' => !$p->active]);
    }

    // ─────────────────────────────────────────────────────────────────────────

    public function render()
    {
        $puntos = ConfiguracionPuntos::with('cycle')
            ->join('commercial_cycles', 'commercial_cycles.id', '=', 'configuracion_puntos.cycle_id')
            ->when($this->colFilterCiclo, fn($q) => $q->where(function ($qq) {
                    $qq->where('commercial_cycles.code', 'like', "%{$this->colFilterCiclo}%")
                       ->orWhere('commercial_cycles.name', 'like', "%{$this->colFilterCiclo}%");
                }))
            ->when($this->colFilterValor !== '', fn($q) =>
                $q->whereRaw("CAST(configuracion_puntos.valor_punto AS CHAR) LIKE ?", ["%{$this->colFilterValor}%"]))
            ->when($this->colFilterDescripcion, fn($q) =>
                $q->where('configuracion_puntos.description', 'like', "%{$this->colFilterDescripcion}%"))
            ->when($this->colFilterEstado !== '', fn($q) => $q->where('configuracion_puntos.active', (bool) $this->colFilterEstado))
            ->when($this->sortBy === 'code',        fn($q) => $q->orderBy('commercial_cycles.code', $this->sortDir))
            ->when($this->sortBy === 'valor_punto', fn($q) => $q->orderBy('configuracion_puntos.valor_punto', $this->sortDir))
            ->when($this->sortBy === 'active',      fn($q) => $q->orderBy('configuracion_puntos.active', $this->sortDir))
            ->when(!$this->sortBy,                  fn($q) => $q->orderBy('commercial_cycles.code'))
            ->select('configuracion_puntos.*')
            ->paginate(15);

        // Ciclos que aún no tienen puntos configurados
        $ciclosDisponibles = CommercialCycle::whereNotIn('id',
                ConfiguracionPuntos::pluck('cycle_id'))
            ->orderByDesc('start_date')
            ->get();

        return view('livewire.admin.ciclo.configuracion-puntos-manager',
            compact('puntos', 'ciclosDisponibles'));
    }
}

<?php

namespace App\Livewire\Admin\Cycles;

use App\Models\CommercialCycle;
use App\Models\FinancialMatrix;
use Illuminate\Validation\Rule;
use App\Livewire\Concerns\HasModuleColor;
use Livewire\Component;
use Livewire\WithPagination;

class CycleManager extends Component
{
    use WithPagination, HasModuleColor;

    public string $sortBy       = 'code';
    public string $sortDir      = 'asc';

    public string $colFilterCodigo      = '';
    public string $colFilterDescripcion = '';
    public string $colFilterInicio      = '';
    public string $colFilterFin         = '';
    public string $colFilterEstado      = '';

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

    public function updatingColFilterCodigo(): void      { $this->resetPage(); }
    public function updatingColFilterDescripcion(): void { $this->resetPage(); }
    public function updatingColFilterInicio(): void      { $this->resetPage(); }
    public function updatingColFilterFin(): void         { $this->resetPage(); }
    public function updatingColFilterEstado(): void      { $this->resetPage(); }

    // Inline add
    public bool   $showAddForm  = false;
    public string $newCode      = '';
    public string $newName      = '';
    public string $newStartDate = '';
    public string $newEndDate   = '';
    public string $newStatus    = 'abierto';
    public string $newNotes     = '';

    // Inline row edit
    public ?int   $editingId    = null;
    public string $editCode     = '';
    public string $editName     = '';
    public string $editStartDate = '';
    public string $editEndDate   = '';
    public string $editStatus    = 'abierto';
    public string $editNotes     = '';

    public ?int $selectedCycleId = null;

    public function selectCycle(int $id): void
    {
        $this->selectedCycleId = $this->selectedCycleId === $id ? null : $id;
    }

    public function mount(): void
    {
        $this->initModuleColor();
    }

    // ── Inline add ────────────────────────────────────────────────────────────

    public function showAdd(): void
    {
        $this->showAddForm  = true;
        $this->newCode      = '';
        $this->newName      = '';
        $this->newStartDate = '';
        $this->newEndDate   = '';
        $this->newStatus    = 'abierto';
        $this->newNotes     = '';
        $this->editingId    = null;
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
            'newCode'      => 'required|string|max:30|unique:commercial_cycles,code',
            'newName'      => 'required|string|min:2',
            'newStartDate' => 'required|date',
            'newEndDate'   => 'required|date|after_or_equal:newStartDate',
            'newStatus'    => 'required|in:abierto,cerrado',
        ], [], [
            'newCode'      => 'código',
            'newName'      => 'nombre',
            'newStartDate' => 'fecha inicio',
            'newEndDate'   => 'fecha fin',
            'newStatus'    => 'estado',
        ]);

        if ($this->datesOverlap($this->newStartDate, $this->newEndDate)) {
            $this->addError('newStartDate', 'Las fechas se cruzan con un ciclo existente.');
            return;
        }

        $cycle = CommercialCycle::create([
            'code'       => strtoupper(trim($this->newCode)),
            'name'       => $this->newName,
            'start_date' => $this->newStartDate,
            'end_date'   => $this->newEndDate,
            'status'     => $this->newStatus,
            'notes'      => $this->newNotes ?: null,
        ]);

        if ($this->newStatus === 'abierto') {
            $this->cerrarOtros($cycle->id);
        }

        $this->showAddForm = false;
        $this->resetPage();
        session()->flash('success', 'Ciclo creado.');
    }

    // ── Inline row edit ───────────────────────────────────────────────────────

    public function startEdit(int $id): void
    {
        $c = CommercialCycle::findOrFail($id);
        $this->editingId     = $id;
        $this->editCode      = $c->code;
        $this->editName      = $c->name;
        $this->editStartDate = $c->start_date->format('Y-m-d');
        $this->editEndDate   = $c->end_date->format('Y-m-d');
        $this->editStatus    = $c->status;
        $this->editNotes     = $c->notes ?? '';
        $this->showAddForm   = false;
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
            'editCode'      => ['required', 'string', 'max:30', Rule::unique('commercial_cycles', 'code')->ignore($this->editingId)],
            'editName'      => 'required|string|min:2',
            'editStartDate' => 'required|date',
            'editEndDate'   => 'required|date|after_or_equal:editStartDate',
            'editStatus'    => 'required|in:abierto,cerrado',
        ], [], [
            'editCode'      => 'código',
            'editName'      => 'descripción',
            'editStartDate' => 'fecha inicio',
            'editEndDate'   => 'fecha fin',
            'editStatus'    => 'estado',
        ]);

        if ($this->datesOverlap($this->editStartDate, $this->editEndDate, $this->editingId)) {
            $this->addError('editStartDate', 'Las fechas se cruzan con otro ciclo existente.');
            return;
        }

        CommercialCycle::findOrFail($this->editingId)->update([
            'code'       => strtoupper(trim($this->editCode)),
            'name'       => $this->editName,
            'start_date' => $this->editStartDate,
            'end_date'   => $this->editEndDate,
            'status'     => $this->editStatus,
            'notes'      => $this->editNotes ?: null,
        ]);

        if ($this->editStatus === 'abierto') {
            $this->cerrarOtros($this->editingId);
        }

        $this->editingId = null;
        $this->resetPage();
        session()->flash('success', 'Ciclo actualizado.');
    }

    public function delete(int $id): void
    {
        $c = CommercialCycle::findOrFail($id);

        $enlazado = $c->listasMaestra()->exists()
            || $c->configuracionPuntos()->exists()
            || FinancialMatrix::where('cycle_id', $id)->exists();

        if ($enlazado) {
            session()->flash('error', 'No se puede eliminar: el ciclo ya tiene registros enlazados.');
            return;
        }

        $c->delete();
        session()->flash('success', 'Ciclo eliminado.');
    }

    public function changeStatus(int $id, string $status): void
    {
        CommercialCycle::findOrFail($id)->update(['status' => $status]);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function cerrarOtros(int $exceptId): void
    {
        CommercialCycle::where('id', '!=', $exceptId)
            ->where('status', 'abierto')
            ->update(['status' => 'cerrado']);
    }

    private function datesOverlap(string $start, string $end, ?int $excludeId = null): bool
    {
        return CommercialCycle::where(function ($q) use ($start, $end) {
                $q->where('start_date', '<=', $end)
                  ->where('end_date', '>=', $start);
            })
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->exists();
    }

    // ─────────────────────────────────────────────────────────────────────────

    public function render()
    {
        $cycles = CommercialCycle::when($this->colFilterCodigo,      fn($q) => $q->where('code', 'like', "%{$this->colFilterCodigo}%"))
                    ->when($this->colFilterDescripcion, fn($q) => $q->where('name', 'like', "%{$this->colFilterDescripcion}%"))
                    ->when($this->colFilterInicio,      fn($q) => $q->where('start_date', '>=', $this->colFilterInicio))
                    ->when($this->colFilterFin,         fn($q) => $q->where('end_date', '<=', $this->colFilterFin))
                    ->when($this->colFilterEstado !== '', fn($q) => $q->where('status', $this->colFilterEstado))
                    ->orderBy($this->sortBy, $this->sortDir)
                    ->paginate(15);

        return view('livewire.admin.cycles.cycle-manager', compact('cycles'));
    }
}

<?php

namespace App\Livewire\Admin\Cycles;

use App\Models\CommercialCycle;
use Illuminate\Validation\Rule;
use App\Livewire\Concerns\HasModuleColor;
use Livewire\Component;
use Livewire\WithPagination;

class CycleManager extends Component
{
    use WithPagination, HasModuleColor;

    public string $search       = '';
    public string $filterStatus = '';

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
    public string $editName     = '';
    public string $editStartDate = '';
    public string $editEndDate   = '';
    public string $editStatus    = 'abierto';
    public string $editNotes     = '';

    public function mount(): void
    {
        $this->initModuleColor();
    }

    public function updatingSearch(): void { $this->resetPage(); }

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

        CommercialCycle::create([
            'code'       => strtoupper(trim($this->newCode)),
            'name'       => $this->newName,
            'start_date' => $this->newStartDate,
            'end_date'   => $this->newEndDate,
            'status'     => $this->newStatus,
            'notes'      => $this->newNotes ?: null,
        ]);

        $this->showAddForm = false;
        session()->flash('success', 'Ciclo creado.');
    }

    // ── Inline row edit ───────────────────────────────────────────────────────

    public function startEdit(int $id): void
    {
        $c = CommercialCycle::findOrFail($id);
        $this->editingId     = $id;
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
            'editName'      => 'required|string|min:2',
            'editStartDate' => 'required|date',
            'editEndDate'   => 'required|date|after_or_equal:editStartDate',
            'editStatus'    => 'required|in:abierto,cerrado',
        ], [], [
            'editName'      => 'nombre',
            'editStartDate' => 'fecha inicio',
            'editEndDate'   => 'fecha fin',
            'editStatus'    => 'estado',
        ]);

        if ($this->datesOverlap($this->editStartDate, $this->editEndDate, $this->editingId)) {
            $this->addError('editStartDate', 'Las fechas se cruzan con otro ciclo existente.');
            return;
        }

        CommercialCycle::findOrFail($this->editingId)->update([
            'name'       => $this->editName,
            'start_date' => $this->editStartDate,
            'end_date'   => $this->editEndDate,
            'status'     => $this->editStatus,
            'notes'      => $this->editNotes ?: null,
        ]);

        $this->editingId = null;
        session()->flash('success', 'Ciclo actualizado.');
    }

    public function changeStatus(int $id, string $status): void
    {
        CommercialCycle::findOrFail($id)->update(['status' => $status]);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

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
        $cycles = CommercialCycle::when($this->search, fn($q) =>
                        $q->where('code', 'like', "%{$this->search}%")
                          ->orWhere('name', 'like', "%{$this->search}%"))
                    ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
                    ->orderByDesc('start_date')
                    ->paginate(15);

        return view('livewire.admin.cycles.cycle-manager', compact('cycles'));
    }
}

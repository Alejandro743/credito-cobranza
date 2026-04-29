<?php

namespace App\Livewire\Admin\Rules;

use App\Models\Group;
use App\Models\Rule;
use App\Livewire\Concerns\HasModuleColor;
use Livewire\Component;
use Livewire\WithPagination;

class RuleManager extends Component
{
    use WithPagination, HasModuleColor;

    public string $mode   = 'list';
    public string $search = '';
    public string $filterType = '';

    public bool  $editing   = false;
    public ?int  $editingId = null;

    public string $name        = '';
    public string $type        = 'segmento';
    public string $condicion   = '';
    public string $description = '';
    public string $priority    = '0';
    public bool   $active      = true;
    public array  $selectedGroups = [];

    public function mount(): void
    {
        $this->initModuleColor();
    }

    public function updatingSearch(): void { $this->resetPage(); }

    protected $rules = [
        'name'     => 'required|string|min:2',
        'type'     => 'required|in:segmento,geografica,comercial,personalizado',
        'priority' => 'integer|min:0',
        'active'   => 'boolean',
    ];

    public function create(): void { $this->resetForm(); $this->mode = 'form'; }

    public function edit(int $id): void
    {
        $r = Rule::with('groups')->findOrFail($id);
        $this->editingId      = $id; $this->editing = true;
        $this->name           = $r->name;
        $this->type           = $r->type;
        $this->condicion      = $r->condicion ?? '';
        $this->description    = $r->description ?? '';
        $this->priority       = (string) $r->priority;
        $this->active         = $r->active;
        $this->selectedGroups = $r->groups->pluck('id')->map(fn($v) => (string) $v)->toArray();
        $this->mode = 'form';
    }

    public function save(): void
    {
        $this->validate();
        $data = [
            'name'        => $this->name,
            'type'        => $this->type,
            'condicion'   => $this->condicion,
            'description' => $this->description,
            'priority'    => $this->priority,
            'active'      => $this->active,
        ];

        if ($this->editing) {
            $rule = Rule::findOrFail($this->editingId);
            $rule->update($data);
        } else {
            $rule = Rule::create($data);
        }

        $rule->groups()->sync($this->selectedGroups);
        session()->flash('success', 'Regla guardada.');
        $this->backToList();
    }

    public function toggleActive(int $id): void
    {
        $r = Rule::findOrFail($id);
        $r->update(['active' => !$r->active]);
    }

    public function backToList(): void { $this->resetForm(); $this->mode = 'list'; }

    private function resetForm(): void
    {
        $this->reset(['name', 'condicion', 'description', 'selectedGroups', 'editingId', 'editing']);
        $this->type     = 'segmento';
        $this->priority = '0';
        $this->active   = true;
    }

    public function render()
    {
        $rules = Rule::withCount('groups')
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->when($this->filterType, fn($q) => $q->where('type', $this->filterType))
            ->orderBy('priority', 'desc')->orderBy('name')
            ->paginate(15);

        $groups = Group::where('active', true)->orderBy('name')->get();

        $stats = [
            'total'   => Rule::count(),
            'activas' => Rule::where('active', true)->count(),
        ];

        return view('livewire.admin.rules.rule-manager', compact('rules', 'groups', 'stats'));
    }
}

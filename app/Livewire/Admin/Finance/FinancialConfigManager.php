<?php
namespace App\Livewire\Admin\Finance;

use App\Models\FinancialPlan;
use App\Models\FinancialCondition;
use App\Livewire\Concerns\HasModuleColor;
use Livewire\Component;
use Livewire\WithPagination;

class FinancialConfigManager extends Component
{
    use WithPagination, HasModuleColor;

    public string $search = '';
    public bool $showModal = false;
    public bool $showCondModal = false;
    public bool $editing = false;
    public ?int $editingId = null;
    public ?int $viewingPlanId = null;

    public string $planName = '';
    public string $description = '';
    public string $interest_rate = '0';
    public string $term_months = '12';
    public string $min_amount = '0';
    public string $max_amount = '';
    public bool $active = true;

    public string $condType = '';
    public string $condOperator = '=';
    public string $condValue = '';
    public string $condDescription = '';

    protected $rules = [
        'planName'      => 'required|string|min:2',
        'interest_rate' => 'required|numeric|min:0',
        'term_months'   => 'required|integer|min:1',
        'min_amount'    => 'required|numeric|min:0',
        'max_amount'    => 'nullable|numeric|min:0',
        'active'        => 'boolean',
    ];

    public function mount(): void
    {
        $this->initModuleColor();
    }

    public function openCreate(): void
    {
        $this->reset(['planName','description','interest_rate','term_months','min_amount','max_amount','editingId','editing']);
        $this->interest_rate = '0'; $this->term_months = '12'; $this->min_amount = '0'; $this->active = true;
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $p = FinancialPlan::findOrFail($id);
        $this->editingId = $id; $this->editing = true;
        $this->planName = $p->name; $this->description = $p->description ?? '';
        $this->interest_rate = $p->interest_rate; $this->term_months = $p->term_months;
        $this->min_amount = $p->min_amount; $this->max_amount = $p->max_amount ?? '';
        $this->active = $p->active;
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();
        $data = ['name'=>$this->planName,'description'=>$this->description,'interest_rate'=>$this->interest_rate,
                 'term_months'=>$this->term_months,'min_amount'=>$this->min_amount,
                 'max_amount'=>$this->max_amount ?: null,'active'=>$this->active];
        if ($this->editing) { FinancialPlan::findOrFail($this->editingId)->update($data); }
        else { FinancialPlan::create($data); }
        $this->showModal = false;
        session()->flash('success', 'Plan guardado.');
    }

    public function openConditions(int $id): void
    {
        $this->viewingPlanId = $id;
        $this->reset(['condType','condOperator','condValue','condDescription']);
        $this->condOperator = '=';
        $this->showCondModal = true;
    }

    public function addCondition(): void
    {
        $this->validate(['condType' => 'required|string', 'condValue' => 'required|string']);
        FinancialCondition::create([
            'financial_plan_id' => $this->viewingPlanId,
            'condition_type'    => $this->condType,
            'operator'          => $this->condOperator,
            'value'             => $this->condValue,
            'description'       => $this->condDescription,
        ]);
        $this->reset(['condType','condValue','condDescription']); $this->condOperator = '=';
    }

    public function removeCondition(int $id): void { FinancialCondition::destroy($id); }

    public function toggleActive(int $id): void
    {
        $p = FinancialPlan::findOrFail($id); $p->update(['active' => !$p->active]);
    }

    public function render()
    {
        $plans = FinancialPlan::withCount('conditions')
            ->when($this->search, fn($q) => $q->where('name','like',"%{$this->search}%"))
            ->orderBy('name')->paginate(10);
        $viewingPlan = $this->viewingPlanId ? FinancialPlan::with('conditions')->find($this->viewingPlanId) : null;
        return view('livewire.admin.finance.financial-config-manager', compact('plans','viewingPlan'));
    }
}

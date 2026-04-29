<?php

namespace App\Livewire\Admin\Finance;

use App\Models\CommercialCycle;
use App\Models\FinancialMatrix;
use Illuminate\Validation\Rule;
use App\Livewire\Concerns\HasModuleColor;
use Livewire\Component;
use Livewire\WithPagination;

class MatrizFinancieraManager extends Component
{
    use WithPagination, HasModuleColor;

    public string $mode   = 'list';   // list | config
    public string $search = '';
    public string $filterStatus = '';

    // ── Inline add ────────────────────────────────────────────────────────────
    public bool   $showAddForm = false;
    public string $newCode        = '';
    public ?int   $newCycleId     = null;
    public string $newName        = '';
    public string $newDescription = '';
    public bool   $newActive      = true;

    // ── Inline edit ───────────────────────────────────────────────────────────
    public ?int   $editingId      = null;
    public string $editCode       = '';
    public ?int   $editCycleId    = null;
    public string $editName       = '';
    public string $editDescription= '';
    public bool   $editActive     = true;

    // ── Config mode ───────────────────────────────────────────────────────────
    public ?int   $configId = null;

    // Campos de configuración financiera (se editan solo en modo config)
    public string $cfgCantidadCuotas    = '1';
    public bool   $cfgUsaCuotaInicial   = false;
    public string $cfgTipoCuotaInicial  = 'porcentaje';
    public string $cfgValorCuotaInicial = '';
    public bool   $cfgUsaIncremento     = false;
    public string $cfgTipoIncremento    = 'porcentaje';
    public string $cfgValorIncremento   = '';

    // Simulador
    public string $simMonto  = '';
    public ?array $simResult = null;

    public function mount(): void
    {
        $this->initModuleColor();
    }

    public function updatingSearch(): void { $this->resetPage(); }

    // ── Inline add ────────────────────────────────────────────────────────────

    public function showAdd(): void
    {
        $this->showAddForm    = true;
        $this->newCode        = '';
        $this->newCycleId     = null;
        $this->newName        = '';
        $this->newDescription = '';
        $this->newActive      = true;
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
            'newCode'    => ['required', 'string', 'max:30', Rule::unique('financial_matrices', 'code')->whereNull('deleted_at')],
            'newCycleId' => 'nullable|integer|exists:commercial_cycles,id',
        ], [], [
            'newCode'    => 'código',
            'newCycleId' => 'ciclo',
        ]);

        $code = strtoupper(trim($this->newCode));
        FinancialMatrix::create([
            'code'        => $code,
            'cycle_id'    => $this->newCycleId,
            'name'        => $this->newDescription ?: $code,
            'description' => $this->newDescription ?: null,
            'active'      => $this->newActive,
        ]);

        $this->showAddForm = false;
        session()->flash('success', 'Matriz creada.');
    }

    // ── Inline edit ───────────────────────────────────────────────────────────

    public function startEdit(int $id): void
    {
        $m = FinancialMatrix::findOrFail($id);
        $this->editingId      = $id;
        $this->editCode       = $m->code;
        $this->editCycleId    = $m->cycle_id;
        $this->editName       = $m->name;
        $this->editDescription= $m->description ?? '';
        $this->editActive     = (bool) $m->active;
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
            'editCode'    => ['required', 'string', 'max:30', Rule::unique('financial_matrices', 'code')->ignore($this->editingId)->whereNull('deleted_at')],
            'editCycleId' => 'nullable|integer|exists:commercial_cycles,id',
        ], [], [
            'editCode'    => 'código',
            'editCycleId' => 'ciclo',
        ]);

        $code = strtoupper(trim($this->editCode));
        FinancialMatrix::findOrFail($this->editingId)->update([
            'code'        => $code,
            'cycle_id'    => $this->editCycleId,
            'name'        => $this->editDescription ?: $code,
            'description' => $this->editDescription ?: null,
            'active'      => $this->editActive,
        ]);

        $this->editingId = null;
        session()->flash('success', 'Matriz actualizada.');
    }

    public function toggleActive(int $id): void
    {
        $m = FinancialMatrix::findOrFail($id);
        $m->update(['active' => !$m->active]);
    }

    // ── Config mode ───────────────────────────────────────────────────────────

    public function openConfig(int $id): void
    {
        $m = FinancialMatrix::findOrFail($id);
        $this->configId             = $id;
        $this->cfgCantidadCuotas    = (string) $m->cantidad_cuotas;
        $this->cfgUsaCuotaInicial   = (bool) $m->usa_cuota_inicial;
        $this->cfgTipoCuotaInicial  = $m->tipo_cuota_inicial ?? 'porcentaje';
        $this->cfgValorCuotaInicial = $m->valor_cuota_inicial ?? '';
        $this->cfgUsaIncremento     = (bool) $m->usa_incremento;
        $this->cfgTipoIncremento    = $m->tipo_incremento ?? 'porcentaje';
        $this->cfgValorIncremento   = $m->valor_incremento ?? '';
        $this->simMonto             = '';
        $this->simResult            = null;
        $this->mode                 = 'config';
        $this->editingId            = null;
        $this->showAddForm          = false;
        $this->resetValidation();
    }

    public function saveConfig(): void
    {
        $this->validate([
            'cfgCantidadCuotas'    => 'required|integer|min:1',
            'cfgValorCuotaInicial' => 'required_if:cfgUsaCuotaInicial,true|nullable|numeric|min:0',
            'cfgValorIncremento'   => 'required_if:cfgUsaIncremento,true|nullable|numeric|min:0',
        ], [], [
            'cfgCantidadCuotas'    => 'cantidad de cuotas',
            'cfgValorCuotaInicial' => 'valor cuota inicial',
            'cfgValorIncremento'   => 'valor incremento',
        ]);

        FinancialMatrix::findOrFail($this->configId)->update([
            'cantidad_cuotas'     => (int) $this->cfgCantidadCuotas,
            'usa_cuota_inicial'   => $this->cfgUsaCuotaInicial,
            'tipo_cuota_inicial'  => $this->cfgUsaCuotaInicial ? $this->cfgTipoCuotaInicial : null,
            'valor_cuota_inicial' => $this->cfgUsaCuotaInicial ? (float) $this->cfgValorCuotaInicial : null,
            'usa_incremento'      => $this->cfgUsaIncremento,
            'tipo_incremento'     => $this->cfgUsaIncremento ? $this->cfgTipoIncremento : null,
            'valor_incremento'    => $this->cfgUsaIncremento ? (float) $this->cfgValorIncremento : null,
        ]);

        session()->flash('success', 'Configuración guardada.');
    }

    public function backToList(): void
    {
        $this->mode      = 'list';
        $this->configId  = null;
        $this->simMonto  = '';
        $this->simResult = null;
    }

    public function simular(): void
    {
        $this->validate(['simMonto' => 'required|numeric|min:0.01'], [], ['simMonto' => 'monto']);

        $m     = FinancialMatrix::findOrFail($this->configId);
        $monto = (float) $this->simMonto;

        $cuotaInicial   = 0;
        $saldoFinanciar = $monto;

        if ($m->usa_cuota_inicial && $m->valor_cuota_inicial > 0) {
            $cuotaInicial = $m->tipo_cuota_inicial === 'porcentaje'
                ? round($monto * $m->valor_cuota_inicial / 100, 2)
                : (float) $m->valor_cuota_inicial;
            $saldoFinanciar = max(0, $monto - $cuotaInicial);
        }

        $incrementoAplicado = 0;
        $saldoConIncremento = $saldoFinanciar;

        if ($m->usa_incremento && $m->valor_incremento > 0) {
            $incrementoAplicado = $m->tipo_incremento === 'porcentaje'
                ? round($saldoFinanciar * $m->valor_incremento / 100, 2)
                : (float) $m->valor_incremento;
            $saldoConIncremento = $saldoFinanciar + $incrementoAplicado;
        }

        $cuotas     = max(1, (int) $m->cantidad_cuotas);
        $montoCuota = $cuotas > 1 ? round($saldoConIncremento / $cuotas, 2) : $saldoConIncremento;
        $totalPagar = round($cuotaInicial + $saldoConIncremento, 2);

        $this->simResult = [
            'cuota_inicial'   => $cuotaInicial,
            'saldo_financiar' => $saldoFinanciar,
            'incremento'      => $incrementoAplicado,
            'monto_cuota'     => $montoCuota,
            'cantidad_cuotas' => $cuotas,
            'total_pagar'     => $totalPagar,
            'es_contado'      => $m->isContado(),
        ];
    }

    // ─────────────────────────────────────────────────────────────────────────

    public function render()
    {
        $matrices = FinancialMatrix::with('cycle')
            ->when($this->search, fn($q) =>
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('code', 'like', "%{$this->search}%"))
            ->when($this->filterStatus !== '', fn($q) =>
                $q->where('active', (bool) $this->filterStatus))
            ->orderBy('name')
            ->paginate(15);

        $cycles = CommercialCycle::orderByDesc('start_date')->get(['id', 'code', 'name']);

        $configMatriz = $this->configId
            ? FinancialMatrix::with('cycle')->find($this->configId)
            : null;

        return view('livewire.admin.finance.matriz-financiera-manager',
            compact('matrices', 'cycles', 'configMatriz'));
    }
}

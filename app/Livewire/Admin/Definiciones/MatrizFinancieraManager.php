<?php

namespace App\Livewire\Admin\Definiciones;

use App\Models\FinancialMatrix;
use Livewire\Component;

class MatrizFinancieraManager extends Component
{
    public bool    $showForm = false;
    public ?int    $editId   = null;

    public string $code               = '';
    public string $name               = '';
    public string $description        = '';
    public bool   $active             = true;
    public string $cantidadCuotas     = '1';
    public bool   $usaCuotaInicial    = false;
    public string $tipoCuotaInicial   = 'porcentaje';
    public string $valorCuotaInicial  = '';
    public bool   $usaIncremento      = false;
    public string $tipoIncremento     = 'porcentaje';
    public string $valorIncremento    = '';

    public string $sortBy  = 'code';
    public string $sortDir = 'asc';

    public ?int $selectedMatrizId = null;

    public string $colFilterCodigo = '';
    public string $colFilterNombre = '';
    public string $colFilterEstado = '';

    public function updatingColFilterCodigo(): void { }
    public function updatingColFilterNombre(): void { }
    public function updatingColFilterEstado(): void { }

    public function selectMatriz(int $id): void
    {
        $this->selectedMatrizId = $this->selectedMatrizId === $id ? null : $id;
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
    }

    public function create(): void
    {
        $this->resetForm();
        $this->resetErrorBag();
        $this->selectedMatrizId = null;
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $m = FinancialMatrix::findOrFail($id);
        $this->resetErrorBag();
        $this->editId            = $m->id;
        $this->code              = $m->code;
        $this->name              = $m->name;
        $this->description       = $m->description ?? '';
        $this->active            = (bool) $m->active;
        $this->cantidadCuotas    = (string) $m->cantidad_cuotas;
        $this->usaCuotaInicial   = (bool) $m->usa_cuota_inicial;
        $this->tipoCuotaInicial  = $m->tipo_cuota_inicial ?? 'porcentaje';
        $this->valorCuotaInicial = $m->valor_cuota_inicial !== null ? (string) $m->valor_cuota_inicial : '';
        $this->usaIncremento     = (bool) $m->usa_incremento;
        $this->tipoIncremento    = $m->tipo_incremento ?? 'porcentaje';
        $this->valorIncremento   = $m->valor_incremento !== null ? (string) $m->valor_incremento : '';
        $this->showForm          = false;
    }

    public function save(): void
    {
        $rules = [
            'code'          => 'required|string|max:30|unique:financial_matrices,code' . ($this->editId ? ",{$this->editId}" : ''),
            'name'          => 'required|string|max:150',
            'cantidadCuotas'=> 'required|integer|min:1|max:120',
        ];
        if ($this->usaCuotaInicial) {
            $rules['tipoCuotaInicial']  = 'required|in:porcentaje,monto_fijo';
            $rules['valorCuotaInicial'] = 'required|numeric|min:0';
        }
        if ($this->usaIncremento) {
            $rules['tipoIncremento']  = 'required|in:porcentaje,monto_fijo';
            $rules['valorIncremento'] = 'required|numeric|min:0';
        }

        $this->validate($rules);

        $data = [
            'code'               => strtoupper(trim($this->code)),
            'name'               => trim($this->name),
            'description'        => trim($this->description) ?: null,
            'active'             => $this->active,
            'cantidad_cuotas'    => (int) $this->cantidadCuotas,
            'usa_cuota_inicial'  => $this->usaCuotaInicial,
            'tipo_cuota_inicial' => $this->usaCuotaInicial ? $this->tipoCuotaInicial : null,
            'valor_cuota_inicial'=> $this->usaCuotaInicial ? (float) $this->valorCuotaInicial : null,
            'usa_incremento'     => $this->usaIncremento,
            'tipo_incremento'    => $this->usaIncremento ? $this->tipoIncremento : null,
            'valor_incremento'   => $this->usaIncremento ? (float) $this->valorIncremento : null,
        ];

        if ($this->editId) {
            FinancialMatrix::findOrFail($this->editId)->update($data);
        } else {
            FinancialMatrix::create($data);
        }

        session()->flash('success', 'Matriz guardada.');
        $this->cancelar();
    }

    public function cancelar(): void
    {
        $this->resetForm();
        $this->resetErrorBag();
        $this->showForm = false;
    }

    private function resetForm(): void
    {
        $this->editId            = null;
        $this->code              = '';
        $this->name              = '';
        $this->description       = '';
        $this->active            = true;
        $this->cantidadCuotas    = '1';
        $this->usaCuotaInicial   = false;
        $this->tipoCuotaInicial  = 'porcentaje';
        $this->valorCuotaInicial = '';
        $this->usaIncremento     = false;
        $this->tipoIncremento    = 'porcentaje';
        $this->valorIncremento   = '';
    }

    public function render()
    {
        $registros = FinancialMatrix::query()
            ->when($this->colFilterCodigo, fn ($q) => $q->where('code', 'like', "%{$this->colFilterCodigo}%"))
            ->when($this->colFilterNombre, fn ($q) => $q->where('name', 'like', "%{$this->colFilterNombre}%"))
            ->when($this->colFilterEstado !== '', fn ($q) => $q->where('active', (bool) $this->colFilterEstado))
            ->orderBy($this->sortBy, $this->sortDir)
            ->get();

        return view('livewire.admin.definiciones.matriz-financiera-manager', [
            'registros' => $registros,
        ]);
    }
}

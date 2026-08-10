<?php

namespace App\Livewire\Admin\Definiciones;

use App\Models\PesoIndicador;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PesoIndicadorManager extends Component
{
    public string $sortBy  = '';
    public string $sortDir = 'asc';

    public ?int $selectedPesoId = null;
    public ?int $editingId      = null;
    public bool $showAddForm    = false;

    public string $colFilterNombre  = '';
    public string $colFilterEstado  = '';
    public string $colFilterVigente = '';

    public string $newNombre             = '';
    public float  $newPesoPuntualidad    = 25;
    public float  $newPesoMora           = 25;
    public float  $newPesoRiesgo         = 20;
    public float  $newPesoRecuperacion   = 20;
    public float  $newPesoReprogramacion = 10;
    public int    $newActivo             = 1;

    public string $editNombre             = '';
    public float  $editPesoPuntualidad    = 25;
    public float  $editPesoMora           = 25;
    public float  $editPesoRiesgo         = 20;
    public float  $editPesoRecuperacion   = 20;
    public float  $editPesoReprogramacion = 10;
    public int    $editActivo             = 1;

    public function toggleSort(string $col): void
    {
        if ($this->sortBy === $col) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy  = $col;
            $this->sortDir = 'asc';
        }
    }

    public function showAdd(): void
    {
        $this->resetNewForm();
        $this->resetErrorBag();
        $this->showAddForm = true;
    }

    public function cancelAdd(): void
    {
        $this->resetNewForm();
        $this->resetErrorBag();
        $this->showAddForm = false;
    }

    public function selectPeso(int $id): void
    {
        $this->selectedPesoId = $this->selectedPesoId === $id ? null : $id;
    }

    public function startEdit(int $id): void
    {
        $p = PesoIndicador::findOrFail($id);

        if ($p->fecha_fin !== null) {
            session()->flash('error', 'Solo se puede editar la configuración vigente. Los registros históricos quedan bloqueados.');
            return;
        }

        $this->resetErrorBag();
        $this->editingId              = $p->id;
        $this->selectedPesoId         = $p->id;
        $this->editNombre             = $p->nombre;
        $this->editPesoPuntualidad    = $p->peso_puntualidad;
        $this->editPesoMora           = $p->peso_mora;
        $this->editPesoRiesgo         = $p->peso_riesgo;
        $this->editPesoRecuperacion   = $p->peso_recuperacion;
        $this->editPesoReprogramacion = $p->peso_reprogramacion;
        $this->editActivo             = $p->activo ? 1 : 0;
    }

    public function cancelEdit(): void
    {
        $this->editingId = null;
        $this->resetErrorBag();
    }

    public function saveNew(): void
    {
        $this->validate([
            'newNombre'             => 'required|string|max:100',
            'newPesoPuntualidad'    => 'required|numeric|min:0|max:100',
            'newPesoMora'           => 'required|numeric|min:0|max:100',
            'newPesoRiesgo'         => 'required|numeric|min:0|max:100',
            'newPesoRecuperacion'   => 'required|numeric|min:0|max:100',
            'newPesoReprogramacion' => 'required|numeric|min:0|max:100',
        ]);

        $total = $this->newPesoPuntualidad + $this->newPesoMora + $this->newPesoRiesgo
               + $this->newPesoRecuperacion + $this->newPesoReprogramacion;

        if (round($total, 2) !== 100.0) {
            $this->addError('newPesoPuntualidad', "La suma de pesos debe ser 100%. Actualmente: {$total}%");
            return;
        }

        DB::transaction(function () {
            $ahora = now();

            $anterior = PesoIndicador::abierta();
            if ($anterior) {
                $anterior->update(['fecha_fin' => $ahora->copy()->subMinute()]);
            }

            PesoIndicador::create([
                'nombre'              => $this->newNombre,
                'fecha_inicio'        => $ahora,
                'fecha_fin'           => null,
                'peso_puntualidad'    => $this->newPesoPuntualidad,
                'peso_mora'           => $this->newPesoMora,
                'peso_riesgo'         => $this->newPesoRiesgo,
                'peso_recuperacion'   => $this->newPesoRecuperacion,
                'peso_reprogramacion' => $this->newPesoReprogramacion,
                'activo'              => (bool) $this->newActivo,
            ]);
        });

        session()->flash('success', 'Configuración creada. Pasa a ser la vigente.');
        $this->resetNewForm();
        $this->showAddForm = false;
    }

    public function saveEdit(): void
    {
        $p = PesoIndicador::findOrFail($this->editingId);
        if ($p->fecha_fin !== null) {
            $this->editingId = null;
            session()->flash('error', 'Este registro ya quedó histórico y no se puede editar.');
            return;
        }

        $this->validate([
            'editNombre'             => 'required|string|max:100',
            'editPesoPuntualidad'    => 'required|numeric|min:0|max:100',
            'editPesoMora'           => 'required|numeric|min:0|max:100',
            'editPesoRiesgo'         => 'required|numeric|min:0|max:100',
            'editPesoRecuperacion'   => 'required|numeric|min:0|max:100',
            'editPesoReprogramacion' => 'required|numeric|min:0|max:100',
        ]);

        $total = $this->editPesoPuntualidad + $this->editPesoMora + $this->editPesoRiesgo
               + $this->editPesoRecuperacion + $this->editPesoReprogramacion;

        if (round($total, 2) !== 100.0) {
            $this->addError('editPesoPuntualidad', "La suma de pesos debe ser 100%. Actualmente: {$total}%");
            return;
        }

        if ((int) $this->editActivo === 0 && PesoIndicador::vigente()?->id === $this->editingId) {
            session()->flash('error', 'No se puede inactivar: es la configuración vigente y no hay otra que la reemplace.');
            return;
        }

        $p->update([
            'nombre'              => $this->editNombre,
            'peso_puntualidad'    => $this->editPesoPuntualidad,
            'peso_mora'           => $this->editPesoMora,
            'peso_riesgo'         => $this->editPesoRiesgo,
            'peso_recuperacion'   => $this->editPesoRecuperacion,
            'peso_reprogramacion' => $this->editPesoReprogramacion,
            'activo'              => (bool) $this->editActivo,
        ]);

        session()->flash('success', 'Configuración actualizada.');
        $this->editingId = null;
    }

    private function resetNewForm(): void
    {
        $this->newNombre             = '';
        $this->newPesoPuntualidad    = 25;
        $this->newPesoMora           = 25;
        $this->newPesoRiesgo         = 20;
        $this->newPesoRecuperacion   = 20;
        $this->newPesoReprogramacion = 10;
        $this->newActivo             = 1;
    }

    public function render()
    {
        $vigenteId = PesoIndicador::vigente()?->id;

        $query = PesoIndicador::query()
            ->when($this->colFilterNombre !== '', fn($q) => $q->where('nombre', 'like', "%{$this->colFilterNombre}%"))
            ->when($this->colFilterEstado !== '', fn($q) => $q->where('activo', $this->colFilterEstado))
            ->when($this->colFilterVigente === '1', fn($q) => $q->where('id', $vigenteId ?? 0))
            ->when($this->colFilterVigente === '0', fn($q) => $q->where('id', '!=', $vigenteId ?? 0));

        if ($this->sortBy) {
            $query->orderBy($this->sortBy, $this->sortDir);
        } else {
            $query->orderByDesc('fecha_inicio');
        }

        $registros = $query->get();

        return view('livewire.admin.definiciones.peso-indicador-manager', [
            'registros' => $registros,
            'vigenteId' => $vigenteId,
        ]);
    }
}

<?php

namespace App\Livewire\Admin\Definiciones;

use App\Models\RangoCalificacion;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class RangoCalificacionManager extends Component
{
    public string $sortBy  = '';
    public string $sortDir = 'asc';

    public ?int $selectedRangoId = null;
    public ?int $editingId       = null;
    public bool $showAddForm     = false;

    public string $colFilterNombre  = '';
    public string $colFilterEstado  = '';
    public string $colFilterVigente = '';

    public string $newNombre = '';
    public float  $newMinA   = 85;
    public float  $newMinB   = 70;
    public float  $newMinC   = 50;
    public float  $newMinD   = 30;
    public int    $newActivo = 1;

    public string $editNombre = '';
    public float  $editMinA   = 85;
    public float  $editMinB   = 70;
    public float  $editMinC   = 50;
    public float  $editMinD   = 30;
    public int    $editActivo = 1;

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

    public function selectRango(int $id): void
    {
        $this->selectedRangoId = $this->selectedRangoId === $id ? null : $id;
    }

    public function refrescarGrilla(): void {}

    public function startEdit(int $id): void
    {
        $r = RangoCalificacion::findOrFail($id);

        if ($r->fecha_fin !== null) {
            session()->flash('error', 'Solo se puede editar la configuración vigente. Los registros históricos quedan bloqueados.');
            return;
        }

        $this->resetErrorBag();
        $this->editingId        = $r->id;
        $this->selectedRangoId  = $r->id;
        $this->editNombre       = $r->nombre;
        $this->editMinA         = $r->min_a;
        $this->editMinB         = $r->min_b;
        $this->editMinC         = $r->min_c;
        $this->editMinD         = $r->min_d;
        $this->editActivo       = $r->activo ? 1 : 0;
    }

    public function cancelEdit(): void
    {
        $this->editingId = null;
        $this->resetErrorBag();
    }

    public function saveNew(): void
    {
        $this->validate([
            'newNombre' => 'required|string|max:100',
            'newMinA'   => 'required|numeric|min:0|max:100',
            'newMinB'   => 'required|numeric|min:0|max:100',
            'newMinC'   => 'required|numeric|min:0|max:100',
            'newMinD'   => 'required|numeric|min:0|max:100',
        ]);

        if (!($this->newMinA > $this->newMinB && $this->newMinB > $this->newMinC && $this->newMinC > $this->newMinD && $this->newMinD >= 0)) {
            $this->addError('newMinA', 'Los umbrales deben ser A > B > C > D ≥ 0.');
            return;
        }

        DB::transaction(function () {
            $ahora = now();

            $anterior = RangoCalificacion::abierta();
            if ($anterior) {
                $anterior->update(['fecha_fin' => $ahora->copy()->subMinute()]);
            }

            RangoCalificacion::create([
                'nombre'       => $this->newNombre,
                'fecha_inicio' => $ahora,
                'fecha_fin'    => null,
                'min_a'        => $this->newMinA,
                'min_b'        => $this->newMinB,
                'min_c'        => $this->newMinC,
                'min_d'        => $this->newMinD,
                'activo'       => (bool) $this->newActivo,
            ]);
        });

        session()->flash('success', 'Configuración creada. Pasa a ser la vigente.');
        $this->resetNewForm();
        $this->showAddForm = false;
    }

    public function saveEdit(): void
    {
        $r = RangoCalificacion::findOrFail($this->editingId);
        if ($r->fecha_fin !== null) {
            $this->editingId = null;
            session()->flash('error', 'Este registro ya quedó histórico y no se puede editar.');
            return;
        }

        $this->validate([
            'editNombre' => 'required|string|max:100',
            'editMinA'   => 'required|numeric|min:0|max:100',
            'editMinB'   => 'required|numeric|min:0|max:100',
            'editMinC'   => 'required|numeric|min:0|max:100',
            'editMinD'   => 'required|numeric|min:0|max:100',
        ]);

        if (!($this->editMinA > $this->editMinB && $this->editMinB > $this->editMinC && $this->editMinC > $this->editMinD && $this->editMinD >= 0)) {
            $this->addError('editMinA', 'Los umbrales deben ser A > B > C > D ≥ 0.');
            return;
        }

        if ((int) $this->editActivo === 0 && RangoCalificacion::vigente()?->id === $this->editingId) {
            session()->flash('error', 'No se puede inactivar: es la configuración vigente y no hay otra que la reemplace.');
            return;
        }

        $r->update([
            'nombre' => $this->editNombre,
            'min_a'  => $this->editMinA,
            'min_b'  => $this->editMinB,
            'min_c'  => $this->editMinC,
            'min_d'  => $this->editMinD,
            'activo' => (bool) $this->editActivo,
        ]);

        session()->flash('success', 'Configuración actualizada.');
        $this->editingId = null;
    }

    private function resetNewForm(): void
    {
        $this->newNombre = '';
        $this->newMinA   = 85;
        $this->newMinB   = 70;
        $this->newMinC   = 50;
        $this->newMinD   = 30;
        $this->newActivo = 1;
    }

    public function render()
    {
        $vigenteId = RangoCalificacion::vigente()?->id;

        $query = RangoCalificacion::query()
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

        return view('livewire.admin.definiciones.rango-calificacion-manager', [
            'registros' => $registros,
            'vigenteId' => $vigenteId,
        ]);
    }
}

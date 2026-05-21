<?php

namespace App\Livewire\Admin\Definiciones;

use App\Models\RangoCalificacion;
use Livewire\Component;

class RangoCalificacionManager extends Component
{
    public string $mode = 'list';

    public ?int   $editId      = null;
    public string $nombre      = '';
    public string $fechaInicio = '';
    public string $fechaFin    = '';
    public float  $minA        = 85;
    public float  $minB        = 70;
    public float  $minC        = 50;
    public float  $minD        = 30;
    public int    $activo      = 1;

    public function create(): void
    {
        $this->resetForm();
        $this->resetErrorBag();
        $this->fechaInicio = now()->toDateString();
        $this->mode = 'form';
    }

    public function edit(int $id): void
    {
        $r = RangoCalificacion::findOrFail($id);
        $this->resetForm();
        $this->resetErrorBag();
        $this->editId      = $r->id;
        $this->nombre      = $r->nombre;
        $this->fechaInicio = $r->fecha_inicio->toDateString();
        $this->fechaFin    = $r->fecha_fin?->toDateString() ?? '';
        $this->minA        = $r->min_a;
        $this->minB        = $r->min_b;
        $this->minC        = $r->min_c;
        $this->minD        = $r->min_d;
        $this->activo      = $r->activo ? 1 : 0;
        $this->mode = 'form';
    }

    public function save(): void
    {
        $this->validate([
            'nombre'      => 'required|string|max:100',
            'fechaInicio' => 'required|date',
            'fechaFin'    => 'nullable|date|after_or_equal:fechaInicio',
            'minA'        => 'required|numeric|min:0|max:100',
            'minB'        => 'required|numeric|min:0|max:100',
            'minC'        => 'required|numeric|min:0|max:100',
            'minD'        => 'required|numeric|min:0|max:100',
        ]);

        if (!($this->minA > $this->minB && $this->minB > $this->minC && $this->minC > $this->minD && $this->minD >= 0)) {
            $this->addError('minA', 'Los umbrales deben ser A > B > C > D ≥ 0.');
            return;
        }

        if ($this->editId && (int) $this->activo === 0 && RangoCalificacion::vigente()?->id === $this->editId) {
            $today = \Carbon\Carbon::today();
            $hayOtro = RangoCalificacion::where('activo', true)
                ->where('id', '!=', $this->editId)
                ->where('fecha_inicio', '<=', $today)
                ->where(fn($q) => $q->whereNull('fecha_fin')->orWhere('fecha_fin', '>=', $today))
                ->exists();
            if (!$hayOtro) {
                $this->addError('activo', 'No se puede inactivar: es la única configuración vigente para hoy.');
                return;
            }
        }

        $data = [
            'nombre'       => $this->nombre,
            'fecha_inicio' => $this->fechaInicio,
            'fecha_fin'    => $this->fechaFin ?: null,
            'min_a'        => $this->minA,
            'min_b'        => $this->minB,
            'min_c'        => $this->minC,
            'min_d'        => $this->minD,
            'activo'       => (bool) $this->activo,
        ];

        if ($this->editId) {
            RangoCalificacion::findOrFail($this->editId)->update($data);
        } else {
            RangoCalificacion::create($data);
        }

        session()->flash('success', 'Configuración guardada.');
        $this->backToList();
    }

    public function toggleActivo(int $id): void
    {
        $r = RangoCalificacion::findOrFail($id);

        if ($r->activo && RangoCalificacion::vigente()?->id === $id) {
            $today = \Carbon\Carbon::today();
            $hayOtro = RangoCalificacion::where('activo', true)
                ->where('id', '!=', $id)
                ->where('fecha_inicio', '<=', $today)
                ->where(fn($q) => $q->whereNull('fecha_fin')->orWhere('fecha_fin', '>=', $today))
                ->exists();
            if (!$hayOtro) {
                session()->flash('error', 'No se puede inactivar: es la única configuración vigente para hoy.');
                return;
            }
        }

        $r->update(['activo' => !$r->activo]);
    }

    public function backToList(): void
    {
        $this->resetForm();
        $this->mode = 'list';
    }

    private function resetForm(): void
    {
        $this->editId      = null;
        $this->nombre      = '';
        $this->fechaInicio = '';
        $this->fechaFin    = '';
        $this->minA        = 85;
        $this->minB        = 70;
        $this->minC        = 50;
        $this->minD        = 30;
        $this->activo      = 1;
    }

    public function render()
    {
        $vigenteId = RangoCalificacion::vigente()?->id;
        $registros = RangoCalificacion::orderByDesc('fecha_inicio')->get()
            ->sortByDesc(function ($r) use ($vigenteId) {
                if ($r->id === $vigenteId) return 2;
                if ($r->activo)            return 1;
                return 0;
            })->values();

        return view('livewire.admin.definiciones.rango-calificacion-manager', compact('registros'));
    }
}

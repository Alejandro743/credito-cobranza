<?php

namespace App\Livewire\Admin\Definiciones;

use App\Models\PesoIndicador;
use Livewire\Component;

class PesoIndicadorManager extends Component
{
    public string $mode = 'list';

    public ?int    $editId             = null;
    public string  $nombre             = '';
    public string  $fechaInicio        = '';
    public string  $fechaFin           = '';
    public float   $pesoPuntualidad    = 25;
    public float   $pesoMora           = 25;
    public float   $pesoRiesgo         = 20;
    public float   $pesoRecuperacion   = 20;
    public float   $pesoReprogramacion = 10;
    public int     $activo             = 1;

    public function create(): void
    {
        $this->resetForm();
        $this->resetErrorBag();
        $this->fechaInicio = now()->toDateString();
        $this->mode = 'form';
    }

    public function edit(int $id): void
    {
        $p = PesoIndicador::findOrFail($id);
        $this->resetForm();
        $this->resetErrorBag();
        $this->editId             = $p->id;
        $this->nombre             = $p->nombre;
        $this->fechaInicio        = $p->fecha_inicio->toDateString();
        $this->fechaFin           = $p->fecha_fin?->toDateString() ?? '';
        $this->pesoPuntualidad    = $p->peso_puntualidad;
        $this->pesoMora           = $p->peso_mora;
        $this->pesoRiesgo         = $p->peso_riesgo;
        $this->pesoRecuperacion   = $p->peso_recuperacion;
        $this->pesoReprogramacion = $p->peso_reprogramacion;
        $this->activo             = $p->activo ? 1 : 0;
        $this->mode = 'form';
    }

    public function save(): void
    {
        $this->validate([
            'nombre'             => 'required|string|max:100',
            'fechaInicio'        => 'required|date',
            'fechaFin'           => 'nullable|date|after_or_equal:fechaInicio',
            'pesoPuntualidad'    => 'required|numeric|min:0|max:100',
            'pesoMora'           => 'required|numeric|min:0|max:100',
            'pesoRiesgo'         => 'required|numeric|min:0|max:100',
            'pesoRecuperacion'   => 'required|numeric|min:0|max:100',
            'pesoReprogramacion' => 'required|numeric|min:0|max:100',
        ]);

        $total = $this->pesoPuntualidad + $this->pesoMora + $this->pesoRiesgo
               + $this->pesoRecuperacion + $this->pesoReprogramacion;

        if (round($total, 2) !== 100.0) {
            $this->addError('pesoPuntualidad', "La suma de pesos debe ser 100%. Actualmente: {$total}%");
            return;
        }

        if ($this->editId && (int) $this->activo === 0 && PesoIndicador::vigente()?->id === $this->editId) {
            $today = \Carbon\Carbon::today();
            $hayOtro = PesoIndicador::where('activo', true)
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
            'nombre'              => $this->nombre,
            'fecha_inicio'        => $this->fechaInicio,
            'fecha_fin'           => $this->fechaFin ?: null,
            'peso_puntualidad'    => $this->pesoPuntualidad,
            'peso_mora'           => $this->pesoMora,
            'peso_riesgo'         => $this->pesoRiesgo,
            'peso_recuperacion'   => $this->pesoRecuperacion,
            'peso_reprogramacion' => $this->pesoReprogramacion,
            'activo'              => (bool) $this->activo,
        ];

        if ($this->editId) {
            PesoIndicador::findOrFail($this->editId)->update($data);
        } else {
            PesoIndicador::create($data);
        }

        session()->flash('success', 'Configuración guardada.');
        $this->backToList();
    }

    public function toggleActivo(int $id): void
    {
        $p = PesoIndicador::findOrFail($id);

        if ($p->activo && PesoIndicador::vigente()?->id === $id) {
            $today = \Carbon\Carbon::today();
            $hayOtro = PesoIndicador::where('activo', true)
                ->where('id', '!=', $id)
                ->where('fecha_inicio', '<=', $today)
                ->where(fn($q) => $q->whereNull('fecha_fin')->orWhere('fecha_fin', '>=', $today))
                ->exists();
            if (!$hayOtro) {
                session()->flash('error', 'No se puede inactivar: es la única configuración vigente para hoy.');
                return;
            }
        }

        $p->update(['activo' => !$p->activo]);
    }

    public function backToList(): void
    {
        $this->resetForm();
        $this->mode = 'list';
    }

    private function resetForm(): void
    {
        $this->editId             = null;
        $this->nombre             = '';
        $this->fechaInicio        = '';
        $this->fechaFin           = '';
        $this->pesoPuntualidad    = 25;
        $this->pesoMora           = 25;
        $this->pesoRiesgo         = 20;
        $this->pesoRecuperacion   = 20;
        $this->pesoReprogramacion = 10;
        $this->activo             = 1;
    }

    public function render()
    {
        $vigenteId = PesoIndicador::vigente()?->id;
        $registros = PesoIndicador::orderByDesc('fecha_inicio')->get()
            ->sortByDesc(function ($r) use ($vigenteId) {
                if ($r->id === $vigenteId) return 2;
                if ($r->activo)            return 1;
                return 0;
            })->values();

        return view('livewire.admin.definiciones.peso-indicador-manager', compact('registros'));
    }
}

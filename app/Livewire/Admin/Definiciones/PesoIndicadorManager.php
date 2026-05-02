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
    public bool    $activo             = true;

    public function create(): void
    {
        $this->resetForm();
        $this->fechaInicio = now()->toDateString();
        $this->mode = 'form';
    }

    public function edit(int $id): void
    {
        $p = PesoIndicador::findOrFail($id);
        $this->editId             = $p->id;
        $this->nombre             = $p->nombre;
        $this->fechaInicio        = $p->fecha_inicio->toDateString();
        $this->fechaFin           = $p->fecha_fin?->toDateString() ?? '';
        $this->pesoPuntualidad    = $p->peso_puntualidad;
        $this->pesoMora           = $p->peso_mora;
        $this->pesoRiesgo         = $p->peso_riesgo;
        $this->pesoRecuperacion   = $p->peso_recuperacion;
        $this->pesoReprogramacion = $p->peso_reprogramacion;
        $this->activo             = $p->activo;
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

        $data = [
            'nombre'              => $this->nombre,
            'fecha_inicio'        => $this->fechaInicio,
            'fecha_fin'           => $this->fechaFin ?: null,
            'peso_puntualidad'    => $this->pesoPuntualidad,
            'peso_mora'           => $this->pesoMora,
            'peso_riesgo'         => $this->pesoRiesgo,
            'peso_recuperacion'   => $this->pesoRecuperacion,
            'peso_reprogramacion' => $this->pesoReprogramacion,
            'activo'              => $this->activo,
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
        $p->update(['activo' => !$p->activo]);
    }

    public function delete(int $id): void
    {
        PesoIndicador::findOrFail($id)->delete();
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
        $this->activo             = true;
    }

    public function render()
    {
        return view('livewire.admin.definiciones.peso-indicador-manager', [
            'registros' => PesoIndicador::orderByDesc('fecha_inicio')->get(),
        ]);
    }
}

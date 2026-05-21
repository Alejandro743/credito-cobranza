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

    public function updatedActivo(int $value): void
    {
        if ($value === 0 && $this->editId &&
            PesoIndicador::where('activo', true)->where('id', '!=', $this->editId)->count() === 0) {
            $this->activo = 1;
            $this->addError('activo', 'No se puede colocar estado: Inactivo. Debe haber siempre mínimo una configuración activa.');
        }
    }

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

        // Validar que no quede un hueco sin cobertura
        $hueco = $this->detectarHueco($this->fechaInicio, $this->fechaFin ?: null, $this->editId);
        if ($hueco) {
            $this->addError('fechaInicio',
                "Hay un vacío sin cobertura del {$hueco['desde']} al {$hueco['hasta']}. " .
                "Ajustá la fecha de inicio o la fecha fin del rango anterior.");
            return;
        }

        $cierreHasta = \Carbon\Carbon::parse($this->fechaInicio)->subDay()->toDateString();

        if ($this->editId) {
            if ($this->activo === 0 && PesoIndicador::where('activo', true)->where('id', '!=', $this->editId)->count() === 0) {
                $this->addError('activo', 'Debe haber siempre una configuración activa.');
                return;
            }

            PesoIndicador::findOrFail($this->editId)->update($data);
            PesoIndicador::where('id', '!=', $this->editId)
                ->whereNull('fecha_fin')
                ->where('fecha_inicio', '<', $this->fechaInicio)
                ->update(['fecha_fin' => $cierreHasta]);
        } else {
            PesoIndicador::whereNull('fecha_fin')
                ->where('fecha_inicio', '<', $this->fechaInicio)
                ->update(['fecha_fin' => $cierreHasta]);
            PesoIndicador::create($data);
        }

        session()->flash('success', 'Configuración guardada.');
        $this->backToList();
    }

    public function toggleActivo(int $id): void
    {
        $p = PesoIndicador::findOrFail($id);
        $nuevoEstado = !$p->activo;

        if (!$nuevoEstado && PesoIndicador::where('activo', true)->count() === 1) {
            session()->flash('error', 'Debe haber siempre una configuración activa.');
            return;
        }

        $p->update(['activo' => $nuevoEstado]);
    }

    public function delete(int $id): void
    {
        if (PesoIndicador::count() === 1) {
            session()->flash('error', 'No se puede eliminar la única configuración registrada.');
            return;
        }

        PesoIndicador::findOrFail($id)->delete();
        session()->flash('success', 'Configuración eliminada.');
    }

    public function backToList(): void
    {
        $this->resetForm();
        $this->mode = 'list';
    }

    private function detectarHueco(string $fechaInicio, ?string $fechaFin, ?int $excludeId = null): ?array
    {
        $inicio = \Carbon\Carbon::parse($fechaInicio);

        // Hueco ANTES: si hay rango abierto anterior, el auto-cierre lo cubre → sin hueco
        $tieneAbiertoAntes = PesoIndicador::whereNull('fecha_fin')
            ->where('fecha_inicio', '<', $inicio)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->exists();

        if (!$tieneAbiertoAntes) {
            $anterior = PesoIndicador::whereNotNull('fecha_fin')
                ->where('fecha_fin', '<', $inicio)
                ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
                ->orderByDesc('fecha_fin')
                ->first();

            if ($anterior) {
                $diaHuecoDesde = \Carbon\Carbon::parse($anterior->fecha_fin)->addDay();
                $diaHuecoHasta = $inicio->copy()->subDay();

                if ($diaHuecoDesde->lte($diaHuecoHasta)) {
                    return [
                        'desde' => $diaHuecoDesde->format('d/m/Y'),
                        'hasta' => $diaHuecoHasta->format('d/m/Y'),
                    ];
                }
            }
        }

        // Hueco DESPUÉS: si el nuevo rango tiene fecha_fin, verificar que no quede vacío antes del siguiente
        if ($fechaFin) {
            $fin = \Carbon\Carbon::parse($fechaFin);
            $siguiente = PesoIndicador::where('fecha_inicio', '>', $fin)
                ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
                ->orderBy('fecha_inicio')
                ->first();

            if ($siguiente) {
                $diaHuecoDesde = $fin->copy()->addDay();
                $diaHuecoHasta = \Carbon\Carbon::parse($siguiente->fecha_inicio)->subDay();

                if ($diaHuecoDesde->lte($diaHuecoHasta)) {
                    return [
                        'desde' => $diaHuecoDesde->format('d/m/Y'),
                        'hasta' => $diaHuecoHasta->format('d/m/Y'),
                    ];
                }
            }
        }

        return null;
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
        return view('livewire.admin.definiciones.peso-indicador-manager', [
            'registros' => PesoIndicador::orderByDesc('fecha_inicio')->get(),
        ]);
    }
}

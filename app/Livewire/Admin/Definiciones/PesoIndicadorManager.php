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

    public function updatedFechaInicio(): void
    {
        if (!$this->fechaInicio) return;
        $this->resetErrorBag('fechaInicio');

        $hueco = $this->detectarHuecoAntes($this->fechaInicio, $this->editId);
        if ($hueco) {
            $this->addError('fechaInicio',
                "Hay un vacío sin cobertura del {$hueco['desde']} al {$hueco['hasta']}. " .
                "Ajustá la fecha de inicio o la fecha fin del rango anterior.");
            return;
        }

        if ($this->esElUltimoRegistro() && \Carbon\Carbon::parse($this->fechaInicio)->gt(\Carbon\Carbon::today())) {
            $this->addError('fechaInicio',
                'La fecha de inicio no puede ser futura. El registro más reciente debe iniciar hoy o antes.');
            return;
        }

        if (!$this->fechaFin) {
            $conflicto = $this->detectarSolapamiento($this->fechaInicio, null, $this->editId);
            if ($conflicto) {
                $this->addError('fechaInicio',
                    "Sin fecha fin, solaparías con el rango que inicia el {$conflicto}. " .
                    "Establecé una fecha fin antes de esa fecha.");
            }
        }
    }

    public function updatedFechaFin(): void
    {
        $this->resetErrorBag('fechaFin');

        if (!$this->fechaFin) {
            if ($this->fechaInicio) {
                $conflicto = $this->detectarSolapamiento($this->fechaInicio, null, $this->editId);
                if ($conflicto) {
                    $this->addError('fechaFin',
                        "Sin fecha fin, solaparías con el rango que inicia el {$conflicto}. " .
                        "Establecé una fecha fin antes de esa fecha.");
                }
            }
            return;
        }

        if ($this->fechaInicio && $this->esElUltimoRegistro()) {
            $this->addError('fechaFin',
                'El registro más reciente no puede tener fecha fin. ' .
                'Para agregar un nuevo período, creá un nuevo registro y el sistema cerrará este automáticamente.');
            return;
        }

        $hueco = $this->detectarHuecoDespues($this->fechaFin, $this->editId);
        if ($hueco) {
            $this->addError('fechaFin',
                "Hay un vacío sin cobertura del {$hueco['desde']} al {$hueco['hasta']}. " .
                "Ajustá la fecha fin o la fecha de inicio del siguiente rango.");
            return;
        }

        $conflicto = $this->detectarSolapamiento($this->fechaInicio, $this->fechaFin, $this->editId);
        if ($conflicto) {
            $this->addError('fechaFin',
                "La fecha fin se solapa con el rango que inicia el {$conflicto}. " .
                "Reducí la fecha fin para no solapar.");
        }
    }

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

        $huecoAntes = $this->detectarHuecoAntes($this->fechaInicio, $this->editId);
        if ($huecoAntes) {
            $this->addError('fechaInicio',
                "Hay un vacío sin cobertura del {$huecoAntes['desde']} al {$huecoAntes['hasta']}. " .
                "Ajustá la fecha de inicio o la fecha fin del rango anterior.");
            return;
        }

        if ($this->fechaFin) {
            $huecoDespues = $this->detectarHuecoDespues($this->fechaFin, $this->editId);
            if ($huecoDespues) {
                $this->addError('fechaFin',
                    "Hay un vacío sin cobertura del {$huecoDespues['desde']} al {$huecoDespues['hasta']}. " .
                    "Ajustá la fecha fin o la fecha de inicio del siguiente rango.");
                return;
            }
        }

        $conflicto = $this->detectarSolapamiento($this->fechaInicio, $this->fechaFin ?: null, $this->editId);
        if ($conflicto) {
            $msg = $this->fechaFin
                ? "La fecha fin se solapa con el rango que inicia el {$conflicto}. Reducí la fecha fin."
                : "Sin fecha fin, solaparías con el rango que inicia el {$conflicto}. Establecé una fecha fin antes de esa fecha.";
            $this->addError('fechaFin', $msg);
            return;
        }

        if ($this->editId && $this->activo === 0 &&
            PesoIndicador::where('activo', true)->where('id', '!=', $this->editId)->count() === 0) {
            $this->addError('activo', 'Debe haber siempre una configuración activa.');
            return;
        }

        if ($this->esElUltimoRegistro()) {
            if ($this->fechaFin) {
                $this->addError('fechaFin',
                    'El registro más reciente no puede tener fecha fin. ' .
                    'Para agregar un nuevo período, creá un nuevo registro y el sistema cerrará este automáticamente.');
                return;
            }
            if (\Carbon\Carbon::parse($this->fechaInicio)->gt(\Carbon\Carbon::today())) {
                $this->addError('fechaInicio',
                    'La fecha de inicio no puede ser futura. El registro más reciente debe iniciar hoy o antes.');
                return;
            }
        }

        if ($this->quedaraSinVigente()) {
            $today = \Carbon\Carbon::today();
            $hoy   = $today->format('d/m/Y');
            if ($this->fechaFin && \Carbon\Carbon::parse($this->fechaFin)->lt($today)) {
                $this->addError('fechaFin',
                    "La fecha fin queda antes de hoy ({$hoy}) y dejaría el sistema sin configuración vigente. " .
                    'Extendé la fecha fin hasta hoy o más, o creá una nueva configuración que cubra la fecha actual.');
            } else {
                $this->addError('fechaInicio',
                    "Con estas fechas no quedaría ninguna configuración vigente para hoy ({$hoy}). " .
                    'Ajustá las fechas o asegurate de que otra configuración cubra la fecha actual.');
            }
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

        $cierreHasta = \Carbon\Carbon::parse($this->fechaInicio)->subDay()->toDateString();

        if ($this->editId) {
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

    public function backToList(): void
    {
        $this->resetForm();
        $this->mode = 'list';
    }

    private function esElUltimoRegistro(): bool
    {
        if (!$this->fechaInicio) return false;
        return !PesoIndicador::where('fecha_inicio', '>', $this->fechaInicio)
            ->when($this->editId, fn($q) => $q->where('id', '!=', $this->editId))
            ->exists();
    }

    private function quedaraSinVigente(): bool
    {
        if (!$this->editId && PesoIndicador::count() === 0) return false;

        $today = \Carbon\Carbon::today();

        $thisWillBeVigente = (bool) $this->activo
            && \Carbon\Carbon::parse($this->fechaInicio)->lte($today)
            && (!$this->fechaFin || \Carbon\Carbon::parse($this->fechaFin)->gte($today));

        if ($thisWillBeVigente) return false;

        $query = PesoIndicador::where('activo', true)
            ->where('fecha_inicio', '<=', $today)
            ->where(fn($q) => $q->whereNull('fecha_fin')->orWhere('fecha_fin', '>=', $today));

        if ($this->editId) {
            $query->where('id', '!=', $this->editId);
        }

        return !$query->exists();
    }

    private function detectarHuecoAntes(string $fechaInicio, ?int $excludeId = null): ?array
    {
        $inicio = \Carbon\Carbon::parse($fechaInicio);

        $tieneAbiertoAntes = PesoIndicador::whereNull('fecha_fin')
            ->where('fecha_inicio', '<', $inicio)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->exists();

        if ($tieneAbiertoAntes) return null;

        $anterior = PesoIndicador::whereNotNull('fecha_fin')
            ->where('fecha_fin', '<', $inicio)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->orderByDesc('fecha_fin')
            ->first();

        if (!$anterior) return null;

        $diaHuecoDesde = \Carbon\Carbon::parse($anterior->fecha_fin)->addDay();
        $diaHuecoHasta = $inicio->copy()->subDay();

        if ($diaHuecoDesde->gt($diaHuecoHasta)) return null;

        return ['desde' => $diaHuecoDesde->format('d/m/Y'), 'hasta' => $diaHuecoHasta->format('d/m/Y')];
    }

    private function detectarHuecoDespues(string $fechaFin, ?int $excludeId = null): ?array
    {
        $fin = \Carbon\Carbon::parse($fechaFin);

        $siguiente = PesoIndicador::where('fecha_inicio', '>', $fin)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->orderBy('fecha_inicio')
            ->first();

        if (!$siguiente) return null;

        $diaHuecoDesde = $fin->copy()->addDay();
        $diaHuecoHasta = \Carbon\Carbon::parse($siguiente->fecha_inicio)->subDay();

        if ($diaHuecoDesde->gt($diaHuecoHasta)) return null;

        return ['desde' => $diaHuecoDesde->format('d/m/Y'), 'hasta' => $diaHuecoHasta->format('d/m/Y')];
    }

    private function detectarSolapamiento(string $fechaInicio, ?string $fechaFin, ?int $excludeId = null): ?string
    {
        $inicio = \Carbon\Carbon::parse($fechaInicio);

        if (!$fechaFin) {
            $siguiente = PesoIndicador::where('fecha_inicio', '>', $inicio)
                ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
                ->orderBy('fecha_inicio')
                ->first();

            return $siguiente ? $siguiente->fecha_inicio->format('d/m/Y') : null;
        }

        $fin = \Carbon\Carbon::parse($fechaFin);
        $solapado = PesoIndicador::where('fecha_inicio', '>', $inicio)
            ->where('fecha_inicio', '<=', $fin)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->orderBy('fecha_inicio')
            ->first();

        return $solapado ? $solapado->fecha_inicio->format('d/m/Y') : null;
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

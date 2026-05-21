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

    public function updatedActivo(int $value): void
    {
        if ($value === 0 && $this->editId &&
            RangoCalificacion::where('activo', true)->where('id', '!=', $this->editId)->count() === 0) {
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

        if ($this->quedaraSinVigente()) {
            $today = \Carbon\Carbon::today();
            if ($this->fechaFin && \Carbon\Carbon::parse($this->fechaFin)->lt($today)) {
                $this->addError('fechaFin',
                    'La fecha fin queda en el pasado y dejaría el sistema sin configuración vigente para hoy. ' .
                    'Ajustá la fecha fin o creá una nueva configuración que cubra la fecha actual.');
            } else {
                $this->addError('fechaInicio',
                    'Con estas fechas no quedaría ninguna configuración vigente para hoy. ' .
                    'Ajustá las fechas o asegurate de que otra configuración cubra la fecha actual.');
            }
            return;
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

        $cierreHasta = \Carbon\Carbon::parse($this->fechaInicio)->subDay()->toDateString();

        if ($this->editId) {
            if ($this->activo === 0 && RangoCalificacion::where('activo', true)->where('id', '!=', $this->editId)->count() === 0) {
                $this->addError('activo', 'Debe haber siempre una configuración activa.');
                return;
            }

            RangoCalificacion::findOrFail($this->editId)->update($data);
            RangoCalificacion::where('id', '!=', $this->editId)
                ->whereNull('fecha_fin')
                ->where('fecha_inicio', '<', $this->fechaInicio)
                ->update(['fecha_fin' => $cierreHasta]);
        } else {
            RangoCalificacion::whereNull('fecha_fin')
                ->where('fecha_inicio', '<', $this->fechaInicio)
                ->update(['fecha_fin' => $cierreHasta]);
            RangoCalificacion::create($data);
        }

        session()->flash('success', 'Configuración guardada.');
        $this->backToList();
    }

    public function toggleActivo(int $id): void
    {
        $r = RangoCalificacion::findOrFail($id);
        $nuevoEstado = !$r->activo;

        if (!$nuevoEstado && RangoCalificacion::where('activo', true)->count() === 1) {
            session()->flash('error', 'Debe haber siempre una configuración activa.');
            return;
        }

        $r->update(['activo' => $nuevoEstado]);
    }

    public function delete(int $id): void
    {
        if (RangoCalificacion::count() === 1) {
            session()->flash('error', 'No se puede eliminar la única configuración registrada.');
            return;
        }

        RangoCalificacion::findOrFail($id)->delete();
        session()->flash('success', 'Configuración eliminada.');
    }

    public function backToList(): void
    {
        $this->resetForm();
        $this->mode = 'list';
    }

    private function quedaraSinVigente(): bool
    {
        if (!$this->editId && RangoCalificacion::count() === 0) return false;

        $today = \Carbon\Carbon::today();

        $thisWillBeVigente = (bool) $this->activo
            && \Carbon\Carbon::parse($this->fechaInicio)->lte($today)
            && (!$this->fechaFin || \Carbon\Carbon::parse($this->fechaFin)->gte($today));

        if ($thisWillBeVigente) return false;

        $query = RangoCalificacion::where('activo', true)
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

        $tieneAbiertoAntes = RangoCalificacion::whereNull('fecha_fin')
            ->where('fecha_inicio', '<', $inicio)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->exists();

        if ($tieneAbiertoAntes) return null;

        $anterior = RangoCalificacion::whereNotNull('fecha_fin')
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

        $siguiente = RangoCalificacion::where('fecha_inicio', '>', $fin)
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
            $siguiente = RangoCalificacion::where('fecha_inicio', '>', $inicio)
                ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
                ->orderBy('fecha_inicio')
                ->first();

            return $siguiente ? $siguiente->fecha_inicio->format('d/m/Y') : null;
        }

        $fin = \Carbon\Carbon::parse($fechaFin);
        $solapado = RangoCalificacion::where('fecha_inicio', '>', $inicio)
            ->where('fecha_inicio', '<=', $fin)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->orderBy('fecha_inicio')
            ->first();

        return $solapado ? $solapado->fecha_inicio->format('d/m/Y') : null;
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
        return view('livewire.admin.definiciones.rango-calificacion-manager', [
            'registros' => RangoCalificacion::orderByDesc('fecha_inicio')->get(),
        ]);
    }
}

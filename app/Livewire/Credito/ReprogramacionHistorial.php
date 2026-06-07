<?php

namespace App\Livewire\Credito;

use App\Models\Cuota;
use App\Models\PlanPago;
use App\Models\Reprogramacion;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ReprogramacionHistorial extends Component
{
    use WithPagination;

    public string $mode    = 'list';
    public string $search  = '';
    public string $filtro  = 'todos';
    public string $sortBy   = '';
    public string $sortDir  = 'asc';

    public function toggleSort(string $col): void
    {
        if ($this->sortBy === $col) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy  = $col;
            $this->sortDir = 'asc';
        }
        $this->resetPage();
    }

    public ?int $reprogramacionId = null;

    // Edición de cuotas
    public array $cuotasEditadas = [];

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingFiltro(): void { $this->resetPage(); }

    public function verDetalle(int $id): void
    {
        $this->reprogramacionId = $id;
        $this->cuotasEditadas   = [];
        $this->mode             = 'detalle';
    }

    public function volver(): void
    {
        if ($this->mode === 'editar') {
            $this->cuotasEditadas = [];
            $this->mode = 'detalle';
            return;
        }
        $this->reprogramacionId = null;
        $this->mode             = 'list';
    }

    public function editarPlan(): void
    {
        $rp   = Reprogramacion::with('planNuevo.cuotas')->find($this->reprogramacionId);
        $plan = $rp?->planNuevo;
        if (!$plan) return;

        $this->cuotasEditadas = $plan->cuotas
            ->where('numero', '>', 0)
            ->sortBy('numero')
            ->values()
            ->map(fn($c) => [
                'id'     => $c->id,
                'numero' => $c->numero,
                'monto'  => number_format((float) $c->monto, 2, '.', ''),
                'fecha'  => $c->fecha_vencimiento?->format('Y-m-d') ?? '',
                'pagado' => $c->estado === 'pagado',
            ])
            ->toArray();

        $this->mode = 'editar';
    }

    public function agregarCuotaEdicion(): void
    {
        $last      = !empty($this->cuotasEditadas) ? end($this->cuotasEditadas) : null;
        $nextFecha = $last
            ? Carbon::parse($last['fecha'])->addDays(30)->format('Y-m-d')
            : Carbon::today()->addDays(30)->format('Y-m-d');

        $this->cuotasEditadas[] = [
            'id'     => null,
            'numero' => count($this->cuotasEditadas) + 1,
            'monto'  => '0.00',
            'fecha'  => $nextFecha,
            'pagado' => false,
        ];
    }

    public function quitarCuotaEdicion(int $index): void
    {
        if ($this->cuotasEditadas[$index]['pagado'] ?? false) return;
        array_splice($this->cuotasEditadas, $index, 1);
        foreach ($this->cuotasEditadas as $i => &$c) {
            $c['numero'] = $i + 1;
        }
    }

    public function guardarEdicion(): void
    {
        $editables = array_filter($this->cuotasEditadas, fn($c) => !($c['pagado'] ?? false));

        $rules = [];
        foreach ($this->cuotasEditadas as $i => $c) {
            if ($c['pagado'] ?? false) continue;
            $rules["cuotasEditadas.{$i}.monto"] = 'required|numeric|min:0.01';
            $rules["cuotasEditadas.{$i}.fecha"]  = 'required|date';
        }
        $this->validate($rules, []);

        $rp   = Reprogramacion::with('planNuevo.cuotas')->find($this->reprogramacionId);
        $plan = $rp?->planNuevo;
        if (!$plan) return;

        $totalNuevo = round(collect($this->cuotasEditadas)
            ->filter(fn($c) => !($c['pagado'] ?? false))
            ->sum(fn($c) => (float) $c['monto']), 2);
        $saldoPend  = round((float) $plan->cuotas->where('estado', '!=', 'pagado')->where('numero', '>', 0)->sum('monto'), 2);

        if (abs($totalNuevo - $saldoPend) >= 0.01) {
            $this->addError('cuotasEditadas', "El total ingresado (Bs. {$totalNuevo}) debe ser exactamente igual al saldo pendiente (Bs. {$saldoPend}).");
            return;
        }

        DB::transaction(function () use ($plan) {
            $idsNuevos = collect($this->cuotasEditadas)
                ->filter(fn($c) => !($c['pagado'] ?? false) && $c['id'])
                ->pluck('id');

            // Eliminar cuotas no pagadas que fueron quitadas
            $plan->cuotas()
                ->where('estado', '!=', 'pagado')
                ->where('numero', '>', 0)
                ->whereNotIn('id', $idsNuevos)
                ->delete();

            foreach ($this->cuotasEditadas as $c) {
                if ($c['pagado'] ?? false) continue;

                if ($c['id']) {
                    Cuota::where('id', $c['id'])->update([
                        'numero'            => $c['numero'],
                        'monto'             => (float) $c['monto'],
                        'fecha_vencimiento' => $c['fecha'],
                    ]);
                } else {
                    Cuota::create([
                        'plan_pago_id'      => $plan->id,
                        'numero'            => $c['numero'],
                        'monto'             => (float) $c['monto'],
                        'estado'            => 'pendiente',
                        'fecha_vencimiento' => $c['fecha'],
                    ]);
                }
            }

            // Recalcular total del plan
            $nuevoTotal = $plan->cuotas()->where('numero', '>', 0)->sum('monto');
            $plan->update([
                'total_pagar'     => round((float) $nuevoTotal, 2),
                'saldo_financiar' => round((float) $nuevoTotal, 2),
                'cantidad_cuotas' => $plan->cuotas()->where('numero', '>', 0)->count(),
            ]);
        });

        // Recargar cuotas desde DB para reflejar el estado guardado
        $rp   = Reprogramacion::with('planNuevo.cuotas')->find($this->reprogramacionId);
        $plan = $rp?->planNuevo;
        $this->cuotasEditadas = $plan
            ? $plan->cuotas->where('numero', '>', 0)->sortBy('numero')->values()
                ->map(fn($c) => [
                    'id'     => $c->id,
                    'numero' => $c->numero,
                    'monto'  => number_format((float) $c->monto, 2, '.', ''),
                    'fecha'  => $c->fecha_vencimiento?->format('Y-m-d') ?? '',
                    'pagado' => $c->estado === 'pagado',
                ])->toArray()
            : [];

        $this->dispatch('rp-toast', message: 'Plan guardado correctamente.');
    }

    public function render()
    {
        $reprogramaciones = collect();

        if ($this->mode === 'list') {
            $reprogramaciones = Reprogramacion::with([
                'pedido.cliente.usuario',
                'planNuevo',
                'creadoPor',
            ])
            ->when(strlen(trim($this->search)) >= 2, fn($q) => $q
                ->whereHas('pedido', fn($p) => $p->where('numero', 'like', "%{$this->search}%"))
                ->orWhereHas('pedido.cliente', fn($c) => $c->where('ci', 'like', "%{$this->search}%"))
                ->orWhereHas('pedido.cliente.usuario', fn($u) => $u->where('name', 'like', "%{$this->search}%"))
                ->orWhere('numero', 'like', "%{$this->search}%")
            )
            ->when($this->filtro === 'activo',   fn($q) => $q->whereHas('planNuevo', fn($p) => $p->where('estado', 'activo')))
            ->when($this->filtro === 'inactivo', fn($q) => $q->whereHas('planNuevo', fn($p) => $p->where('estado', 'inactivo')))
            ->when($this->sortBy === 'fecha',  fn($q) => $q->orderBy('created_at', $this->sortDir))
            ->when($this->sortBy === 'saldo',  fn($q) => $q->orderBy('saldo_reprogramado', $this->sortDir))
            ->when($this->sortBy === 'numero', fn($q) => $q->orderBy('numero', $this->sortDir))
            ->when(!$this->sortBy,             fn($q) => $q->orderByDesc('created_at'))
            ->paginate(15);
        }

        $reprogramacionDetalle = null;
        if (in_array($this->mode, ['detalle', 'editar']) && $this->reprogramacionId) {
            $reprogramacionDetalle = Reprogramacion::with([
                'pedido.cliente.usuario',
                'pedido.vendedor.user',
                'planViejo.cuotas',
                'planNuevo.cuotas',
                'creadoPor',
            ])->find($this->reprogramacionId);
        }

        return view('livewire.credito.reprogramacion-historial', compact(
            'reprogramaciones', 'reprogramacionDetalle'
        ));
    }
}

<?php

namespace App\Livewire\Credito;

use App\Models\Cuota;
use App\Models\Pedido;
use App\Models\PlanPago;
use App\Models\Reprogramacion;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ReprogramacionManager extends Component
{
    use WithPagination;

    // Modos: home | nueva_buscar | nueva_preview | nueva_form
    //        hist_list | hist_pedido | hist_detalle
    public string $mode = 'home';

    // Búsqueda nueva reprog
    public string $search = '';

    // Pedido seleccionado (nueva + historial)
    public ?int $pedidoId        = null;
    public ?int $reprogramacionId = null;

    // New plan builder
    public array  $nuevasCuotas = [];
    public string $motivo       = '';

    // Historial search
    public string $searchHist = '';

    public function updatingSearch(): void    { $this->resetPage(); }
    public function updatingSearchHist(): void { $this->resetPage(); }

    // ── Navegación ──────────────────────────────────────────────────────────

    public function irNueva(): void    { $this->resetNueva(); $this->mode = 'nueva_buscar'; }
    public function irHistorial(): void { $this->resetHist();  $this->mode = 'hist_list'; }
    public function backHome(): void   { $this->resetAll();    $this->mode = 'home'; }

    // ── Nueva Reprogramación ─────────────────────────────────────────────────

    public function seleccionarPedido(int $id): void
    {
        $this->pedidoId = $id;
        $this->mode     = 'nueva_preview';
    }

    public function irForm(): void
    {
        $pedido = Pedido::with('planPago.cuotas')->findOrFail($this->pedidoId);
        $plan   = $pedido->planPago;
        if (!$plan) return;

        $pendientes = $plan->cuotas
            ->where('estado', '!=', 'pagado')
            ->where('numero', '>', 0)
            ->sortBy('numero')
            ->values();

        $balance = round((float) $pendientes->sum('monto'), 2);
        $count   = $pendientes->count();
        if ($count === 0) return;

        $montoPorCuota = $count > 1 ? round($balance / $count, 2) : $balance;
        $startDate     = Carbon::today()->addDays(30);

        $this->nuevasCuotas = [];
        for ($i = 0; $i < $count; $i++) {
            $monto = ($i === $count - 1)
                ? round($balance - ($montoPorCuota * ($count - 1)), 2)
                : $montoPorCuota;
            $this->nuevasCuotas[] = [
                'numero' => $i + 1,
                'monto'  => number_format($monto, 2, '.', ''),
                'fecha'  => $startDate->copy()->addDays($i * 30)->format('Y-m-d'),
            ];
        }
        $this->motivo = '';
        $this->mode   = 'nueva_form';
    }

    public function agregarCuota(): void
    {
        $last      = !empty($this->nuevasCuotas) ? end($this->nuevasCuotas) : null;
        $nextFecha = $last
            ? Carbon::parse($last['fecha'])->addDays(30)->format('Y-m-d')
            : Carbon::today()->addDays(30)->format('Y-m-d');

        $this->nuevasCuotas[] = [
            'numero' => count($this->nuevasCuotas) + 1,
            'monto'  => '0.00',
            'fecha'  => $nextFecha,
        ];
    }

    public function quitarCuota(int $index): void
    {
        array_splice($this->nuevasCuotas, $index, 1);
        foreach ($this->nuevasCuotas as $i => &$c) {
            $c['numero'] = $i + 1;
        }
    }

    public function confirmar(): void
    {
        $this->validate([
            'motivo'               => 'required|min:5',
            'nuevasCuotas'         => 'required|array|min:1',
            'nuevasCuotas.*.monto' => 'required|numeric|min:0.01',
            'nuevasCuotas.*.fecha' => 'required|date',
        ], [
            'motivo.required'               => 'Ingresá el motivo de la reprogramación.',
            'motivo.min'                    => 'El motivo debe tener al menos 5 caracteres.',
            'nuevasCuotas.*.monto.required' => 'Cada cuota necesita un monto.',
            'nuevasCuotas.*.monto.min'      => 'El monto debe ser mayor a 0.',
            'nuevasCuotas.*.fecha.required' => 'Cada cuota necesita una fecha.',
        ]);

        $pedido    = Pedido::with('planPago.cuotas')->where('estado', 'aprobado')->findOrFail($this->pedidoId);
        $planViejo = $pedido->planPago;
        if (!$planViejo) return;

        DB::transaction(function () use ($pedido, $planViejo) {
            $planViejo->update(['estado' => 'inactivo']);

            $version          = ($planViejo->version ?? 1) + 1;
            $totalNuevo       = round(collect($this->nuevasCuotas)->sum(fn($c) => (float) $c['monto']), 2);
            $cuotasPagadas    = $planViejo->cuotas->where('estado', 'pagado')->where('numero', '>', 0)->count();
            $saldoReprogramado= round((float) $planViejo->cuotas->where('estado', '!=', 'pagado')->where('numero', '>', 0)->sum('monto'), 2);

            $planNuevo = PlanPago::create([
                'pedido_id'      => $pedido->id,
                'version'        => $version,
                'estado'         => 'activo',
                'matriz_nombre'  => ($planViejo->matriz_nombre ?? 'Plan') . " · Reprog. v{$version}",
                'cantidad_cuotas'=> count($this->nuevasCuotas),
                'cuota_inicial'  => 0,
                'saldo_financiar'=> $totalNuevo,
                'incremento'     => 0,
                'monto_cuota'    => (float) ($this->nuevasCuotas[0]['monto'] ?? 0),
                'total_pagar'    => $totalNuevo,
                'notas'          => $this->motivo,
            ]);

            foreach ($this->nuevasCuotas as $c) {
                Cuota::create([
                    'plan_pago_id'     => $planNuevo->id,
                    'numero'           => $c['numero'],
                    'monto'            => (float) $c['monto'],
                    'estado'           => 'pendiente',
                    'fecha_vencimiento'=> $c['fecha'],
                ]);
            }

            Reprogramacion::create([
                'pedido_id'         => $pedido->id,
                'plan_viejo_id'     => $planViejo->id,
                'plan_nuevo_id'     => $planNuevo->id,
                'version_anterior'  => $planViejo->version ?? 1,
                'version_nueva'     => $version,
                'saldo_reprogramado'=> $saldoReprogramado,
                'cuotas_pagadas'    => $cuotasPagadas,
                'motivo'            => $this->motivo,
                'creado_por'        => auth()->id(),
            ]);

            $pedido->update(['total_pagar' => $totalNuevo]);
        });

        session()->flash('success', 'Reprogramación registrada correctamente.');
        $this->resetAll();
        $this->mode = 'home';
    }

    // ── Historial ────────────────────────────────────────────────────────────

    public function verHistorialPedido(int $id): void
    {
        $this->pedidoId = $id;
        $this->mode     = 'hist_pedido';
    }

    public function verDetalle(int $repId): void
    {
        $this->reprogramacionId = $repId;
        $this->mode             = 'hist_detalle';
    }

    // ── Reset helpers ────────────────────────────────────────────────────────

    private function resetNueva(): void
    {
        $this->search       = '';
        $this->pedidoId     = null;
        $this->nuevasCuotas = [];
        $this->motivo       = '';
    }

    private function resetHist(): void
    {
        $this->searchHist       = '';
        $this->pedidoId         = null;
        $this->reprogramacionId = null;
    }

    private function resetAll(): void
    {
        $this->resetNueva();
        $this->resetHist();
    }

    // ── Render ───────────────────────────────────────────────────────────────

    public function render()
    {
        // Nueva: resultados de búsqueda
        $resultados = collect();
        if (in_array($this->mode, ['nueva_buscar']) && strlen(trim($this->search)) >= 2) {
            $resultados = Pedido::with(['cliente.usuario', 'vendedor.user', 'planPago.cuotas'])
                ->where('estado', 'aprobado')
                ->whereHas('planPago', fn($q) => $q->where('estado', 'activo'))
                ->where(fn($q) => $q
                    ->whereHas('cliente', fn($c) => $c->where('ci', 'like', "%{$this->search}%"))
                    ->orWhereHas('cliente.usuario', fn($u) => $u->where('name', 'like', "%{$this->search}%"))
                    ->orWhere('numero', 'like', "%{$this->search}%")
                )
                ->orderByDesc('created_at')
                ->get();
        }

        // Pedido seleccionado para preview/form
        $pedidoDetalle = null;
        if ($this->pedidoId && in_array($this->mode, ['nueva_preview', 'nueva_form', 'hist_pedido'])) {
            $pedidoDetalle = Pedido::with(['cliente.usuario', 'vendedor.user', 'planPago.cuotas', 'planes.cuotas'])
                ->find($this->pedidoId);
        }

        // Historial: pedidos con reprogramaciones
        $pedidosHist = collect();
        if ($this->mode === 'hist_list') {
            $pedidosHist = Pedido::with(['cliente.usuario'])
                ->whereHas('planes', fn($q) => $q->where('version', '>', 1))
                ->when(strlen(trim($this->searchHist)) >= 2, fn($q) => $q
                    ->whereHas('cliente', fn($c) => $c->where('ci', 'like', "%{$this->searchHist}%"))
                    ->orWhereHas('cliente.usuario', fn($u) => $u->where('name', 'like', "%{$this->searchHist}%"))
                    ->orWhere('numero', 'like', "%{$this->searchHist}%")
                )
                ->orderByDesc('updated_at')
                ->paginate(15);
        }

        // Reprogramaciones de un pedido
        $reprogramaciones = collect();
        if ($this->mode === 'hist_pedido' && $this->pedidoId) {
            $reprogramaciones = Reprogramacion::with(['planViejo.cuotas', 'planNuevo.cuotas', 'creadoPor'])
                ->where('pedido_id', $this->pedidoId)
                ->orderByDesc('created_at')
                ->get();
        }

        // Detalle de una reprogramación
        $reprogramacionDetalle = null;
        if ($this->mode === 'hist_detalle' && $this->reprogramacionId) {
            $reprogramacionDetalle = Reprogramacion::with([
                'pedido.cliente.usuario',
                'planViejo.cuotas',
                'planNuevo.cuotas',
                'creadoPor',
            ])->find($this->reprogramacionId);
        }

        return view('livewire.credito.reprogramacion-manager', compact(
            'resultados', 'pedidoDetalle',
            'pedidosHist', 'reprogramaciones', 'reprogramacionDetalle'
        ));
    }
}

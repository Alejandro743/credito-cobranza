<?php

namespace App\Livewire\Credito;

use App\Models\Cuota;
use App\Models\Pedido;
use App\Models\PlanPago;
use App\Models\Reprogramacion;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ReprogramacionNueva extends Component
{
    // Modos: buscar | preview | form
    public string $mode   = 'buscar';
    public string $search = '';

    public ?int   $pedidoId       = null;
    public array  $nuevasCuotas   = [];

    // Editar plan directo desde la grilla
    public ?int   $editandoPlanPedidoId = null;
    public array  $cuotasEditadas       = [];
    public string $motivo         = '';

    // Configurador del plan
    public string $cantidadCuotas = '';
    public string $fechaPrimera   = '';

    public function seleccionarPedido(int $id): void
    {
        $this->pedidoId = $id;
        $this->mode     = 'preview';
    }

    public function irForm(): void
    {
        $pedido = Pedido::with('planPago.cuotas')->findOrFail($this->pedidoId);
        $plan   = $pedido->planPago;
        if (!$plan) return;

        $count = $plan->cuotas->where('estado', '!=', 'pagado')->where('numero', '>', 0)->count();
        if ($count === 0) return;

        $this->cantidadCuotas = (string) $count;
        $this->fechaPrimera   = Carbon::today()->addDays(30)->format('Y-m-d');
        $this->nuevasCuotas   = [];
        $this->motivo         = '';
        $this->mode           = 'form';

        $this->generarPlan();
    }

    public function generarPlan(): void
    {
        $count = (int) $this->cantidadCuotas;
        if ($count < 1 || !$this->fechaPrimera) return;

        $pedido  = Pedido::with('planPago.cuotas')->findOrFail($this->pedidoId);
        $plan    = $pedido->planPago;
        if (!$plan) return;

        $balance = round((float) $plan->cuotas->where('estado', '!=', 'pagado')->where('numero', '>', 0)->sum('monto'), 2);
        if ($balance <= 0) return;

        $montoPorCuota = $count > 1 ? round($balance / $count, 2) : $balance;
        $start         = Carbon::parse($this->fechaPrimera);

        $this->nuevasCuotas = [];
        for ($i = 0; $i < $count; $i++) {
            $monto = ($i === $count - 1)
                ? round($balance - ($montoPorCuota * ($count - 1)), 2)
                : $montoPorCuota;
            $this->nuevasCuotas[] = [
                'numero' => $i + 1,
                'monto'  => number_format($monto, 2, '.', ''),
                'fecha'  => $start->copy()->addDays($i * 30)->format('Y-m-d'),
            ];
        }
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

            $version           = ($planViejo->version ?? 1) + 1;
            $totalNuevo        = round(collect($this->nuevasCuotas)->sum(fn($c) => (float) $c['monto']), 2);
            $cuotasPagadas     = $planViejo->cuotas->where('estado', 'pagado')->where('numero', '>', 0)->count();
            $saldoReprogramado = round((float) $planViejo->cuotas->where('estado', '!=', 'pagado')->where('numero', '>', 0)->sum('monto'), 2);

            $planNuevo = PlanPago::create([
                'pedido_id'       => $pedido->id,
                'version'         => $version,
                'estado'          => 'activo',
                'matriz_nombre'   => ($planViejo->matriz_nombre ?? 'Plan') . " · Reprog. v{$version}",
                'cantidad_cuotas' => count($this->nuevasCuotas),
                'cuota_inicial'   => 0,
                'saldo_financiar' => $totalNuevo,
                'incremento'      => 0,
                'monto_cuota'     => (float) ($this->nuevasCuotas[0]['monto'] ?? 0),
                'total_pagar'     => $totalNuevo,
                'notas'           => $this->motivo,
            ]);

            foreach ($this->nuevasCuotas as $c) {
                Cuota::create([
                    'plan_pago_id'      => $planNuevo->id,
                    'numero'            => $c['numero'],
                    'monto'             => (float) $c['monto'],
                    'estado'            => 'pendiente',
                    'fecha_vencimiento' => $c['fecha'],
                ]);
            }

            Reprogramacion::create([
                'numero'             => Reprogramacion::generarNumero(),
                'pedido_id'          => $pedido->id,
                'plan_viejo_id'      => $planViejo->id,
                'plan_nuevo_id'      => $planNuevo->id,
                'version_anterior'   => $planViejo->version ?? 1,
                'version_nueva'      => $version,
                'saldo_reprogramado' => $saldoReprogramado,
                'cuotas_pagadas'     => $cuotasPagadas,
                'motivo'             => $this->motivo,
                'creado_por'         => auth()->id(),
            ]);

            $pedido->update(['total_pagar' => $totalNuevo]);
        });

        session()->flash('success', 'Reprogramación registrada correctamente.');
        $this->reset(['pedidoId', 'nuevasCuotas', 'motivo', 'search', 'cantidadCuotas', 'fechaPrimera']);
        $this->mode = 'buscar';
    }

    public function volver(): void
    {
        if ($this->mode === 'editar_plan') { $this->cerrarEditarPlan(); return; }
        if ($this->mode === 'form')        { $this->mode = 'preview'; return; }
        if ($this->mode === 'preview')     { $this->pedidoId = null; $this->mode = 'buscar'; return; }
    }

    // ── Editar plan directo ──────────────────────────────────────────────────

    public function abrirEditarPlan(int $pedidoId): void
    {
        $pedido = Pedido::with('planPago.cuotas')->find($pedidoId);
        $plan   = $pedido?->planPago;
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

        $this->editandoPlanPedidoId = $pedidoId;
        $this->mode = 'editar_plan';
    }

    public function cerrarEditarPlan(): void
    {
        $this->editandoPlanPedidoId = null;
        $this->cuotasEditadas       = [];
        $this->mode                 = 'buscar';
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

    public function guardarEdicionPlan(): void
    {
        $rules = [];
        foreach ($this->cuotasEditadas as $i => $c) {
            if ($c['pagado'] ?? false) continue;
            $rules["cuotasEditadas.{$i}.monto"] = 'required|numeric|min:0.01';
            $rules["cuotasEditadas.{$i}.fecha"]  = 'required|date';
        }
        $this->validate($rules);

        $pedido = Pedido::with('planPago.cuotas')->find($this->editandoPlanPedidoId);
        $plan   = $pedido?->planPago;
        if (!$plan) return;

        DB::transaction(function () use ($plan) {
            $idsNuevos = collect($this->cuotasEditadas)
                ->filter(fn($c) => !($c['pagado'] ?? false) && $c['id'])
                ->pluck('id');

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

            $nuevoTotal = $plan->cuotas()->where('numero', '>', 0)->sum('monto');
            $plan->update([
                'total_pagar'     => round((float) $nuevoTotal, 2),
                'saldo_financiar' => round((float) $nuevoTotal, 2),
                'cantidad_cuotas' => $plan->cuotas()->where('numero', '>', 0)->count(),
            ]);
        });

        session()->flash('success', 'Plan de pagos actualizado correctamente.');
        $this->cerrarEditarPlan();
    }

    public function render()
    {
        $query = Pedido::with(['cliente.usuario', 'vendedor.user', 'planPago.cuotas'])
            ->where('estado', 'aprobado')
            ->whereHas('planPago', fn($q) => $q->where('estado', 'activo'))
            ->whereHas('planPago.cuotas', fn($q) => $q->where('estado', '!=', 'pagado')->where('numero', '>', 0));

        if (strlen(trim($this->search)) >= 2) {
            $query->where(fn($q) => $q
                ->whereHas('cliente', fn($c) => $c->where('ci', 'like', "%{$this->search}%"))
                ->orWhereHas('cliente.usuario', fn($u) => $u->where('name', 'like', "%{$this->search}%"))
                ->orWhere('numero', 'like', "%{$this->search}%")
            );
        }

        $resultados = $query->orderByDesc('created_at')->get();

        $pedidoDetalle = null;
        if ($this->pedidoId && in_array($this->mode, ['preview', 'form'])) {
            $pedidoDetalle = Pedido::with(['cliente.usuario', 'vendedor.user', 'planPago.cuotas'])
                ->find($this->pedidoId);
        }

        // Datos para el modal editar plan
        $planEditando  = null;
        $pendActual    = 0;
        $totalEditado  = 0;
        $difEditado    = 0;
        if ($this->editandoPlanPedidoId) {
            $pedEdit      = Pedido::with(['cliente', 'vendedor.user', 'planPago.cuotas'])->find($this->editandoPlanPedidoId);
            $planEditando = $pedEdit?->planPago;
            $pendActual   = round((float) ($planEditando?->cuotas->where('numero','>',0)->where('estado','!=','pagado')->sum('monto') ?? 0), 2);
            $totalEditado = round(collect($this->cuotasEditadas)->filter(fn($c) => !($c['pagado'] ?? false))->sum(fn($c) => (float)$c['monto']), 2);
            $difEditado   = round($totalEditado - $pendActual, 2);
        }

        $cuotasEditadas = $this->cuotasEditadas;

        return view('livewire.credito.reprogramacion-nueva', compact('resultados', 'pedidoDetalle', 'planEditando', 'pendActual', 'totalEditado', 'difEditado', 'cuotasEditadas'));
    }
}

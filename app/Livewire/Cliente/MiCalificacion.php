<?php

namespace App\Livewire\Cliente;

use App\Models\PesoIndicador;
use App\Models\RangoCalificacion;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Livewire\Component;

class MiCalificacion extends Component
{
    public ?int $pedidoExpandido = null;

    public function togglePedido(int $pedidoId): void
    {
        $this->pedidoExpandido = $this->pedidoExpandido === $pedidoId ? null : $pedidoId;
    }

    public function render()
    {
        $hoy    = Carbon::today();
        $pesos  = PesoIndicador::vigente($hoy)  ?? PesoIndicador::porDefecto();
        $rangos = RangoCalificacion::vigente($hoy) ?? RangoCalificacion::porDefecto();

        $cliente = auth()->user()->cliente;

        if (!$cliente) {
            return view('livewire.cliente.mi-calificacion', [
                'sinCliente'   => true,
                'indicadores'  => null,
                'pedidos'      => collect(),
                'calificacion' => null,
                'calBadge'     => null,
                'nombre'       => auth()->user()->name,
            ]);
        }

        $cliente->loadMissing(['pedidos' => function ($q) {
            $q->where('estado', 'aprobado')
              ->with(['planPago.cuotas', 'planes', 'vendedor']);
        }]);

        $pedidosConPlan = $cliente->pedidos->filter(fn($p) => $p->planPago !== null);

        // ── Indicadores globales del cliente ─────────────────────────────────
        $totalPedidos = $pedidosConPlan->count();
        $todasCuotas  = $pedidosConPlan->flatMap(fn($p) => $p->planPago->cuotas->where('numero', '>', 0));
        $cerradas     = $todasCuotas->filter(fn($c) => $c->fecha_vencimiento && $c->fecha_vencimiento->lte($hoy));

        $nCerradas   = $cerradas->count();
        $nATiempo    = $cerradas->filter(fn($c) => $c->estado === 'pagado' && $c->fecha_pago && $c->fecha_pago->lte($c->fecha_vencimiento))->count();
        $puntualidad = $nCerradas > 0 ? round(($nATiempo / $nCerradas) * 100, 1) : 100.0;

        $pedidosEnMora = $pedidosConPlan->filter(function ($p) use ($hoy) {
            return $p->planPago->cuotas->where('numero', '>', 0)
                ->filter(fn($c) => $c->fecha_vencimiento && $c->fecha_vencimiento->lte($hoy) && $c->estado !== 'pagado')
                ->isNotEmpty();
        })->count();
        $mora = $totalPedidos > 0 ? round(($pedidosEnMora / $totalPedidos) * 100, 1) : 0.0;

        $saldoVencido   = $cerradas->where('estado', '!=', 'pagado')->sum('monto');
        $cuotasAbiertas = $todasCuotas->filter(fn($c) => !$c->fecha_vencimiento || $c->fecha_vencimiento->gt($hoy));
        $saldoAbierto   = $cuotasAbiertas->where('estado', '!=', 'pagado')->sum('monto');
        $saldoPendiente = $saldoVencido + $saldoAbierto;
        $riesgo         = $saldoPendiente > 0 ? round(($saldoVencido / $saldoPendiente) * 100, 1) : 0.0;

        $totalVencidoNoPagado = $cerradas->where('estado', '!=', 'pagado')->sum('monto');
        if ($totalVencidoNoPagado > 0) {
            $montoRecuperado = $cerradas->where('estado', 'pagado')
                ->filter(fn($c) => $c->fecha_pago && $c->fecha_pago->gt($c->fecha_vencimiento))
                ->sum('monto');
            $recuperacion = min(100, round(($montoRecuperado / $totalVencidoNoPagado) * 100, 1));
        } else {
            $recuperacion = 100.0;
        }

        $pedidosReprog = $pedidosConPlan->filter(fn($p) => $p->planes->count() > 1)->count();
        $reprog        = $totalPedidos > 0 ? round(($pedidosReprog / $totalPedidos) * 100, 1) : 0.0;

        $puntaje = $totalPedidos > 0 ? round(
            ($puntualidad    * $pesos->peso_puntualidad    / 100) +
            ((100 - $mora)   * $pesos->peso_mora           / 100) +
            ((100 - $riesgo) * $pesos->peso_riesgo         / 100) +
            ($recuperacion   * $pesos->peso_recuperacion   / 100) +
            ((100 - $reprog) * $pesos->peso_reprogramacion / 100),
            1
        ) : null;

        $calificacion = $puntaje !== null ? $rangos->calificar($puntaje) : null;

        $indicadores = $puntaje !== null ? [
            'puntaje'      => $puntaje,
            'puntualidad'  => $puntualidad,
            'mora'         => $mora,
            'riesgo'       => $riesgo,
            'recuperacion' => $recuperacion,
            'reprog'       => $reprog,
            'calificacion' => $calificacion,
        ] : null;

        $calBadge = match($calificacion) {
            'A'         => ['bg' => '#DCFCE7', 'cl' => '#15803D'],
            'B'         => ['bg' => '#ECFEFF', 'cl' => '#0e7490'],
            'C'         => ['bg' => '#FEF3C7', 'cl' => '#854F0B'],
            'D'         => ['bg' => '#FFF7ED', 'cl' => '#C2410C'],
            'BLOQUEADO' => ['bg' => '#FEF2F2', 'cl' => '#B91C1C'],
            default     => ['bg' => '#f3f4f6', 'cl' => '#6b7280'],
        };

        // ── Detalle por pedido con cuotas ────────────────────────────────────
        $pedidos = $pedidosConPlan->map(function ($p) use ($hoy) {
            $cuotasRegulares = $p->planPago->cuotas->where('numero', '>', 0);
            $cerradas        = $cuotasRegulares->filter(fn($c) => $c->fecha_vencimiento && $c->fecha_vencimiento->lte($hoy));
            $nCerradas       = $cerradas->count();
            $nATiempo        = $cerradas->filter(fn($c) => $c->estado === 'pagado' && $c->fecha_pago && $c->fecha_pago->lte($c->fecha_vencimiento))->count();
            $puntualidad     = $nCerradas > 0 ? round(($nATiempo / $nCerradas) * 100, 1) : 100.0;
            $enMora          = $cerradas->filter(fn($c) => $c->estado !== 'pagado')->isNotEmpty();

            $saldoVencido    = $cerradas->where('estado', '!=', 'pagado')->sum('monto');
            $cuotasAbiertas  = $cuotasRegulares->filter(fn($c) => !$c->fecha_vencimiento || $c->fecha_vencimiento->gt($hoy));
            $saldoAbierto    = $cuotasAbiertas->where('estado', '!=', 'pagado')->sum('monto');
            $saldoPendiente  = $saldoVencido + $saldoAbierto;
            $riesgo          = $saldoPendiente > 0 ? round(($saldoVencido / $saldoPendiente) * 100, 1) : 0.0;

            // Todas las cuotas para mostrar (incluye inicial)
            $todasCuotas = $p->planPago->cuotas->map(fn($c) => [
                'numero'            => $c->numero,
                'monto'             => (float) $c->monto,
                'fecha_vencimiento' => $c->fecha_vencimiento,
                'fecha_pago'        => $c->fecha_pago,
                'estado'            => $c->estadoFinanciero,
                'badge'             => $c->estadoFinancieroBadge,
            ]);

            $pagadas  = $cuotasRegulares->where('estado', 'pagado')->count();
            $totalReg = $cuotasRegulares->count();

            return [
                'id'           => $p->id,
                'numero'       => $p->numero,
                'vendedor'     => ucwords(mb_strtolower($p->vendedor?->nombre_completo ?? '—')),
                'total_cuotas' => $totalReg,
                'pagadas'      => $pagadas,
                'cerradas'     => $nCerradas,
                'al_dia'       => $nATiempo,
                'puntualidad'  => $puntualidad,
                'en_mora'      => $enMora,
                'riesgo'       => $riesgo,
                'reprogramado' => $p->planes->count() > 1,
                'monto'        => (float) $p->total_pagar,
                'estado_badge' => $p->planPago->estadoFinancieroBadge,
                'cuotas'       => $todasCuotas,
            ];
        })->values();

        return view('livewire.cliente.mi-calificacion', compact(
            'indicadores', 'pedidos', 'calificacion', 'calBadge', 'pesos'
        ));
    }
}

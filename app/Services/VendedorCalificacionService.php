<?php

namespace App\Services;

use App\Models\Pedido;
use App\Models\PesoIndicador;
use App\Models\RangoCalificacion;
use App\Models\Vendedor;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class VendedorCalificacionService
{
    /**
     * Calcula el puntaje y calificación (A/B/C/D/BLOQUEADO) de un vendedor/EIE
     * en base a su historial de pedidos. Devuelve null si no tiene pedidos
     * con plan de pago (nada que calificar todavía).
     */
    public function calcularParaVendedor(Vendedor $vendedor, PesoIndicador $pesos, RangoCalificacion $rangos): ?array
    {
        $hoy = Carbon::today();

        $vendedor->loadMissing(['pedidos' => function ($q) {
            $q->paraIndicadores()->with(['planReciente.cuotas', 'planes']);
        }]);

        $pedidos = $vendedor->pedidos->filter(fn ($p) => $p->planReciente !== null);

        if ($pedidos->isEmpty()) {
            return null;
        }

        $totalPedidos = $pedidos->count();

        $todasCuotas = $pedidos->flatMap(fn ($p) => $p->planReciente->cuotas->where('numero', '>', 0));
        $cerradas    = $todasCuotas->filter(fn ($c) => $c->fecha_vencimiento && $c->fecha_vencimiento->lte($hoy));

        // 1. PUNTUALIDAD
        $nCerradas   = $cerradas->count();
        $nATiempo    = $cerradas->filter(fn ($c) => $c->estado === 'pagado' && $c->fecha_pago && $c->fecha_pago->lte($c->fecha_vencimiento))->count();
        $puntualidad = $nCerradas > 0 ? round(($nATiempo / $nCerradas) * 100, 1) : 100.0;

        // 2. MORA GENERADA
        $pedidosEnMora = $pedidos->filter(function ($p) use ($hoy) {
            return $p->planReciente->cuotas
                ->where('numero', '>', 0)
                ->filter(fn ($c) => $c->fecha_vencimiento && $c->fecha_vencimiento->lte($hoy) && $c->estado !== 'pagado')
                ->isNotEmpty();
        })->count();
        $mora = $totalPedidos > 0 ? round(($pedidosEnMora / $totalPedidos) * 100, 1) : 0.0;

        // 3. CARTERA EN RIESGO
        $saldoVencido   = $cerradas->where('estado', '!=', 'pagado')->sum('monto');
        $cuotasAbiertas = $todasCuotas->filter(fn ($c) => !$c->fecha_vencimiento || $c->fecha_vencimiento->gt($hoy));
        $saldoAbierto   = $cuotasAbiertas->where('estado', '!=', 'pagado')->sum('monto');
        $saldoPendiente = $saldoVencido + $saldoAbierto;
        $riesgo         = $saldoPendiente > 0 ? round(($saldoVencido / $saldoPendiente) * 100, 1) : 0.0;

        // 4. RECUPERACIÓN
        $totalVencidoNoPagado = $cerradas->where('estado', '!=', 'pagado')->sum('monto');
        if ($totalVencidoNoPagado > 0) {
            $montoRecuperado = $cerradas->where('estado', 'pagado')
                ->filter(fn ($c) => $c->fecha_pago && $c->fecha_pago->gt($c->fecha_vencimiento))
                ->sum('monto');
            $recuperacion = min(100, round(($montoRecuperado / $totalVencidoNoPagado) * 100, 1));
        } else {
            $recuperacion = 100.0;
        }

        // 5. REPROGRAMACIONES
        $pedidosReprog = $pedidos->filter(fn ($p) => $p->planes->count() > 1)->count();
        $reprog        = $totalPedidos > 0 ? round(($pedidosReprog / $totalPedidos) * 100, 1) : 0.0;

        $puntaje = round(
            ($puntualidad    * $pesos->peso_puntualidad    / 100) +
            ((100 - $mora)   * $pesos->peso_mora           / 100) +
            ((100 - $riesgo) * $pesos->peso_riesgo         / 100) +
            ($recuperacion   * $pesos->peso_recuperacion   / 100) +
            ((100 - $reprog) * $pesos->peso_reprogramacion / 100),
            1
        );

        return [
            'id'            => $vendedor->id,
            'nombre'        => $vendedor->nombre_completo,
            'total_pedidos' => $totalPedidos,
            'puntualidad'   => $puntualidad,
            'mora'          => $mora,
            'riesgo'        => $riesgo,
            'recuperacion'  => $recuperacion,
            'reprog'        => $reprog,
            'puntaje'       => $puntaje,
            'calificacion'  => $rangos->calificar($puntaje),
        ];
    }

    /** Resumen por pedido (puntualidad, mora, riesgo, reprogramado, monto) de un vendedor. */
    public function calcularDetallePedidos(int $vendedorId): Collection
    {
        $hoy = Carbon::today();

        return Pedido::where('vendedor_id', $vendedorId)
            ->paraIndicadores()
            ->with(['planReciente.cuotas', 'planes', 'cliente.usuario'])
            ->get()
            ->filter(fn ($p) => $p->planReciente !== null)
            ->map(function (Pedido $p) use ($hoy) {
                $cuotas   = $p->planReciente->cuotas->where('numero', '>', 0);
                $cerradas = $cuotas->filter(fn ($c) => $c->fecha_vencimiento && $c->fecha_vencimiento->lte($hoy));

                $nCerradas = $cerradas->count();
                $nATiempo  = $cerradas->filter(fn ($c) =>
                    $c->estado === 'pagado' && $c->fecha_pago && $c->fecha_pago->lte($c->fecha_vencimiento)
                )->count();
                $puntualidad = $nCerradas > 0 ? round(($nATiempo / $nCerradas) * 100, 1) : 100.0;

                $enMora = $cerradas->filter(fn ($c) => $c->estado !== 'pagado')->isNotEmpty();

                $saldoVencido   = $cerradas->where('estado', '!=', 'pagado')->sum('monto');
                $cuotasAbiertas = $cuotas->filter(fn ($c) => !$c->fecha_vencimiento || $c->fecha_vencimiento->gt($hoy));
                $saldoAbierto   = $cuotasAbiertas->where('estado', '!=', 'pagado')->sum('monto');
                $saldoPendiente = $saldoVencido + $saldoAbierto;
                $riesgo         = $saldoPendiente > 0 ? round(($saldoVencido / $saldoPendiente) * 100, 1) : 0.0;

                return [
                    'numero'       => $p->numero,
                    'cliente'      => $p->cliente?->nombre_completo ?? '—',
                    'total_cuotas' => $cuotas->count(),
                    'cerradas'     => $nCerradas,
                    'al_dia'       => $nATiempo,
                    'puntualidad'  => $puntualidad,
                    'en_mora'      => $enMora,
                    'riesgo'       => $riesgo,
                    'reprogramado' => $p->planes->count() > 1,
                    'monto'        => (float) $p->total_pagar,
                ];
            })
            ->values();
    }
}

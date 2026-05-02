<?php

namespace App\Livewire\Credito\Indicadores;

use App\Models\Vendedor;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Livewire\Component;

class CalificacionVendedor extends Component
{
    public string $filtroCalificacion = 'todos';
    public string $ordenar            = 'puntaje_desc';
    public ?int   $detalleId          = null;

    public function toggleDetalle(int $vendedorId): void
    {
        $this->detalleId = $this->detalleId === $vendedorId ? null : $vendedorId;
    }

    private function calcularVendedores(): Collection
    {
        $hoy      = Carbon::today();
        $vendedores = Vendedor::where('activo', true)
            ->with(['pedidos' => function ($q) {
                $q->where('estado', 'aprobado')
                  ->with(['planPago.cuotas', 'planes']);
            }])
            ->get();

        return $vendedores->map(function (Vendedor $v) use ($hoy) {
            $pedidos = $v->pedidos->filter(fn($p) => $p->planPago !== null);

            if ($pedidos->isEmpty()) return null;

            $totalPedidos = $pedidos->count();

            // Todas las cuotas (numero > 0) de todos los planes activos
            $todasCuotas = $pedidos->flatMap(fn($p) => $p->planPago->cuotas->where('numero', '>', 0));

            // Cuotas cerradas = fecha_vencimiento <= hoy
            $cerradas = $todasCuotas->filter(fn($c) => $c->fecha_vencimiento && $c->fecha_vencimiento->lte($hoy));

            // 1. PUNTUALIDAD (25%)
            $nCerradas   = $cerradas->count();
            $nATiempo    = $cerradas->filter(fn($c) => $c->estado === 'pagado' && $c->fecha_pago && $c->fecha_pago->lte($c->fecha_vencimiento))->count();
            $puntualidad = $nCerradas > 0 ? round(($nATiempo / $nCerradas) * 100, 1) : 100.0;

            // 2. MORA GENERADA (25%)
            $pedidosEnMora = $pedidos->filter(function ($p) use ($hoy) {
                return $p->planPago->cuotas
                    ->where('numero', '>', 0)
                    ->filter(fn($c) => $c->fecha_vencimiento && $c->fecha_vencimiento->lte($hoy) && $c->estado !== 'pagado')
                    ->isNotEmpty();
            })->count();
            $mora = $totalPedidos > 0 ? round(($pedidosEnMora / $totalPedidos) * 100, 1) : 0.0;

            // 3. CARTERA EN RIESGO (20%)
            $saldoVencido   = $cerradas->where('estado', '!=', 'pagado')->sum('monto');
            $cuotasAbiertas = $todasCuotas->filter(fn($c) => !$c->fecha_vencimiento || $c->fecha_vencimiento->gt($hoy));
            $saldoAbierto   = $cuotasAbiertas->where('estado', '!=', 'pagado')->sum('monto');
            $saldoPendiente = $saldoVencido + $saldoAbierto;
            $riesgo         = $saldoPendiente > 0 ? round(($saldoVencido / $saldoPendiente) * 100, 1) : 0.0;

            // 4. RECUPERACIÓN (20%)
            $totalVencidoNoPagado = $cerradas->where('estado', '!=', 'pagado')->sum('monto');
            if ($totalVencidoNoPagado > 0) {
                $montoRecuperado = $cerradas->where('estado', 'pagado')
                    ->filter(fn($c) => $c->fecha_pago && $c->fecha_pago->gt($c->fecha_vencimiento))
                    ->sum('monto');
                $recuperacion = round(($montoRecuperado / $totalVencidoNoPagado) * 100, 1);
                $recuperacion = min(100, $recuperacion);
            } else {
                $recuperacion = 100.0;
            }

            // 5. REPROGRAMACIONES (10%)
            $pedidosReprog = $pedidos->filter(fn($p) => $p->planes->count() > 1)->count();
            $reprog        = $totalPedidos > 0 ? round(($pedidosReprog / $totalPedidos) * 100, 1) : 0.0;

            // PUNTAJE FINAL
            $puntaje = round(
                ($puntualidad * 0.25) +
                ((100 - $mora)    * 0.25) +
                ((100 - $riesgo)  * 0.20) +
                ($recuperacion    * 0.20) +
                ((100 - $reprog)  * 0.10),
                1
            );

            $calificacion = match(true) {
                $puntaje >= 85 => 'A',
                $puntaje >= 70 => 'B',
                $puntaje >= 50 => 'C',
                $puntaje >= 30 => 'D',
                default        => 'BLOQUEADO',
            };

            return [
                'id'            => $v->id,
                'nombre'        => $v->nombre_completo,
                'total_pedidos' => $totalPedidos,
                'puntualidad'   => $puntualidad,
                'mora'          => $mora,
                'riesgo'        => $riesgo,
                'recuperacion'  => $recuperacion,
                'reprog'        => $reprog,
                'puntaje'       => $puntaje,
                'calificacion'  => $calificacion,
            ];
        })->filter()->values();
    }

    public function render()
    {
        $vendedores = $this->calcularVendedores();

        if ($this->filtroCalificacion !== 'todos') {
            $vendedores = $vendedores->filter(fn($v) => $v['calificacion'] === $this->filtroCalificacion)->values();
        }

        $vendedores = match($this->ordenar) {
            'puntaje_asc'  => $vendedores->sortBy('puntaje')->values(),
            'nombre'       => $vendedores->sortBy('nombre')->values(),
            default        => $vendedores->sortByDesc('puntaje')->values(),
        };

        $kpis = [
            'total'    => $vendedores->count(),
            'ab'       => $vendedores->whereIn('calificacion', ['A', 'B'])->count(),
            'c'        => $vendedores->where('calificacion', 'C')->count(),
            'db'       => $vendedores->whereIn('calificacion', ['D', 'BLOQUEADO'])->count(),
        ];

        return view('livewire.credito.indicadores.calificacion-vendedor', compact('vendedores', 'kpis'));
    }
}

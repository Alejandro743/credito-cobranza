<?php

namespace App\Livewire\Credito\Indicadores;

use App\Models\PesoIndicador;
use App\Models\RangoCalificacion;
use App\Models\Vendedor;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Livewire\Component;

class CalificacionVendedor extends Component
{
    public string $filtroCalificacion = 'todos';
    public string $ordenar            = 'puntaje_desc';
    public string $buscarVendedor     = '';
    public ?int   $detalleId          = null;

    public function toggleDetalle(int $vendedorId): void
    {
        $this->detalleId = $this->detalleId === $vendedorId ? null : $vendedorId;
    }

    private function calcularVendedores(): Collection
    {
        $hoy    = Carbon::today();
        $pesos  = PesoIndicador::vigente($hoy) ?? PesoIndicador::porDefecto();
        $rangos = RangoCalificacion::vigente($hoy) ?? RangoCalificacion::porDefecto();
        $vendedores = Vendedor::where('activo', true)
            ->with(['pedidos' => function ($q) {
                $q->where('estado', 'aprobado')
                  ->with(['planPago.cuotas', 'planes']);
            }])
            ->get();

        return $vendedores->map(function (Vendedor $v) use ($hoy, $pesos, $rangos) {
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

            // PUNTAJE FINAL usando pesos configurados
            $puntaje = round(
                ($puntualidad    * $pesos->peso_puntualidad    / 100) +
                ((100 - $mora)   * $pesos->peso_mora           / 100) +
                ((100 - $riesgo) * $pesos->peso_riesgo         / 100) +
                ($recuperacion   * $pesos->peso_recuperacion   / 100) +
                ((100 - $reprog) * $pesos->peso_reprogramacion / 100),
                1
            );

            $calificacion = $rangos->calificar($puntaje);

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

        if (strlen(trim($this->buscarVendedor)) >= 2) {
            $q = mb_strtolower(trim($this->buscarVendedor));
            $vendedores = $vendedores->filter(fn($v) => str_contains(mb_strtolower($v['nombre']), $q))->values();
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

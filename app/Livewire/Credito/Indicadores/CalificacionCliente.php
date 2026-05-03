<?php

namespace App\Livewire\Credito\Indicadores;

use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\PesoIndicador;
use App\Models\RangoCalificacion;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Livewire\Component;

class CalificacionCliente extends Component
{
    public string $mode               = 'list';
    public string $filtroCalificacion = 'todos';
    public string $ordenar            = 'puntaje_desc';
    public string $buscarCliente      = '';
    public ?int   $detalleId          = null;

    public function verDetalle(int $id): void
    {
        $this->detalleId = $id;
        $this->mode      = 'detalle';
    }

    public function backToList(): void
    {
        $this->detalleId = null;
        $this->mode      = 'list';
    }

    private function calcularClientes(PesoIndicador $pesos, RangoCalificacion $rangos): Collection
    {
        $hoy = Carbon::today();

        $clientes = Cliente::where('active', true)
            ->with(['pedidos' => function ($q) {
                $q->where('estado', 'aprobado')
                  ->with(['planPago.cuotas', 'planes']);
            }])
            ->get();

        return $clientes->map(function (Cliente $c) use ($hoy, $pesos, $rangos) {
            $pedidos = $c->pedidos->filter(fn($p) => $p->planPago !== null);

            if ($pedidos->isEmpty()) return null;

            $totalPedidos = $pedidos->count();

            $todasCuotas = $pedidos->flatMap(fn($p) => $p->planPago->cuotas->where('numero', '>', 0));
            $cerradas    = $todasCuotas->filter(fn($c) => $c->fecha_vencimiento && $c->fecha_vencimiento->lte($hoy));

            // 1. PUNTUALIDAD
            $nCerradas   = $cerradas->count();
            $nATiempo    = $cerradas->filter(fn($c) => $c->estado === 'pagado' && $c->fecha_pago && $c->fecha_pago->lte($c->fecha_vencimiento))->count();
            $puntualidad = $nCerradas > 0 ? round(($nATiempo / $nCerradas) * 100, 1) : 100.0;

            // 2. MORA GENERADA
            $pedidosEnMora = $pedidos->filter(function ($p) use ($hoy) {
                return $p->planPago->cuotas
                    ->where('numero', '>', 0)
                    ->filter(fn($c) => $c->fecha_vencimiento && $c->fecha_vencimiento->lte($hoy) && $c->estado !== 'pagado')
                    ->isNotEmpty();
            })->count();
            $mora = $totalPedidos > 0 ? round(($pedidosEnMora / $totalPedidos) * 100, 1) : 0.0;

            // 3. CARTERA EN RIESGO
            $saldoVencido   = $cerradas->where('estado', '!=', 'pagado')->sum('monto');
            $cuotasAbiertas = $todasCuotas->filter(fn($c) => !$c->fecha_vencimiento || $c->fecha_vencimiento->gt($hoy));
            $saldoAbierto   = $cuotasAbiertas->where('estado', '!=', 'pagado')->sum('monto');
            $saldoPendiente = $saldoVencido + $saldoAbierto;
            $riesgo         = $saldoPendiente > 0 ? round(($saldoVencido / $saldoPendiente) * 100, 1) : 0.0;

            // 4. RECUPERACIÓN
            $totalVencidoNoPagado = $cerradas->where('estado', '!=', 'pagado')->sum('monto');
            if ($totalVencidoNoPagado > 0) {
                $montoRecuperado = $cerradas->where('estado', 'pagado')
                    ->filter(fn($c) => $c->fecha_pago && $c->fecha_pago->gt($c->fecha_vencimiento))
                    ->sum('monto');
                $recuperacion = min(100, round(($montoRecuperado / $totalVencidoNoPagado) * 100, 1));
            } else {
                $recuperacion = 100.0;
            }

            // 5. REPROGRAMACIONES
            $pedidosReprog = $pedidos->filter(fn($p) => $p->planes->count() > 1)->count();
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
                'id'            => $c->id,
                'nombre'        => $c->nombre_completo,
                'total_pedidos' => $totalPedidos,
                'puntualidad'   => $puntualidad,
                'mora'          => $mora,
                'riesgo'        => $riesgo,
                'recuperacion'  => $recuperacion,
                'reprog'        => $reprog,
                'puntaje'       => $puntaje,
                'calificacion'  => $rangos->calificar($puntaje),
            ];
        })->filter()->values();
    }

    private function calcularDetallePedidos(int $clienteId): Collection
    {
        $hoy = Carbon::today();

        return Pedido::where('cliente_id', $clienteId)
            ->where('estado', 'aprobado')
            ->with(['planPago.cuotas', 'planes'])
            ->get()
            ->filter(fn($p) => $p->planPago !== null)
            ->map(function (Pedido $p) use ($hoy) {
                $cuotas   = $p->planPago->cuotas->where('numero', '>', 0);
                $cerradas = $cuotas->filter(fn($c) => $c->fecha_vencimiento && $c->fecha_vencimiento->lte($hoy));

                $nCerradas = $cerradas->count();
                $nATiempo  = $cerradas->filter(fn($c) =>
                    $c->estado === 'pagado' && $c->fecha_pago && $c->fecha_pago->lte($c->fecha_vencimiento)
                )->count();
                $puntualidad = $nCerradas > 0 ? round(($nATiempo / $nCerradas) * 100, 1) : 100.0;

                $enMora = $cerradas->filter(fn($c) => $c->estado !== 'pagado')->isNotEmpty();

                $saldoVencido   = $cerradas->where('estado', '!=', 'pagado')->sum('monto');
                $cuotasAbiertas = $cuotas->filter(fn($c) => !$c->fecha_vencimiento || $c->fecha_vencimiento->gt($hoy));
                $saldoAbierto   = $cuotasAbiertas->where('estado', '!=', 'pagado')->sum('monto');
                $saldoPendiente = $saldoVencido + $saldoAbierto;
                $riesgo         = $saldoPendiente > 0 ? round(($saldoVencido / $saldoPendiente) * 100, 1) : 0.0;

                return [
                    'numero'       => $p->numero,
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

    public function render()
    {
        $hoy    = Carbon::today();
        $pesos  = PesoIndicador::vigente($hoy) ?? PesoIndicador::porDefecto();
        $rangos = RangoCalificacion::vigente($hoy) ?? RangoCalificacion::porDefecto();

        $todos = $this->calcularClientes($pesos, $rangos);

        $clienteDetalle = $this->detalleId ? $todos->firstWhere('id', $this->detalleId) : null;
        $detallePedidos = $this->detalleId ? $this->calcularDetallePedidos($this->detalleId) : collect();

        $clientes = $todos;
        if ($this->filtroCalificacion !== 'todos') {
            $clientes = $clientes->filter(fn($c) => $c['calificacion'] === $this->filtroCalificacion)->values();
        }
        if (strlen(trim($this->buscarCliente)) >= 2) {
            $q = mb_strtolower(trim($this->buscarCliente));
            $clientes = $clientes->filter(fn($c) => str_contains(mb_strtolower($c['nombre']), $q))->values();
        }
        $clientes = match($this->ordenar) {
            'puntaje_asc' => $clientes->sortBy('puntaje')->values(),
            'nombre'      => $clientes->sortBy('nombre')->values(),
            default       => $clientes->sortByDesc('puntaje')->values(),
        };

        $kpis = [
            'total' => $todos->count(),
            'a'     => $todos->where('calificacion', 'A')->count(),
            'b'     => $todos->where('calificacion', 'B')->count(),
            'c'     => $todos->where('calificacion', 'C')->count(),
            'db'    => $todos->whereIn('calificacion', ['D', 'BLOQUEADO'])->count(),
        ];

        return view('livewire.credito.indicadores.calificacion-cliente',
            compact('clientes', 'kpis', 'pesos', 'rangos', 'clienteDetalle', 'detallePedidos'));
    }
}

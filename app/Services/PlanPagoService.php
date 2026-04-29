<?php

namespace App\Services;

use App\Models\FinancialMatrix;
use Carbon\Carbon;

class PlanPagoService
{
    public function calcular(FinancialMatrix $m, float $total): array
    {
        $cuotaInicial = 0.0;
        $saldo = $total;

        if ($m->usa_cuota_inicial && $m->valor_cuota_inicial > 0) {
            $cuotaInicial = $m->tipo_cuota_inicial === 'porcentaje'
                ? round($total * $m->valor_cuota_inicial / 100, 2)
                : (float) $m->valor_cuota_inicial;
            $saldo = max(0.0, $total - $cuotaInicial);
        }

        $cuotas = max(1, (int) $m->cantidad_cuotas);
        $dias   = max(1, (int) ($m->dias_entre_cuotas ?? 30));

        $montoCuota  = $cuotas > 1 ? round($saldo / $cuotas, 2) : $saldo;
        $ultimaCuota = round($saldo - ($montoCuota * ($cuotas - 1)), 2);

        $hoy = Carbon::today();
        $cuotasPreview = [];

        if ($cuotaInicial > 0) {
            $cuotasPreview[] = [
                'numero'             => 0,
                'tipo'               => 'inicial',
                'monto'              => $cuotaInicial,
                'fecha'              => $hoy->format('d/m/Y'),
                'fecha_vencimiento'  => $hoy->copy(),
            ];
        }

        for ($i = 1; $i <= $cuotas; $i++) {
            $fecha = $hoy->copy()->addDays($dias * $i);
            $cuotasPreview[] = [
                'numero'            => $i,
                'tipo'              => 'regular',
                'monto'             => ($i === $cuotas) ? $ultimaCuota : $montoCuota,
                'fecha'             => $fecha->format('d/m/Y'),
                'fecha_vencimiento' => $fecha,
            ];
        }

        return [
            'cuota_inicial'     => $cuotaInicial,
            'saldo_financiar'   => $saldo,
            'incremento'        => 0.0,
            'monto_cuota'       => $montoCuota,
            'ultima_cuota'      => $ultimaCuota,
            'cantidad_cuotas'   => $cuotas,
            'total_pagar'       => round($cuotaInicial + $saldo, 2),
            'es_contado'        => $m->isContado(),
            'cuotas_preview'    => $cuotasPreview,
            'dias_entre_cuotas' => $dias,
        ];
    }
}

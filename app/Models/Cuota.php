<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cuota extends Model
{
    protected $table = 'cuotas';

    protected $fillable = [
        'plan_pago_id', 'pago_id', 'numero', 'monto', 'estado', 'fecha_vencimiento', 'fecha_pago',
    ];

    protected $casts = [
        'monto'             => 'decimal:2',
        'fecha_vencimiento' => 'date',
        'fecha_pago'        => 'date',
    ];

    public function planPago(): BelongsTo { return $this->belongsTo(PlanPago::class); }
    public function pago(): BelongsTo     { return $this->belongsTo(Pago::class); }

    public function getEstadoFinancieroAttribute(): string
    {
        if ($this->estado === 'pagado') return 'pagado';
        if ($this->fecha_vencimiento && $this->fecha_vencimiento->isPast()) return 'en_mora';
        return 'vigente';
    }

    public function getEstadoFinancieroBadgeAttribute(): array
    {
        return match ($this->estadoFinanciero) {
            'pagado'  => ['bg' => '#DCFCE7', 'cl' => '#15803D', 'lb' => 'Pagado'],
            'en_mora' => ['bg' => '#FEF2F2', 'cl' => '#B91C1C', 'lb' => 'En Mora'],
            default   => ['bg' => '#FEF3C7', 'cl' => '#854F0B', 'lb' => 'Vigente'],
        };
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PlanPago extends Model
{
    protected $table = 'plan_pagos';

    protected $fillable = [
        'pedido_id', 'version', 'estado', 'matriz_nombre',
        'cantidad_cuotas', 'cuota_inicial', 'saldo_financiar',
        'incremento', 'monto_cuota', 'total_pagar', 'notas',
    ];

    protected $casts = [
        'cuota_inicial'   => 'decimal:2',
        'saldo_financiar' => 'decimal:2',
        'incremento'      => 'decimal:2',
        'monto_cuota'     => 'decimal:2',
        'total_pagar'     => 'decimal:2',
    ];

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class);
    }

    public function cuotas(): HasMany
    {
        return $this->hasMany(Cuota::class)->orderBy('numero');
    }

    /** Cuotas regulares (excluye la inicial que es numero=0) */
    public function cuotasRegulares(): HasMany
    {
        return $this->hasMany(Cuota::class)->where('numero', '>', 0)->orderBy('numero');
    }

    public function reprogramacion(): HasOne
    {
        return $this->hasOne(Reprogramacion::class, 'plan_nuevo_id');
    }

    public function getEstadoFinancieroAttribute(): string
    {
        $cuotas = $this->cuotas->where('numero', '>', 0);
        if ($cuotas->isEmpty()) return 'vigente';
        if ($cuotas->every(fn($c) => $c->estado === 'pagado')) return 'pagado';
        if ($cuotas->contains(fn($c) => $c->estadoFinanciero === 'en_mora')) return 'en_mora';
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

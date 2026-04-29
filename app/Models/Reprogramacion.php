<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reprogramacion extends Model
{
    protected $table = 'reprogramaciones';

    protected $fillable = [
        'numero', 'pedido_id', 'plan_viejo_id', 'plan_nuevo_id',
        'version_anterior', 'version_nueva',
        'saldo_reprogramado', 'cuotas_pagadas',
        'motivo', 'creado_por',
    ];

    public static function generarNumero(): string
    {
        $ultimo = static::max('id') ?? 0;
        return 'REPROG-' . str_pad($ultimo + 1, 5, '0', STR_PAD_LEFT);
    }

    protected $casts = [
        'saldo_reprogramado' => 'decimal:2',
    ];

    public function pedido(): BelongsTo   { return $this->belongsTo(Pedido::class); }
    public function planViejo(): BelongsTo { return $this->belongsTo(PlanPago::class, 'plan_viejo_id'); }
    public function planNuevo(): BelongsTo { return $this->belongsTo(PlanPago::class, 'plan_nuevo_id'); }
    public function creadoPor(): BelongsTo { return $this->belongsTo(\App\Models\User::class, 'creado_por'); }
}

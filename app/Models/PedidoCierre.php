<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PedidoCierre extends Model
{
    protected $table    = 'pedido_cierres';
    protected $fillable = [
        'pedido_id', 'plan_pago_id', 'motivo_cierre_id', 'observacion', 'cerrado_por',
        'revertido_at', 'revertido_por', 'motivo_reversion',
    ];
    protected $casts = ['revertido_at' => 'datetime'];

    public function pedido(): BelongsTo        { return $this->belongsTo(Pedido::class); }
    public function planPago(): BelongsTo      { return $this->belongsTo(PlanPago::class); }
    public function motivoCierre(): BelongsTo  { return $this->belongsTo(MotivoCierre::class); }
    public function cerradoPor(): BelongsTo    { return $this->belongsTo(User::class, 'cerrado_por'); }
    public function revertidoPor(): BelongsTo  { return $this->belongsTo(User::class, 'revertido_por'); }

    public function estaRevertido(): bool
    {
        return $this->revertido_at !== null;
    }
}

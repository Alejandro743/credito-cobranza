<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pago extends Model
{
    protected $table = 'pagos';

    protected $fillable = [
        'numero', 'pedido_id', 'plan_pago_id', 'monto_total', 'cantidad_cuotas',
        'creado_por', 'estado', 'anulado_por', 'anulado_at',
    ];

    protected $casts = [
        'monto_total' => 'decimal:2',
        'anulado_at'  => 'datetime',
    ];

    public static function generarNumero(): string
    {
        $ultimo = static::max('id') ?? 0;
        return 'PAGO-' . str_pad($ultimo + 1, 6, '0', STR_PAD_LEFT);
    }

    public function pedido(): BelongsTo    { return $this->belongsTo(Pedido::class); }
    public function planPago(): BelongsTo  { return $this->belongsTo(PlanPago::class); }
    public function creadoPor(): BelongsTo { return $this->belongsTo(User::class, 'creado_por'); }
    public function anuladoPor(): BelongsTo{ return $this->belongsTo(User::class, 'anulado_por'); }
    public function cuotas(): HasMany      { return $this->hasMany(Cuota::class)->orderBy('numero'); }

    public function getEsAnulableAttribute(): bool
    {
        return $this->estado === 'activo' && $this->planPago?->estado === 'activo';
    }
}

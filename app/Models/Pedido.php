<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pedido extends Model
{
    use SoftDeletes;

    protected $table    = 'pedidos';
    protected $fillable = [
        'numero', 'cliente_id', 'vendedor_id', 'financial_matrix_id',
        'estado', 'revisado_por', 'notas', 'total', 'total_pagar', 'cuota_inicial',
        'matriz_snapshot',
        'entrega_ciudad', 'entrega_provincia', 'entrega_municipio', 'entrega_direccion', 'entrega_referencia',
        'doc_anverso_ci', 'doc_reverso_ci', 'doc_anverso_doc', 'doc_reverso_doc', 'doc_aviso_luz',
    ];

    protected $casts = [
        'total'           => 'decimal:2',
        'total_pagar'     => 'decimal:2',
        'cuota_inicial'   => 'decimal:2',
        'matriz_snapshot' => 'array',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function vendedor(): BelongsTo
    {
        return $this->belongsTo(Vendedor::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PedidoItem::class);
    }

    /** Plan activo vigente */
    public function planPago(): HasOne
    {
        return $this->hasOne(PlanPago::class)->where('estado', 'activo')->orderByDesc('version');
    }

    /** Todos los planes (histórico + activo) */
    public function planes(): HasMany
    {
        return $this->hasMany(PlanPago::class)->orderBy('version');
    }

    public function financialMatrix(): BelongsTo
    {
        return $this->belongsTo(FinancialMatrix::class);
    }

    /** Genera el próximo número de pedido: PED-00001 */
    public static function generarNumero(): string
    {
        $ultimo = static::withTrashed()->max('id') ?? 0;
        return 'PED-' . str_pad($ultimo + 1, 5, '0', STR_PAD_LEFT);
    }

    public function getEstadoBadgeAttribute(): array
    {
        return match($this->estado) {
            'en_espera' => ['label' => 'En espera',   'style' => 'background:#FEF3C7; color:#854F0B;'],
            'revision'  => ['label' => 'En revisión', 'style' => 'background:#F0F9FF; color:#0369A1;'],
            'aprobado'  => ['label' => 'Aprobado',    'style' => 'background:#F0FDF4; color:#15803D;'],
            'rechazado' => ['label' => 'Rechazado',   'style' => 'background:#FEF2F2; color:#B91C1C;'],
            'operativo' => ['label' => 'Operativo',   'style' => 'background:#EFF6FF; color:#1D4ED8;'],
            'cerrado'   => ['label' => 'Cerrado',     'style' => 'background:#F3F4F6; color:#374151;'],
            default     => ['label' => $this->estado, 'style' => 'background:#f3f4f6; color:#6b7280;'],
        };
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinancialMatrix extends Model
{
    use SoftDeletes;

    protected $table = 'financial_matrices';

    protected $fillable = [
        'code', 'cycle_id', 'name', 'description', 'active',
        'usa_cuota_inicial', 'tipo_cuota_inicial', 'valor_cuota_inicial',
        'cantidad_cuotas', 'dias_entre_cuotas',
        'usa_incremento', 'tipo_incremento', 'valor_incremento',
    ];

    public function cycle(): BelongsTo
    {
        return $this->belongsTo(CommercialCycle::class, 'cycle_id');
    }

    protected $casts = [
        'active'            => 'boolean',
        'usa_cuota_inicial' => 'boolean',
        'usa_incremento'    => 'boolean',
        'valor_cuota_inicial' => 'decimal:2',
        'valor_incremento'    => 'decimal:2',
    ];

    public function isContado(): bool
    {
        return (int) $this->cantidad_cuotas === 1;
    }
}

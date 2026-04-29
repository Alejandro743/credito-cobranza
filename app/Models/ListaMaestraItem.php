<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ListaMaestraItem extends Model
{
    protected $table = 'lista_maestra_items';

    protected $fillable = [
        'lista_maestra_id', 'product_id',
        'precio_base', 'puntos',
        'stock_inicial', 'stock_consumido', 'stock_actual',
        'descuento', 'active',
        'tipo_incremento', 'factor_incremento', 'monto_incremento',
    ];

    protected $casts = [
        'precio_base'       => 'decimal:2',
        'stock_inicial'     => 'decimal:2',
        'stock_consumido'   => 'decimal:2',
        'stock_actual'      => 'decimal:2',
        'descuento'         => 'decimal:2',
        'factor_incremento' => 'decimal:2',
        'monto_incremento'  => 'decimal:2',
        'active'            => 'boolean',
    ];

    public function getPrecioFinalAttribute(): float
    {
        return max(0, (float)$this->precio_base + (float)$this->monto_incremento);
    }

    public function calcMonto(): float
    {
        if (!$this->tipo_incremento || (float)$this->factor_incremento === 0.0) return 0.0;
        return $this->tipo_incremento === 'porcentaje'
            ? round((float)$this->precio_base * (float)$this->factor_incremento / 100, 2)
            : (float)$this->factor_incremento;
    }

    public function listaMaestra(): BelongsTo
    {
        return $this->belongsTo(ListaMaestra::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function itemsDerivados(): HasMany
    {
        return $this->hasMany(ListaDerivadaItem::class);
    }

    /**
     * Cuando el usuario cambia stock_actual, ajustar stock_inicial.
     * Fórmula: Stock Actual = Stock Inicial - Stock Consumido
     * Si usuario pone nuevo Actual → diferencia = nuevo_actual - actual_anterior
     * Stock Inicial nuevo = Stock Inicial anterior + diferencia
     */
    public function ajustarStock(float $nuevoActual): void
    {
        $diferencia = $nuevoActual - (float) $this->stock_actual;
        $this->stock_inicial = (float) $this->stock_inicial + $diferencia;
        $this->stock_actual  = $nuevoActual;
        $this->save();
    }
}

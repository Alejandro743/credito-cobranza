<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ListaDerivadaItem extends Model
{
    protected $table = 'lista_derivada_items';

    protected $fillable = ['lista_derivada_id', 'lista_maestra_item_id', 'descuento', 'stock_asignado', 'active'];

    protected $casts = [
        'descuento'      => 'decimal:2',
        'stock_asignado' => 'decimal:2',
        'active'         => 'boolean',
    ];

    public function listaDerivada(): BelongsTo
    {
        return $this->belongsTo(ListaDerivada::class);
    }

    public function maestraItem(): BelongsTo
    {
        return $this->belongsTo(ListaMaestraItem::class, 'lista_maestra_item_id');
    }

    /** Precio final = precio_base - descuento */
    public function getPrecioFinalAttribute(): float
    {
        return max(0, (float) $this->maestraItem?->precio_base - (float) $this->descuento);
    }
}

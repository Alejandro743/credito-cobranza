<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MaestroArticulo extends Model
{
    protected $table = 'maestro_articulos';

    protected $fillable = [
        'codigo', 'nombre', 'descripcion',
        'categoria_id', 'unidad_id',
        'precio_base', 'active',
    ];

    protected $casts = [
        'precio_base' => 'decimal:2',
        'active'      => 'boolean',
    ];

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function unidad(): BelongsTo
    {
        return $this->belongsTo(Unidad::class);
    }

    public function stockArticulos(): HasMany
    {
        return $this->hasMany(ListaMaestraItem::class);
    }
}

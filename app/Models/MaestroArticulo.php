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
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
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

    public function getFotoUrlAttribute(): ?string
    {
        if (!$this->codigo || !config('services.cloudinary.cloud_name')) return null;
        $cloud = config('services.cloudinary.cloud_name');
        return "https://res.cloudinary.com/{$cloud}/image/upload/{$this->codigo}";
    }
}

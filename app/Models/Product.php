<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = ['code', 'name', 'description', 'image', 'unidad_id', 'base_price', 'categoria_id', 'active'];

    protected $casts = [
        'active'     => 'boolean',
        'base_price' => 'decimal:2',
    ];

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function unidad(): BelongsTo
    {
        return $this->belongsTo(Unidad::class);
    }

    public function listaMaestraItems(): HasMany
    {
        return $this->hasMany(ListaMaestraItem::class);
    }

    public function getFotoUrlAttribute(): ?string
    {
        if (!$this->code || !config('services.cloudinary.cloud_name')) return null;
        $cloud = config('services.cloudinary.cloud_name');
        return "https://res.cloudinary.com/{$cloud}/image/upload/{$this->code}";
    }
}

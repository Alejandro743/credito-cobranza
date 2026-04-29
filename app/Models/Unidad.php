<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unidad extends Model
{
    protected $table = 'unidades';

    protected $fillable = ['code', 'name', 'abreviatura', 'active'];

    protected $casts = ['active' => 'boolean'];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->abreviatura ? "{$this->name} ({$this->abreviatura})" : $this->name;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Modulo extends Model
{
    protected $table = 'modulos';

    protected $fillable = ['name', 'slug', 'icon', 'color', 'sort_order', 'active'];

    protected $casts = ['active' => 'boolean'];

    /** Todos los submodulos raíz (parent_id = null) */
    public function submodulos(): HasMany
    {
        return $this->hasMany(Submodulo::class)->whereNull('parent_id')->orderBy('sort_order');
    }

    /** Submodulos raíz activos con sus hijos activos cargados */
    public function submodulosActivos(): HasMany
    {
        return $this->hasMany(Submodulo::class)
            ->whereNull('parent_id')
            ->where('active', true)
            ->orderBy('sort_order');
    }
}

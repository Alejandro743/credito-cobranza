<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Submodulo extends Model
{
    protected $table = 'submodulos';

    protected $fillable = ['modulo_id', 'parent_id', 'name', 'slug', 'route_name', 'sort_order', 'active'];

    protected $casts = ['active' => 'boolean'];

    public function modulo(): BelongsTo
    {
        return $this->belongsTo(Modulo::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Submodulo::class, 'parent_id');
    }

    /** Subgrupos hijos (segundo nivel) */
    public function children(): HasMany
    {
        return $this->hasMany(Submodulo::class, 'parent_id')->where('active', true)->orderBy('sort_order');
    }

    public function permisos(): HasMany
    {
        return $this->hasMany(RolSubmoduloPermiso::class);
    }

    public function permisoParaRol(int $roleId): ?RolSubmoduloPermiso
    {
        return $this->permisos()->where('role_id', $roleId)->first();
    }

    /** Es un subgrupo sin ruta (padre de hojas) */
    public function isGroup(): bool
    {
        return is_null($this->route_name);
    }
}

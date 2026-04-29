<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'type', 'description', 'active'];

    protected $casts = ['active' => 'boolean'];

    /** Miembros vía pivot group_user (asignación automática por reglas) */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_user');
    }

    /** Miembros agregados manualmente */
    public function miembrosManual(): HasMany
    {
        return $this->hasMany(GrupoMiembroManual::class);
    }

    public function rules(): BelongsToMany
    {
        return $this->belongsToMany(Rule::class, 'group_rule');
    }

    /** Listas de Precios asignadas al grupo */
    public function listas(): BelongsToMany
    {
        return $this->belongsToMany(ListaMaestra::class, 'grupo_lista_precio', 'group_id', 'lista_maestra_id');
    }
}

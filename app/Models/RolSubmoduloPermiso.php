<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Permission\Models\Role;

class RolSubmoduloPermiso extends Model
{
    protected $table = 'rol_submodulo_permiso';

    protected $fillable = ['role_id', 'submodulo_id', 'puede_ver'];

    protected $casts = [
        'puede_ver' => 'boolean',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function submodulo(): BelongsTo
    {
        return $this->belongsTo(Submodulo::class);
    }
}

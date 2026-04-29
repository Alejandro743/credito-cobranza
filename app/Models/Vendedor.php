<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vendedor extends Model
{
    use SoftDeletes;

    protected $table    = 'vendedores';
    protected $fillable = ['nombre', 'apellido', 'telefono', 'email', 'grupo_id', 'user_id', 'activo'];

    protected $casts = ['activo' => 'boolean'];

    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombre} {$this->apellido}";
    }

    public function grupo(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'grupo_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Clientes registrados por este vendedor (vendedor_id → users.id) */
    public function clientes(): HasMany
    {
        return $this->hasMany(Cliente::class, 'vendedor_id', 'user_id');
    }

    public function pedidos(): HasMany
    {
        return $this->hasMany(Pedido::class);
    }

    /** Vendedor vinculado al usuario autenticado actual. */
    public static function delUsuario(): ?self
    {
        $userId = auth()->id();
        if (!$userId) return null;
        return static::where('user_id', $userId)->where('activo', true)->first();
    }
}

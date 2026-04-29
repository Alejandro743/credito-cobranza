<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    protected $table = 'clientes';

    protected $fillable = [
        'usuario_id', 'vendedor_id', 'id_ln',
        'ci', 'apellido', 'nit', 'correo', 'telefono',
        'ciudad', 'provincia', 'municipio', 'direccion',
        'active',
    ];

    protected $casts = ['active' => 'boolean'];

    // ── Relaciones ────────────────────────────────────────────────────────────

    /** Usuario dueño de esta cuenta cliente */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    /** Usuario vendedor que registró al cliente */
    public function vendedorUsuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vendedor_id');
    }

    public function pedidos(): HasMany
    {
        return $this->hasMany(Pedido::class);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    public function getNombreCompletoAttribute(): string
    {
        $nombre = $this->usuario->name ?? '';
        $apellido = $this->apellido ?? '';
        return trim("$nombre $apellido");
    }
}

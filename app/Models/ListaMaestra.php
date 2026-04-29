<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ListaMaestra extends Model
{
    use SoftDeletes;

    protected $table = 'lista_maestra';

    protected $fillable = [
        'code', 'cycle_id', 'name', 'active', 'estado',
        'tipo_incremento', 'valor_incremento',
        'cantidad_cuotas', 'dias_entre_cuotas',
        'usa_cuota_inicial', 'tipo_cuota_inicial', 'valor_cuota_inicial',
    ];

    protected $casts = [
        'active'              => 'boolean',
        'usa_cuota_inicial'   => 'boolean',
        'valor_incremento'    => 'decimal:2',
        'cantidad_cuotas'     => 'integer',
        'dias_entre_cuotas'   => 'integer',
        'valor_cuota_inicial' => 'decimal:2',
    ];

    public function cycle(): BelongsTo
    {
        return $this->belongsTo(CommercialCycle::class, 'cycle_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(ListaMaestraItem::class);
    }

    public function accesos(): HasMany
    {
        return $this->hasMany(ListaAcceso::class, 'lista_maestra_id');
    }

    public function accesosClientes(): HasMany
    {
        return $this->hasMany(ListaAcceso::class, 'lista_maestra_id')->where('tipo', 'cliente');
    }

    public function accesosVendedores(): HasMany
    {
        return $this->hasMany(ListaAcceso::class, 'lista_maestra_id')->where('tipo', 'vendedor');
    }
}

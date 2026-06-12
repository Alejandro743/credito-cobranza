<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CommercialCycle extends Model
{
    use SoftDeletes;

    protected $fillable = ['code', 'name', 'start_date', 'end_date', 'status', 'notes'];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    public function isOpen(): bool
    {
        return $this->status === 'abierto';
    }

    public static function vigente(): ?self
    {
        return self::where('status', 'abierto')->first();
    }

    public function configuracionPuntos(): HasOne
    {
        return $this->hasOne(ConfiguracionPuntos::class, 'cycle_id');
    }

    public function listasMaestra(): HasMany
    {
        return $this->hasMany(ListaMaestra::class, 'cycle_id');
    }

    public function productos(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'ciclo_productos')
                    ->withPivot('stock_total')
                    ->withTimestamps();
    }
}

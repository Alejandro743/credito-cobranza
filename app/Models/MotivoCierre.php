<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MotivoCierre extends Model
{
    protected $table    = 'motivo_cierres';
    protected $fillable = ['nombre', 'afecta_mora', 'activo'];
    protected $casts    = ['afecta_mora' => 'boolean', 'activo' => 'boolean'];

    public function cierres(): HasMany
    {
        return $this->hasMany(PedidoCierre::class);
    }

    public static function activos()
    {
        return static::where('activo', true)->orderBy('nombre')->get();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConfiguracionPuntos extends Model
{
    protected $table = 'configuracion_puntos';

    protected $fillable = ['cycle_id', 'valor_punto', 'description', 'active'];

    protected $casts = ['valor_punto' => 'decimal:2', 'active' => 'boolean'];

    public function cycle(): BelongsTo
    {
        return $this->belongsTo(CommercialCycle::class, 'cycle_id');
    }
}

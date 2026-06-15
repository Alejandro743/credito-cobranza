<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CobranzaCaso extends Model
{
    protected $table = 'cobranza_casos';

    protected $fillable = [
        'pedido_id', 'responsable_id', 'asignado_por', 'fecha_asignacion',
        'estado', 'observacion_asignacion',
        'fecha_cierre', 'cerrado_por', 'motivo_cierre', 'observacion_cierre',
    ];

    protected $casts = [
        'fecha_asignacion' => 'datetime',
        'fecha_cierre'     => 'datetime',
    ];

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class);
    }

    public function responsable(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }

    public function asignadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'asignado_por');
    }

    public function cerradoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cerrado_por');
    }

    public function actividades(): HasMany
    {
        return $this->hasMany(CobranzaActividad::class, 'caso_id');
    }
}

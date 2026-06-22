<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CobranzaActividad extends Model
{
    protected $table = 'cobranza_actividades';

    protected $fillable = [
        'caso_id', 'numero', 'actividad_origen_id', 'campana_id',
        'tipo_contacto_id', 'accion_id', 'tipo_respuesta_id',
        'responsable_id', 'fecha_programada', 'fecha_inicio', 'fecha_cierre',
        'observacion', 'observacion_cierre', 'estado',
        'created_by', 'cerrado_por', 'motivo_cancelacion', 'tipo_cancelacion_id',
    ];

    protected $casts = [
        'fecha_programada' => 'date',
        'fecha_inicio'     => 'datetime',
        'fecha_cierre'     => 'datetime',
    ];

    public function caso(): BelongsTo        { return $this->belongsTo(CobranzaCaso::class, 'caso_id'); }
    public function actividadOrigen(): BelongsTo { return $this->belongsTo(CobranzaActividad::class, 'actividad_origen_id'); }
    public function tipoContacto(): BelongsTo { return $this->belongsTo(CobranzaCatalogo::class, 'tipo_contacto_id'); }
    public function accion(): BelongsTo      { return $this->belongsTo(CobranzaCatalogo::class, 'accion_id'); }
    public function tipoRespuesta(): BelongsTo   { return $this->belongsTo(CobranzaCatalogo::class, 'tipo_respuesta_id'); }
    public function tipoCancelacion(): BelongsTo { return $this->belongsTo(CobranzaCatalogo::class, 'tipo_cancelacion_id'); }
    public function responsable(): BelongsTo { return $this->belongsTo(User::class, 'responsable_id'); }
    public function creadoPor(): BelongsTo   { return $this->belongsTo(User::class, 'created_by'); }
    public function cerradoPor(): BelongsTo  { return $this->belongsTo(User::class, 'cerrado_por'); }
    public function derivadas(): HasMany     { return $this->hasMany(CobranzaActividad::class, 'actividad_origen_id'); }
}

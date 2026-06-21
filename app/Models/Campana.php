<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campana extends Model
{
    protected $fillable = [
        'nombre', 'estado', 'tipo_contacto_id', 'accion_id',
        'fecha_programada', 'responsable_id', 'creado_por', 'observacion',
    ];

    protected $casts = ['fecha_programada' => 'date'];

    public function tipoContacto()  { return $this->belongsTo(CobranzaCatalogo::class, 'tipo_contacto_id'); }
    public function accion()        { return $this->belongsTo(CobranzaCatalogo::class, 'accion_id'); }
    public function responsable()   { return $this->belongsTo(User::class, 'responsable_id'); }
    public function creadoPor()     { return $this->belongsTo(User::class, 'creado_por'); }
    public function actividades()   { return $this->hasMany(CobranzaActividad::class, 'campana_id'); }
}

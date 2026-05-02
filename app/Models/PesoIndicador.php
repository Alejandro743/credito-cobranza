<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class PesoIndicador extends Model
{
    protected $table    = 'peso_indicadores';
    protected $fillable = [
        'nombre', 'fecha_inicio', 'fecha_fin',
        'peso_puntualidad', 'peso_mora', 'peso_riesgo',
        'peso_recuperacion', 'peso_reprogramacion', 'activo',
    ];

    protected $casts = [
        'fecha_inicio'       => 'date',
        'fecha_fin'          => 'date',
        'peso_puntualidad'   => 'float',
        'peso_mora'          => 'float',
        'peso_riesgo'        => 'float',
        'peso_recuperacion'  => 'float',
        'peso_reprogramacion'=> 'float',
        'activo'             => 'boolean',
    ];

    public function getTotalPesosAttribute(): float
    {
        return $this->peso_puntualidad + $this->peso_mora + $this->peso_riesgo
             + $this->peso_recuperacion + $this->peso_reprogramacion;
    }

    public static function vigente(?Carbon $fecha = null): ?self
    {
        $fecha ??= Carbon::today();
        return static::where('activo', true)
            ->where('fecha_inicio', '<=', $fecha)
            ->where(fn($q) => $q->whereNull('fecha_fin')->orWhere('fecha_fin', '>=', $fecha))
            ->orderByDesc('fecha_inicio')
            ->first();
    }

    public static function porDefecto(): self
    {
        $m = new self();
        $m->peso_puntualidad    = 25;
        $m->peso_mora           = 25;
        $m->peso_riesgo         = 20;
        $m->peso_recuperacion   = 20;
        $m->peso_reprogramacion = 10;
        return $m;
    }
}

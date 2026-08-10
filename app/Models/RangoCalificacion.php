<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class RangoCalificacion extends Model
{
    protected $table    = 'rango_calificaciones';
    protected $fillable = [
        'nombre', 'fecha_inicio', 'fecha_fin',
        'min_a', 'min_b', 'min_c', 'min_d', 'activo',
    ];

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin'    => 'datetime',
        'min_a'        => 'float',
        'min_b'        => 'float',
        'min_c'        => 'float',
        'min_d'        => 'float',
        'activo'       => 'boolean',
    ];

    public function calificar(float $puntaje): string
    {
        return match(true) {
            $puntaje >= $this->min_a => 'A',
            $puntaje >= $this->min_b => 'B',
            $puntaje >= $this->min_c => 'C',
            $puntaje >= $this->min_d => 'D',
            default                  => 'BLOQUEADO',
        };
    }

    /**
     * El vigente es siempre el último registro creado (el que aún no fue cerrado
     * por uno posterior) y que además está activo.
     */
    public static function vigente(): ?self
    {
        return static::where('activo', true)
            ->whereNull('fecha_fin')
            ->orderByDesc('fecha_inicio')
            ->first();
    }

    /**
     * El registro abierto (sin fecha_fin) independientemente de su estado activo.
     * Es el que se cierra automáticamente cuando se crea uno nuevo.
     */
    public static function abierta(): ?self
    {
        return static::whereNull('fecha_fin')->orderByDesc('fecha_inicio')->first();
    }

    public static function porDefecto(): self
    {
        $m        = new self();
        $m->min_a = 85;
        $m->min_b = 70;
        $m->min_c = 50;
        $m->min_d = 30;
        return $m;
    }
}

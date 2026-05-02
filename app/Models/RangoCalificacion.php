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
        'fecha_inicio' => 'date',
        'fecha_fin'    => 'date',
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
        $m        = new self();
        $m->min_a = 85;
        $m->min_b = 70;
        $m->min_c = 50;
        $m->min_d = 30;
        return $m;
    }
}

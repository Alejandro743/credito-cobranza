<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ConfiguracionCorrelativo extends Model
{
    protected $table    = 'configuracion_correlativo';
    protected $fillable = ['prefijo', 'siguiente_numero', 'longitud', 'descripcion', 'activo'];
    protected $casts    = ['activo' => 'boolean'];

    /** Genera el próximo ID_LN de forma atómica e incrementa el contador. */
    public static function generarIdLN(): string
    {
        $config = static::where('activo', true)->first();

        if (!$config) {
            // Fallback si no hay configuración activa
            $n = (Cliente::withTrashed()->max('id') ?? 0) + 1;
            return 'LN' . str_pad($n, 6, '0', STR_PAD_LEFT);
        }

        $id = $config->prefijo . str_pad($config->siguiente_numero, $config->longitud, '0', STR_PAD_LEFT);

        // Incrementar de forma segura
        DB::table('configuracion_correlativo')
            ->where('id', $config->id)
            ->increment('siguiente_numero');

        return $id;
    }
}

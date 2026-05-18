<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ConfiguracionCorrelativo extends Model
{
    protected $table    = 'configuracion_correlativo';
    protected $fillable = ['prefijo', 'siguiente_numero', 'longitud', 'descripcion', 'activo'];
    protected $casts    = ['activo' => 'boolean'];

    /** Genera el próximo ID_LN único de forma atómica, saltando números ya asignados. */
    public static function generarIdLN(): string
    {
        return DB::transaction(function () {
            $config = static::lockForUpdate()->where('activo', true)->first();

            if (!$config) {
                // Fallback: busca un código LN no usado
                $n = (Cliente::withTrashed()->max('id') ?? 0) + 1;
                do {
                    $id = 'LN' . str_pad($n++, 6, '0', STR_PAD_LEFT);
                } while (Cliente::withTrashed()->where('id_ln', $id)->exists());
                return $id;
            }

            // Busca el siguiente número disponible saltando los ya asignados
            $numero = $config->siguiente_numero;
            do {
                $id = $config->prefijo . str_pad($numero++, $config->longitud, '0', STR_PAD_LEFT);
            } while (Cliente::withTrashed()->where('id_ln', $id)->exists());

            // Guarda el próximo número a usar (el que quedó libre después del elegido)
            DB::table('configuracion_correlativo')
                ->where('id', $config->id)
                ->update(['siguiente_numero' => $numero]);

            return $id;
        });
    }
}

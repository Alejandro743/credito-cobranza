<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CobranzaCatalogo extends Model
{
    protected $table = 'cobranza_catalogos';

    protected $fillable = [
        'tipo', 'codigo', 'nombre', 'descripcion',
        'activo', 'sort_order', 'created_by', 'updated_by',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function creadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function modificadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public static function activos(string $tipo)
    {
        return static::where('tipo', $tipo)->where('activo', true)->orderBy('sort_order')->orderBy('nombre')->get();
    }
}

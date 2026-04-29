<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ListaAcceso extends Model
{
    protected $table = 'lista_acceso';

    protected $fillable = ['lista_maestra_id', 'user_id', 'tipo', 'origen'];

    public function listaMaestra(): BelongsTo
    {
        return $this->belongsTo(ListaMaestra::class, 'lista_maestra_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

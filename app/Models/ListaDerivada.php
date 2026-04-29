<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ListaDerivada extends Model
{
    use SoftDeletes;

    protected $table = 'lista_derivada';

    protected $fillable = ['lista_maestra_id', 'name', 'estado'];

    public function listaMaestra(): BelongsTo
    {
        return $this->belongsTo(ListaMaestra::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(ListaDerivadaItem::class);
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'grupo_lista_precio', 'lista_derivada_id', 'group_id');
    }
}

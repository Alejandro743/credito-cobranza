<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ciudad extends Model
{
    protected $table    = 'ciudades';
    protected $fillable = ['nombre'];

    public function provincias(): HasMany
    {
        return $this->hasMany(Provincia::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Rule extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'type', 'condicion', 'description', 'priority', 'active'];

    protected $casts = ['active' => 'boolean'];

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class);
    }
}

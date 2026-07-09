<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['name', 'position'])]

class Modifier extends Model
{
    use SoftDeletes;

    public function merchant() : BelongsTo
    {
        return $this->belongsTo(Merchant::class);
    }

    public function options() : HasMany
    {
        return $this->hasMany(ModifierOption::class);
    }

    public function stores() : BelongsToMany
    {
        return $this->belongsToMany(Store::class);
    }

    protected function casts() : array
    {
        return [
            'position' => 'integer'
        ];
    }
}

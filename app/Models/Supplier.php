<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['name'])]
class Supplier extends Model
{
    use SoftDeletes;

    public function items() : HasMany
    {
        return $this->hasMany(Item::class);
    }
}

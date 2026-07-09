<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['name', 'address', 'city', 'region', 'postal_code', 'country_code', 'phone_number', 'description', 'domain_url', 'lat_lng_point'])]

class Store extends Model
{
    use SoftDeletes;

    public function merchant() : BelongsTo
    {
        return $this->belongsTo(Merchant::class);
    }

    public function employees() : BelongsToMany
    {
        return $this->belongsToMany(Employee::class);
    }

    public function modifiers() : BelongsToMany
    {
        return $this->belongsToMany(Modifier::class);
    }

    public function storeVariants() : HasMany
    {
        return $this->hasMany(StoreVariant::class);
    }

    public function taxes() : BelongsToMany
    {
        return $this->belongsToMany(Tax::class);
    }
}

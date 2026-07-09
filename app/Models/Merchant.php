<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['name'])]
class Merchant extends Model
{
    use HasFactory, SoftDeletes;

    public function categories() : HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function modifiers() : HasMany
    {
        return $this->hasMany(Modifier::class);
    }

    public function modifierOptions() : HasMany
    {
        return $this->hasMany(ModifierOption::class);
    }

    public function items() : HasMany
    {
        return $this->hasMany(Item::class);
    }

    public function employees() : HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function stores() : HasMany
    {
        return $this->hasMany(Store::class);
    }

    public function taxes() : HasMany
    {
        return $this->hasMany(Tax::class);
    }

    public function variants() : HasMany
    {
        return $this->hasMany(Variant::class);
    }

    public function storeVariants() : HasMany
    {
        return $this->hasMany(StoreVariant::class);
    }
}

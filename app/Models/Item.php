<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['handle', 'name', 'description', 'track_stock', 'sold_by_weight', 'is_composite', 'use_production', 'category_id', 'form', 'colour', 'image_url', 'option1_name', 'option2_name', 'option3_name'])]

class Item extends Model
{
    use SoftDeletes;

    public function merchant() : BelongsTo 
    {
        return $this->belongsTo(Merchant::class);
    }

    public function category() : BelongsTo 
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier() : BelongsTo 
    {
        return $this->belongsTo(Supplier::class);
    }

    public function variants() : HasMany 
    {
        return $this->hasMany(Variant::class);
    }

    protected function casts() : array
    {
        return [
            'track_stock' => 'boolean',
            'sold_by_weight' => 'boolean',
            'is_composite' => 'boolean',
            'use_production' => 'boolean',
        ];
    }
}

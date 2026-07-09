<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['sku', 'option1_value', 'option2_value', 'option3_value', 'barcode', 'cost', 'purchase_cost', 'default_pricing_type', 'default_price'])]

class Variant extends Model
{
    use SoftDeletes;

    public function merchant() : BelongsTo
    {
        return $this->belongsTo(Merchant::class);
    }

    public function item() : BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function storeVariants() : HasMany
    {
        return $this->hasMany(StoreVariant::class);
    }

    protected function casts() : array
    {
        return [
            'cost' => 'decimal:3',
            'purchase_cost' => 'decimal:3',
            'default_price' => 'decimal:3',
        ];
    }
}

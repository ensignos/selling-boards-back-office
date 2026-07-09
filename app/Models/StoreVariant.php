<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['pricing_type', 'price', 'available_for_sale', 'optimal_stock', 'low_stock'])]
class StoreVariant extends Model
{
    use SoftDeletes;

    public function merchant() : BelongsTo
    {
        return $this->belongsTo(Merchant::class);
    }

    public function store() : BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function variant() : BelongsTo
    {
        return $this->belongsTo(Variant::class);
    }

    protected function casts() : array
    {
        return [
            'price' => 'decimal:6',
            'available_for_sale' => 'boolean',
        ];
    }
}

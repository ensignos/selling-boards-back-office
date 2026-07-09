<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['pricing_type', 'price', 'available_for_sale', 'optimal_stock', 'low_stock'])]
class StoreVariant extends Model
{
    use SoftDeletes;
}

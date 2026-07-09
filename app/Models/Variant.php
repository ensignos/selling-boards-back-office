<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['sku', 'option1_value', 'option2_value', 'option3_value', 'barcode', 'cost', 'purchase_cost', 'default_pricing_type', 'default_price'])]

class Variant extends Model
{
    use SoftDeletes;
}

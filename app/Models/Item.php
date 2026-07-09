<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['handle', 'name', 'description', 'track_stock', 'sold_by_weight', 'is_composite', 'use_production', 'category_id', 'form', 'color', 'image_url', 'option1_name', 'option2_name', 'option3_name'])]

class Item extends Model
{
    use SoftDeletes;
}

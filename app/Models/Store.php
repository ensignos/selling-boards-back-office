<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['name', 'address', 'city', 'region', 'postal_code', 'country_code', 'phone_number', 'description', 'domain_url', 'lat_lng_point'])]

class Store extends Model
{
    use SoftDeletes;
}

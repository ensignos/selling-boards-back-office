<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['name', 'email', 'phone_number', 'is_owner'])]
class Employee extends Model
{
    use SoftDeletes;

    public function merchant() : BelongsTo 
    {
        return $this->belongsTo(Merchant::class);
    }

    public function stores() : BelongsToMany
    {
        return $this->belongsToMany(Store::class);
    }

    protected function casts() : array
    {
        return [
            'is_owner' => 'boolean'
        ];
    }
}

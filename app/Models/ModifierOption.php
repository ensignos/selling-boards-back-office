<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['name', 'price', 'position'])]

class ModifierOption extends Model
{
    use SoftDeletes;

    public function merchant() : BelongsTo
    {
        return $this->belongsTo(Merchant::class);
    }

    public function modifier() : BelongsTo
    {
        return $this->belongsTo(Modifier::class);
    }

     protected function casts() : array
    {
        return [
            'price' => 'decimal:6',
            'position' => 'integer'
        ];
    }
}

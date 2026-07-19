<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class CartItem extends Model
{
    protected $fillable = ['user_id', 'itemable_id', 'itemable_type', 'service_product_id', 'quantity', 'unit_price', 'name'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function itemable(): MorphTo
    {
        return $this->morphTo();
    }

    public function serviceProduct(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'service_product_id');
    }
}

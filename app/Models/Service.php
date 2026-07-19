<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Service extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'price', 'image', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean', 'price' => 'decimal:2'];
    }

    public function orderItems(): MorphMany
    {
        return $this->morphMany(OrderItem::class, 'itemable');
    }
}

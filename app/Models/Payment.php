<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Payment extends Model
{
    protected $fillable = ['order_id', 'payable_type', 'payable_id', 'method', 'amount', 'status', 'reference_id', 'payment_url', 'raw_response'];

    protected function casts(): array
    {
        return ['amount' => 'decimal:2', 'raw_response' => 'array'];
    }

    public function payable(): MorphTo
    {
        return $this->morphTo();
    }
}

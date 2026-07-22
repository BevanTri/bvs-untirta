<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mechanic extends Model
{
    protected $fillable = ['name', 'specialist'];

    public function repairOrders(): HasMany
    {
        return $this->hasMany(RepairOrder::class);
    }
}

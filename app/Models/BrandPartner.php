<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrandPartner extends Model
{
    protected $fillable = ['name', 'logo', 'url', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }
}

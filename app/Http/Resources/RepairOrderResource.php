<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RepairOrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'customer' => $this->customer?->name,
            'vehicle' => $this->vehicle?->plate_number,
            'status' => $this->status,
            'total' => (int) $this->total,
        ];
    }
}

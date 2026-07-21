<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RepairOrderResource;
use App\Models\RepairOrder;

class RepairOrderController extends Controller
{
    public function index()
    {
        return RepairOrderResource::collection(RepairOrder::with('customer', 'vehicle', 'mechanic', 'items')->latest()->get());
    }
}

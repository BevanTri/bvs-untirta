<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RepairOrder;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $r)
    {
        $q = RepairOrder::with('customer', 'vehicle', 'mechanic', 'items');

        if ($s = $r->search) {
            $q->where(function ($q) use ($s) {
                $q->where('order_number', 'like', "%$s%")
                  ->orWhereHas('customer', fn($q) => $q->where('name', 'like', "%$s%"))
                  ->orWhereHas('vehicle', fn($q) => $q->where('plate_number', 'like', "%$s%"));
            });
        }

        if ($status = $r->status) {
            $q->where('status', $status);
        }

        return $q->latest()->paginate($r->per_page ?? 20);
    }
}

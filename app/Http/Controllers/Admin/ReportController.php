<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\RepairOrder;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $r)
    {
        $period = $r->period ?? 'monthly';
        $start = match ($period) {
            'daily' => now()->startOfDay(),
            'weekly' => now()->startOfWeek(),
            'monthly' => now()->startOfMonth(),
            'yearly' => now()->startOfYear(),
            default => now()->startOfMonth(),
        };
        $end = now()->endOfDay();

        $orderRevenue = Order::whereBetween('created_at', [$start, $end])->sum('total');
        $repairRevenue = RepairOrder::whereBetween('created_at', [$start, $end])->sum('total');
        $totalRevenue = $orderRevenue + $repairRevenue;

        $orderCount = Order::whereBetween('created_at', [$start, $end])->count();
        $repairCount = RepairOrder::whereBetween('created_at', [$start, $end])->count();

        $orders = Order::whereBetween('created_at', [$start, $end])->latest()->get();
        $repairs = RepairOrder::with('customer', 'vehicle')->whereBetween('created_at', [$start, $end])->latest()->get();

        return view('admin.reports', compact('period', 'orderRevenue', 'repairRevenue', 'totalRevenue', 'orderCount', 'repairCount', 'orders', 'repairs'));
    }
}

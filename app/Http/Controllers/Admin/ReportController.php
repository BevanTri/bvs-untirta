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

    public function exportCsv(Request $r)
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

        $orders = Order::whereBetween('created_at', [$start, $end])->latest()->get();
        $repairs = RepairOrder::with('customer', 'vehicle')->whereBetween('created_at', [$start, $end])->latest()->get();

        $headers = ['Content-Type'=>'text/csv; charset=utf-8','Content-Disposition'=>'attachment; filename=reports-'.$period.'-'.now()->format('Ymd').'.csv'];
        $callback = function () use ($orders, $repairs, $period) {
            $fh = fopen('php://output', 'w');
            fprintf($fh, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($fh, ['LAPORAN '.strtoupper($period)], ';');
            fputcsv($fh, ['Periode: '.now()->startOfWeek()->format('d/m/Y').' - '.now()->format('d/m/Y')], ';');
            fputcsv($fh, [], ';');

            fputcsv($fh, ['PESANAN PRODUK'], ';');
            fputcsv($fh, ['Invoice','Total','Status','Tanggal'], ';');
            foreach ($orders as $o) {
                fputcsv($fh, [$o->order_number, number_format($o->total,0,',','.'), $o->status, $o->created_at->format('d/m/Y')], ';');
            }

            fputcsv($fh, [], ';');
            fputcsv($fh, ['SERVIS WORKSHOP'], ';');
            fputcsv($fh, ['No. Servis','Pelanggan','Kendaraan','Total','Status','Tanggal'], ';');
            foreach ($repairs as $r) {
                fputcsv($fh, [$r->order_number, $r->customer->name, $r->vehicle->plate_number, number_format($r->total,0,',','.'), $r->status, $r->created_at->format('d/m/Y')], ';');
            }

            fputcsv($fh, [], ';');
            fputcsv($fh, ['RINGKASAN'], ';');
            fputcsv($fh, ['Total Pesanan', count($orders)], ';');
            fputcsv($fh, ['Total Servis', count($repairs)], ';');
            fputcsv($fh, ['Revenue Produk', number_format($orders->sum('total'),0,',','.')], ';');
            fputcsv($fh, ['Revenue Servis', number_format($repairs->sum('total'),0,',','.')], ';');
            fputcsv($fh, ['Total Revenue', number_format($orders->sum('total') + $repairs->sum('total'),0,',','.')], ';');

            fclose($fh);
        };
        return response()->stream($callback, 200, $headers);
    }
}

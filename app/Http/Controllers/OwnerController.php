<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\OrderItem;
class OwnerController extends Controller
{
    public function dashboard()
    {
        // Total Revenue (hanya yang sudah bayar/verified/dst)
        $totalRevenue = Order::whereIn('status', ['paid', 'verified', 'in_production', 'ready_to_ship', 'shipped', 'completed'])
            ->sum('total_harga');

        // Total Orders
        $totalOrders = Order::count();

        // Top 5 Customers (by total belanja)
        $topCustomers = User::where('role', 'customer')
            ->withSum(['orders' => function($query) {
                $query->whereIn('status', ['paid', 'verified', 'in_production', 'ready_to_ship', 'shipped', 'completed']);
            }], 'total_harga')
            ->orderByDesc('orders_sum_total_harga')
            ->take(5)
            ->get();

        // Grafik Revenue 12 Bulan Terakhir
        $monthlyRevenue = Order::whereIn('status', ['paid', 'verified', 'in_production', 'ready_to_ship', 'shipped', 'completed'])
            ->where('created_at', '>=', now()->subMonths(12))
            ->select(
                DB::raw('SUM(total_harga) as revenue'),
                DB::raw("DATE_FORMAT(created_at, '%M %Y') as month"),
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as sort_key")
            )
            ->groupBy('month', 'sort_key')
            ->orderBy('sort_key')
            ->get();

        return view('owner.dashboard', compact('totalRevenue', 'totalOrders', 'topCustomers', 'monthlyRevenue'));
    }
    public function analisisKeuntungan()
    {
        // Revenue by Sablon Type
        $revenueBySablon = DB::table('order_items')
            ->join('produks', 'order_items.produk_id', '=', 'produks.id')
            ->join('jenis_sablons', 'produks.jenis_sablon_id', '=', 'jenis_sablons.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('orders.status', ['paid', 'verified', 'in_production', 'ready_to_ship', 'shipped', 'completed'])
            ->select('jenis_sablons.nama as nama_sablon', DB::raw('SUM(order_items.subtotal) as total'))
            ->groupBy('jenis_sablons.nama')
            ->get();

        // Monthly comparison (current vs previous month)
        $currentMonthRevenue = Order::whereIn('status', ['paid', 'verified', 'in_production', 'ready_to_ship', 'shipped', 'completed'])
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_harga');

        $lastMonthRevenue = Order::whereIn('status', ['paid', 'verified', 'in_production', 'ready_to_ship', 'shipped', 'completed'])
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('total_harga');

        return view('owner.analisis-keuntungan', compact('revenueBySablon', 'currentMonthRevenue', 'lastMonthRevenue'));
    }

    public function rekapEksekutif()
    {
        $stats = [
            'total_sales' => Order::whereIn('status', ['paid', 'verified', 'in_production', 'ready_to_ship', 'shipped', 'completed'])->sum('total_harga'),
            'total_orders' => Order::count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'total_items_sold' => OrderItem::whereHas('order', function($q) {
                $q->whereIn('status', ['paid', 'verified', 'in_production', 'ready_to_ship', 'shipped', 'completed']);
            })->sum('quantity'),
        ];

        $topSellingProducts = DB::table('order_items')
            ->join('produks', 'order_items.produk_id', '=', 'produks.id')
            ->join('jenis_sablons', 'produks.jenis_sablon_id', '=', 'jenis_sablons.id')
            ->select('jenis_sablons.nama as nama_sablon', DB::raw('SUM(order_items.quantity) as total_qty'))
            ->groupBy('jenis_sablons.nama')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        return view('owner.rekap-eksekutif', compact('stats', 'topSellingProducts'));
    }
}

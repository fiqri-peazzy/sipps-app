<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CustomerReturn;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class DashboardAnalytics extends Component
{
    public $periodDays = 30;

    public function render()
    {
        $startDate = now()->subDays($this->periodDays);
        $endDate = now();

        // Operational Metrics
        $operational = $this->getOperationalMetrics($startDate, $endDate);

        // Financial Metrics
        $financial = $this->getFinancialMetrics($startDate, $endDate);

        // Chart Data
        $charts = $this->getChartData($startDate, $endDate);

        return view('livewire.admin.dashboard-analytics', compact('operational', 'financial', 'charts'));
    }

    private function getOperationalMetrics($startDate, $endDate)
    {
        // Total Orders
        $totalOrders = Order::whereBetween('created_at', [$startDate, $endDate])->count();

        // Production Status
        $productionStats = OrderItem::whereHas('order', function ($q) use ($startDate, $endDate) {
            $q->whereBetween('verified_at', [$startDate, $endDate]);
        })
            ->select('production_status', DB::raw('count(*) as count'))
            ->groupBy('production_status')
            ->pluck('count', 'production_status')
            ->toArray();

        // Shipping Status
        $shippingStats = Order::whereBetween('shipped_at', [$startDate, $endDate])
            ->select('status_pengiriman', DB::raw('count(*) as count'))
            ->groupBy('status_pengiriman')
            ->pluck('count', 'status_pengiriman')
            ->toArray();

        // Returns
        $totalReturns = CustomerReturn::whereBetween('created_at', [$startDate, $endDate])->count();
        $pendingReturns = CustomerReturn::where('status', 'pending')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // On-Time Delivery
        $completedOrders = Order::where('status', 'completed')
            ->whereBetween('completed_at', [$startDate, $endDate])
            ->get();

        $onTimeOrders = $completedOrders->filter(function ($order) {
            $deadline = $order->items->min('deadline');
            return $deadline && $order->completed_at->lte($deadline);
        })->count();

        $onTimeRate = $completedOrders->count() > 0
            ? ($onTimeOrders / $completedOrders->count()) * 100
            : 0;

        return [
            'total_orders' => $totalOrders,
            'production_stats' => $productionStats,
            'shipping_stats' => $shippingStats,
            'total_returns' => $totalReturns,
            'pending_returns' => $pendingReturns,
            'on_time_rate' => round($onTimeRate, 1),
            'completed_orders' => $completedOrders->count(),
        ];
    }

    private function getFinancialMetrics($startDate, $endDate)
    {
        // Total Revenue
        $totalRevenue = Order::whereIn('status', ['completed', 'shipped'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_harga');

        // Payment Status
        $paymentStats = Order::whereBetween('created_at', [$startDate, $endDate])
            ->select('payment_status', DB::raw('count(*) as count'), DB::raw('sum(total_harga) as total'))
            ->groupBy('payment_status')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->payment_status => [
                    'count' => $item->count,
                    'total' => $item->total
                ]];
            })
            ->toArray();

        // Top Products
        $topProducts = OrderItem::whereHas('order', function ($q) use ($startDate, $endDate) {
            $q->whereBetween('created_at', [$startDate, $endDate]);
        })
            ->with(['produk.jenisSablon', 'produk.ukuran'])
            ->select('produk_id', DB::raw('count(*) as count'), DB::raw('sum(quantity) as total_qty'), DB::raw('sum(subtotal) as revenue'))
            ->groupBy('produk_id')
            ->orderByDesc('revenue')
            ->limit(5)
            ->get();

        // Average Order Value
        $avgOrderValue = Order::whereBetween('created_at', [$startDate, $endDate])
            ->avg('total_harga') ?? 0;

        return [
            'total_revenue' => $totalRevenue,
            'payment_stats' => $paymentStats,
            'top_products' => $topProducts,
            'avg_order_value' => $avgOrderValue,
        ];
    }

    private function getChartData($startDate, $endDate)
    {
        // Daily Orders Chart (last 7 days)
        $dailyOrders = Order::whereBetween('created_at', [now()->subDays(6), now()])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $dates = [];
        $counts = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dates[] = now()->subDays($i)->format('d M');
            $order = $dailyOrders->firstWhere('date', $date);
            $counts[] = $order ? $order->count : 0;
        }

        // Revenue Chart (last 7 days)
        $dailyRevenue = Order::whereBetween('created_at', [now()->subDays(6), now()])
            ->whereIn('status', ['completed', 'shipped'])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('sum(total_harga) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $revenues = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $order = $dailyRevenue->firstWhere('date', $date);
            $revenues[] = $order ? $order->total : 0;
        }

        // Production Status Distribution
        $productionDistribution = OrderItem::whereHas('order', function ($q) use ($startDate, $endDate) {
            $q->whereBetween('verified_at', [$startDate, $endDate]);
        })
            ->select('production_status', DB::raw('count(*) as count'))
            ->groupBy('production_status')
            ->get();

        return [
            'daily_orders' => [
                'labels' => $dates,
                'data' => $counts,
            ],
            'daily_revenue' => [
                'labels' => $dates,
                'data' => $revenues,
            ],
            'production_distribution' => $productionDistribution,
        ];
    }

    public function updatedPeriodDays()
    {
        // Trigger re-render when period changes
    }
}

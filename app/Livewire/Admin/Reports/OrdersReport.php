<?php

namespace App\Livewire\Admin\Reports;

use App\Models\Order;
use App\Models\OrderItem;
use App\Services\PriorityCalculator;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use Barryvdh\DomPDF\Facade\Pdf;

class OrdersReport extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // Filter properties
    public $startDate;
    public $endDate;
    public $status = '';

    public function mount()
    {
        // Set default dates
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
    }

    public function render()
    {
        // Query OrderItem yang berelasi dengan Order dan Produk
        $query = OrderItem::with(['order.user', 'produk.jenisSablon'])
            ->whereHas('order', function ($q) {
                $q->whereBetween('created_at', [$this->startDate . ' 00:00:00', $this->endDate . ' 23:59:59']);
                if ($this->status) {
                    $q->where('status', $this->status);
                }
            });

        $orders = $query->latest()->paginate(20);

        // Statistics (Based on unique Orders within the filtered Items)
        $orderIds = (clone $query)->pluck('order_id')->unique();
        $filteredOrders = Order::whereIn('id', $orderIds)->get();
        $filteredItems = (clone $query)->get();

        $stats = [
            'total_orders' => $filteredOrders->count(),
            'total_revenue' => $filteredOrders->sum('total_harga'),
            'avg_order_value' => $filteredOrders->avg('total_harga') ?? 0,
            'total_items' => $filteredItems->sum('quantity'),
            'status_breakdown' => $filteredOrders->groupBy('status')->map->count(),
            'payment_breakdown' => $filteredOrders->groupBy('payment_status')->map->count(),
        ];

        return view('livewire.admin.reports.orders-report', compact('orders', 'stats'));
    }

    public function resetFilters()
    {
        $this->reset(['startDate', 'endDate', 'status']);
        $this->mount();
    }

    public function exportPdf()
    {
        $query = OrderItem::with(['order.user', 'produk.jenisSablon'])
            ->whereHas('order', function ($q) {
                $q->whereBetween('created_at', [$this->startDate . ' 00:00:00', $this->endDate . ' 23:59:59']);
                if ($this->status) {
                    $q->where('status', $this->status);
                }
            });

        $items = $query->latest()->get();

        $orderIds = (clone $query)->pluck('order_id')->unique();
        $filteredOrders = Order::whereIn('id', $orderIds)->get();

        $stats = [
            'total_orders' => $filteredOrders->count(),
            'total_revenue' => $filteredOrders->sum('total_harga'),
            'avg_order_value' => $filteredOrders->avg('total_harga') ?? 0,
            'total_items' => $items->sum('quantity'),
            'status_breakdown' => $filteredOrders->groupBy('status')->map->count(),
            'payment_breakdown' => $filteredOrders->groupBy('payment_status')->map->count(),
        ];

        $pdf = Pdf::loadView('pdf.orders', [
            'items' => $items,
            'stats' => $stats,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'status' => $this->status,
        ]);

        $filename = 'Laporan_Pesanan_DPS_' . date('Ymd_His') . '.pdf';

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->setPaper('a4', 'landscape')->output(); // Landscape for better table fit
        }, $filename);
    }
}

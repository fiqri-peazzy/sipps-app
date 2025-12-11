<?php

namespace App\Livewire\Admin\Reports;

use App\Models\Order;
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
        // Query dengan filter real-time
        $query = Order::with(['user', 'items.produk'])
            ->whereBetween('created_at', [$this->startDate, $this->endDate]);

        if ($this->status) {
            $query->where('status', $this->status);
        }

        $orders = $query->latest()->paginate(20);

        // Statistics
        $allOrders = Order::whereBetween('created_at', [$this->startDate, $this->endDate])
            ->when($this->status, fn($q) => $q->where('status', $this->status))
            ->get();

        $stats = [
            'total_orders' => $allOrders->count(),
            'total_revenue' => $allOrders->sum('total_harga'),
            'avg_order_value' => $allOrders->avg('total_harga') ?? 0,
            'total_items' => $allOrders->sum('total_item'),
            'status_breakdown' => $allOrders->groupBy('status')->map->count(),
            'payment_breakdown' => $allOrders->groupBy('payment_status')->map->count(),
        ];

        return view('livewire.admin.reports.orders-report', compact('orders', 'stats'));
    }

    public function resetFilters()
    {
        $this->reset(['startDate', 'endDate', 'status']);
        $this->mount(); // Reset ke default
    }

    public function exportPdf()
    {
        $query = Order::with(['user', 'items.produk'])
            ->whereBetween('created_at', [$this->startDate, $this->endDate]);

        if ($this->status) {
            $query->where('status', $this->status);
        }

        $orders = $query->latest()->get();

        $allOrders = Order::whereBetween('created_at', [$this->startDate, $this->endDate])
            ->when($this->status, fn($q) => $q->where('status', $this->status))
            ->get();

        $stats = [
            'total_orders' => $allOrders->count(),
            'total_revenue' => $allOrders->sum('total_harga'),
            'avg_order_value' => $allOrders->avg('total_harga') ?? 0,
            'total_items' => $allOrders->sum('total_item'),
            'status_breakdown' => $allOrders->groupBy('status')->map->count(),
            'payment_breakdown' => $allOrders->groupBy('payment_status')->map->count(),
        ];

        $pdf = Pdf::loadView('pdf.orders', [
            'orders' => $orders,
            'stats' => $stats,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'status' => $this->status,
        ]);

        $filename = 'Laporan_Pesanan_' . date('Ymd_His') . '.pdf';

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->setPaper('a4', 'portrait')->output();
        }, $filename);
    }
}
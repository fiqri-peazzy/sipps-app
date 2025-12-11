<?php

namespace App\Livewire\Admin\Reports;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PriorityLog;
use Livewire\Component;
use Livewire\WithPagination;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class DpsPerformanceReport extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // Filter properties
    public $startDate;
    public $endDate;
    public $productionStatus = '';

    public function mount()
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
    }

    public function render()
    {
        // Query order items dengan filter
        $query = OrderItem::with(['order', 'produk'])
            ->whereHas('order', function ($q) {
                $q->whereBetween('verified_at', [$this->startDate, $this->endDate]);
            });

        if ($this->productionStatus) {
            $query->where('production_status', $this->productionStatus);
        }

        $items = $query->latest()->paginate(20);

        // Calculate metrics
        $allItems = OrderItem::with('order')
            ->whereHas('order', function ($q) {
                $q->whereBetween('verified_at', [$this->startDate, $this->endDate]);
            })
            ->when($this->productionStatus, fn($q) => $q->where('production_status', $this->productionStatus))
            ->get();

        $stats = $this->calculateMetrics($allItems);

        return view('livewire.admin.reports.dps-performance-report', compact('items', 'stats'));
    }

    private function calculateMetrics($items)
    {
        // 1. Average Waiting Time (verified_at ke production_started_at)
        $waitingTimes = $items->filter(function ($item) {
            return $item->order->verified_at && $item->production_started_at;
        })->map(function ($item) {
            return $item->order->verified_at->diffInHours($item->production_started_at);
        });

        $avgWaitingTime = $waitingTimes->avg() ?? 0;

        // 2. On-Time Delivery Rate
        $completedItems = $items->where('production_status', 'completed');
        $onTimeItems = $completedItems->filter(function ($item) {
            return $item->order->completed_at &&
                $item->deadline &&
                $item->order->completed_at->lte($item->deadline);
        });

        $onTimeRate = $completedItems->count() > 0
            ? ($onTimeItems->count() / $completedItems->count()) * 100
            : 0;

        // 3. Priority Distribution
        $priorityDistribution = [
            'very_low' => $items->whereBetween('priority_score', [0, 20])->count(),
            'low' => $items->whereBetween('priority_score', [21, 40])->count(),
            'medium' => $items->whereBetween('priority_score', [41, 60])->count(),
            'high' => $items->whereBetween('priority_score', [61, 80])->count(),
            'very_high' => $items->whereBetween('priority_score', [81, 100])->count(),
        ];

        // 4. Additional Metrics
        $avgPriorityScore = $items->avg('priority_score') ?? 0;
        $avgComplexityScore = $items->avg('complexity_score') ?? 0;

        // 5. Priority Recalculation Stats
        $totalRecalculations = PriorityLog::whereIn('order_item_id', $items->pluck('id'))
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->count();

        // 6. Production Status Breakdown
        $statusBreakdown = $items->groupBy('production_status')->map->count();

        return [
            'total_items' => $items->count(),
            'avg_waiting_time' => round($avgWaitingTime, 2),
            'on_time_rate' => round($onTimeRate, 2),
            'avg_priority_score' => round($avgPriorityScore, 2),
            'avg_complexity_score' => round($avgComplexityScore, 2),
            'priority_distribution' => $priorityDistribution,
            'status_breakdown' => $statusBreakdown,
            'total_recalculations' => $totalRecalculations,
            'completed_items' => $completedItems->count(),
            'on_time_items' => $onTimeItems->count(),
        ];
    }

    public function resetFilters()
    {
        $this->reset(['startDate', 'endDate', 'productionStatus']);
        $this->mount();
    }

    public function exportPdf()
    {
        $query = OrderItem::with(['order', 'produk'])
            ->whereHas('order', function ($q) {
                $q->whereBetween('verified_at', [$this->startDate, $this->endDate]);
            });

        if ($this->productionStatus) {
            $query->where('production_status', $this->productionStatus);
        }

        $items = $query->latest()->get();
        $stats = $this->calculateMetrics($items);

        $pdf = Pdf::loadView('pdf.dps-performance', [
            'items' => $items,
            'stats' => $stats,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'productionStatus' => $this->productionStatus,
        ]);

        $filename = 'Laporan_Kinerja_DPS_' . date('Ymd_His') . '.pdf';

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->setPaper('a4', 'portrait')->output();
        }, $filename);
    }
}

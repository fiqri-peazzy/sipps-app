<?php

namespace App\Livewire\Admin\Reports;

use App\Models\OrderItem;
use Livewire\Component;
use Livewire\WithPagination;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;

class ComparisonReport extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // Filter properties
    public $startDate;
    public $endDate;

    public function mount()
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
    }

    public function render()
    {
        // Get order items dalam periode
        $items = OrderItem::with(['order', 'produk'])
            ->whereHas('order', function ($q) {
                $q->whereBetween('verified_at', [$this->startDate, $this->endDate]);
            })
            ->latest()
            ->paginate(20);

        // Get all items untuk simulasi
        $allItems = OrderItem::with(['order', 'produk'])
            ->whereHas('order', function ($q) {
                $q->whereBetween('verified_at', [$this->startDate, $this->endDate]);
            })
            ->get();

        // Simulasi FCFS vs DPS
        $comparison = $this->simulateComparison($allItems);

        return view('livewire.admin.reports.comparison-report', compact('items', 'comparison'));
    }

    private function simulateComparison(Collection $items)
    {
        // FCFS Simulation: Sort by created_at (first come first serve)
        $fcfsItems = $items->sortBy('order.created_at')->values();

        // DPS Simulation: Sort by priority_score (existing DPS data)
        $dpsItems = $items->sortByDesc('priority_score')->values();

        // Calculate FCFS Metrics
        $fcfsMetrics = $this->calculateMethodMetrics($fcfsItems, 'fcfs');

        // Calculate DPS Metrics
        $dpsMetrics = $this->calculateMethodMetrics($dpsItems, 'dps');

        // Calculate Improvements
        $improvements = [
            'on_time_rate' => $dpsMetrics['on_time_rate'] - $fcfsMetrics['on_time_rate'],
            'avg_completion_time' => $fcfsMetrics['avg_completion_time'] - $dpsMetrics['avg_completion_time'],
            'efficiency' => $dpsMetrics['efficiency'] - $fcfsMetrics['efficiency'],
        ];

        return [
            'fcfs' => $fcfsMetrics,
            'dps' => $dpsMetrics,
            'improvements' => $improvements,
            'total_items' => $items->count(),
        ];
    }

    private function calculateMethodMetrics(Collection $items, string $method)
    {
        $completedItems = $items->where('production_status', 'completed');

        // 1. On-Time Delivery Rate
        $onTimeItems = $completedItems->filter(function ($item) {
            return $item->order->completed_at &&
                $item->deadline &&
                $item->order->completed_at->lte($item->deadline);
        });

        $onTimeRate = $completedItems->count() > 0
            ? ($onTimeItems->count() / $completedItems->count()) * 100
            : 0;

        // 2. Average Completion Time (verified_at to completed_at in hours)
        $completionTimes = $completedItems->filter(function ($item) {
            return $item->order->verified_at && $item->order->completed_at;
        })->map(function ($item) {
            return $item->order->verified_at->diffInHours($item->order->completed_at);
        });

        $avgCompletionTime = $completionTimes->avg() ?? 0;

        // 3. Efficiency Score (items per day)
        $totalDays = now()->parse($this->startDate)->diffInDays(now()->parse($this->endDate)) ?: 1;
        $throughput = $completedItems->count() / $totalDays;

        // Normalize efficiency to 0-100 scale (assume 10 items/day = 100%)
        $efficiency = min(($throughput / 10) * 100, 100);

        // 4. Average Waiting Time
        $waitingTimes = $items->filter(function ($item) {
            return $item->order->verified_at && $item->production_started_at;
        })->map(function ($item) {
            return $item->order->verified_at->diffInHours($item->production_started_at);
        });

        $avgWaitingTime = $waitingTimes->avg() ?? 0;

        // 5. Late Deliveries
        $lateItems = $completedItems->filter(function ($item) {
            return $item->order->completed_at &&
                $item->deadline &&
                $item->order->completed_at->gt($item->deadline);
        });

        $lateRate = $completedItems->count() > 0
            ? ($lateItems->count() / $completedItems->count()) * 100
            : 0;

        // 6. Priority Distribution (only for DPS)
        $priorityDistribution = null;
        if ($method === 'dps') {
            $priorityDistribution = [
                'very_high' => $items->whereBetween('priority_score', [81, 100])->count(),
                'high' => $items->whereBetween('priority_score', [61, 80])->count(),
                'medium' => $items->whereBetween('priority_score', [41, 60])->count(),
                'low' => $items->whereBetween('priority_score', [21, 40])->count(),
                'very_low' => $items->whereBetween('priority_score', [0, 20])->count(),
            ];
        }

        return [
            'on_time_rate' => round($onTimeRate, 2),
            'avg_completion_time' => round($avgCompletionTime, 2),
            'efficiency' => round($efficiency, 2),
            'avg_waiting_time' => round($avgWaitingTime, 2),
            'late_rate' => round($lateRate, 2),
            'throughput' => round($throughput, 2),
            'completed_items' => $completedItems->count(),
            'on_time_items' => $onTimeItems->count(),
            'late_items' => $lateItems->count(),
            'priority_distribution' => $priorityDistribution,
        ];
    }

    public function resetFilters()
    {
        $this->reset(['startDate', 'endDate']);
        $this->mount();
    }

    public function exportPdf()
    {
        $allItems = OrderItem::with(['order', 'produk'])
            ->whereHas('order', function ($q) {
                $q->whereBetween('verified_at', [$this->startDate, $this->endDate]);
            })
            ->get();

        $comparison = $this->simulateComparison($allItems);

        $pdf = Pdf::loadView('pdf.comparison', [
            'comparison' => $comparison,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
        ]);

        $filename = 'Laporan_Perbandingan_FCFS_vs_DPS_' . date('Ymd_His') . '.pdf';

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->setPaper('a4', 'landscape')->output();
        }, $filename);
    }
}

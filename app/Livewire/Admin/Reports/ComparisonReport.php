<?php

namespace App\Livewire\Admin\Reports;

use App\Models\OrderItem;
use Livewire\Component;
use Livewire\WithPagination;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;
use Carbon\Carbon;

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
        if ($items->isEmpty()) {
            return [
                'fcfs' => $this->emptyMetrics(),
                'dps' => $this->emptyMetrics(),
                'improvements' => ['on_time_rate' => 0, 'avg_completion_time' => 0, 'efficiency' => 0],
                'total_items' => 0,
            ];
        }

        // 1. FCFS: PURE SIMULATION (What if we only used FCFS?)
        $fcfsMetrics = $this->runFcfsHypotheticalSimulation($items);

        // 2. DPS: REAL PROGRESS (Actual performance of the current DPS system)
        $dpsMetrics = $this->calculateRealDpsMetrics($items);

        // Hitung Peningkatan
        $improvements = [
            'on_time_rate' => round($dpsMetrics['on_time_rate'] - $fcfsMetrics['on_time_rate'], 2),
            'avg_completion_time' => round($fcfsMetrics['avg_completion_time'] - $dpsMetrics['avg_completion_time'], 2),
            'efficiency' => round($dpsMetrics['efficiency'] - $fcfsMetrics['efficiency'], 2),
        ];

        return [
            'fcfs' => $fcfsMetrics,
            'dps' => $dpsMetrics,
            'improvements' => $improvements,
            'total_items' => $items->count(),
        ];
    }

    private function runFcfsHypotheticalSimulation(Collection $items)
    {
        $totalItems = $items->count();
        $unprocessed = $items->sortBy('order.verified_at')->values();
        $simStartTime = $items->min('order.verified_at') ?? now();

        $capacity = 2;
        $slots = array_fill(0, $capacity, clone $simStartTime);
        $secondsPerUnit = 1800;
        $setupSeconds = 14400;

        $metrics = ['wait' => 0, 'flow' => 0, 'on_time' => 0, 'tardiness' => 0, 'max_late' => 0, 'ship_delay' => 0];

        foreach ($unprocessed as $item) {
            $earliestSlotTime = collect($slots)->min();
            $arrival = $item->order->verified_at ?? $simStartTime;

            $start = $earliestSlotTime->gt($arrival) ? clone $earliestSlotTime : clone $arrival;
            $duration = $setupSeconds + (($item->quantity ?? 1) * $secondsPerUnit);
            $finish = (clone $start)->addSeconds($duration);

            // Metrics
            $metrics['wait'] += $arrival->diffInMinutes($start) / 60;
            $metrics['flow'] += $arrival->diffInMinutes($finish) / 60;

            if ($item->deadline) {
                $lateness = $item->deadline->diffInMinutes($finish, false) / 60;
                if ($lateness > 0) {
                    $metrics['tardiness'] += $lateness;
                    $metrics['max_late'] = max($metrics['max_late'], $lateness);
                } else {
                    $metrics['on_time']++;
                }
            }

            // Hypo Shipping
            $shipDays = ($item->order->kurir == 'jne') ? 2 : 3;
            $delivered = (clone $finish)->addDays($shipDays);
            if ($item->deadline && $delivered->gt(Carbon::parse($item->deadline)->addDay())) {
                $metrics['ship_delay'] += Carbon::parse($item->deadline)->addDay()->diffInHours($delivered);
            }

            $slotIdx = array_search($earliestSlotTime, $slots);
            $slots[$slotIdx] = clone $finish;
        }

        $totalHours = $simStartTime->diffInMinutes(collect($slots)->max()) / 60;
        return $this->formatMetricsArray($totalItems, $metrics, $totalHours);
    }

    private function calculateRealDpsMetrics(Collection $items)
    {
        $totalItems = $items->count();
        $now = now();
        $metrics = ['wait' => 0, 'flow' => 0, 'on_time' => 0, 'tardiness' => 0, 'max_late' => 0, 'ship_delay' => 0];

        foreach ($items as $item) {
            $arrival = $item->order->verified_at ?? $item->created_at;

            if ($item->production_status === 'completed' && $item->order->completed_at) {
                $start = $item->production_started_at ?? $arrival;
                $finish = $item->order->completed_at;
            } elseif ($item->production_status === 'in_progress' && $item->production_started_at) {
                $start = $item->production_started_at;
                $finish = (clone $start)->addSeconds(14400 + (($item->quantity ?? 1) * 1800));
            } else {
                $start = $now->gt($arrival) ? clone $now : clone $arrival;
                $finish = (clone $start)->addSeconds(14400 + (($item->quantity ?? 1) * 1800));
            }

            $metrics['wait'] += $arrival->diffInMinutes($start) / 60;
            $metrics['flow'] += $arrival->diffInMinutes($finish) / 60;

            if ($item->deadline) {
                $lateness = $item->deadline->diffInMinutes($finish, false) / 60;
                if ($lateness > 0) {
                    $metrics['tardiness'] += $lateness;
                    $metrics['max_late'] = max($metrics['max_late'], $lateness);
                } else {
                    $metrics['on_time']++;
                }
            }

            if ($item->order->shipped_at) {
                $shippedAt = $item->order->shipped_at;
                $deadlineShip = $item->deadline ? Carbon::parse($item->deadline)->addDay() : null;
                if ($deadlineShip && $shippedAt->gt($deadlineShip)) {
                    $metrics['ship_delay'] += $deadlineShip->diffInHours($shippedAt);
                }
            }
        }

        $totalDays = Carbon::parse($this->startDate)->diffInDays(Carbon::parse($this->endDate)) ?: 1;
        $res = $this->formatMetricsArray($totalItems, $metrics, $totalDays * 24);
        $res['completed_items'] = $items->where('production_status', 'completed')->count();
        $res['throughput'] = round($res['completed_items'] / $totalDays, 2);

        return $res;
    }

    private function formatMetricsArray($totalItems, $metrics, $totalHours)
    {
        $onTimeRate = ($metrics['on_time'] / $totalItems) * 100;
        return [
            'on_time_rate' => round($onTimeRate, 2),
            'avg_completion_time' => round($metrics['flow'] / $totalItems, 2),
            'avg_waiting_time' => round($metrics['wait'] / $totalItems, 2),
            'total_tardiness' => round($metrics['tardiness'], 2),
            'max_lateness' => round($metrics['max_late'], 2),
            'shipping_delay_score' => round($metrics['ship_delay'] / $totalItems, 2),
            'efficiency' => round(($totalItems / max(1, $totalHours / 24)) * 10, 2),
            'late_rate' => round(100 - $onTimeRate, 2),
            'throughput' => round($totalItems / max(1, $totalHours / 24), 2),
            'completed_items' => $totalItems,
            'on_time_items' => $metrics['on_time'],
            'late_items' => $totalItems - $metrics['on_time'],
        ];
    }

    private function emptyMetrics()
    {
        return [
            'on_time_rate' => 0,
            'avg_completion_time' => 0,
            'avg_waiting_time' => 0,
            'total_tardiness' => 0,
            'max_lateness' => 0,
            'shipping_delay_score' => 0,
            'efficiency' => 0,
            'late_rate' => 0,
            'throughput' => 0,
            'completed_items' => 0,
            'on_time_items' => 0,
            'late_items' => 0,
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

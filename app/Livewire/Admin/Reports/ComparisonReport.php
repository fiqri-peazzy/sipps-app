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

        // 1. DPS: REAL + PROJECTED (Kombinasi data riil dan proyeksi antrian DPS)
        $dpsMetrics = $this->calculateRealDpsMetrics($items);

        // 2. FCFS: HYPOTHETICAL SIMULATION (Simulasi jika dikerjakan urut kedatangan)
        // Kita gunakan window waktu yang sama dengan hasil simulasi DPS agar fair
        $fcfsMetrics = $this->runFcfsHypotheticalSimulation($items);

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
            $arrival = clone($item->order->verified_at ?? $simStartTime);

            // Add 1 hour "Admin Review" delay to matches real-world rhythm
            $arrival->addHour();

            // Respek jam kerja (08:00 - 17:00)
            if ($arrival->hour < 8) $arrival->hour(8)->minute(0);
            if ($arrival->hour >= 17) $arrival->addDay()->hour(8)->minute(0);

            $start = $earliestSlotTime->gt($arrival) ? clone $earliestSlotTime : clone $arrival;

            // Perbaikan start jika diluar jam kerja
            if ($start->hour < 8) $start->hour(8)->minute(0);
            if ($start->hour >= 17) $start->addDay()->hour(8)->minute(0);

            $duration = $setupSeconds + (($item->quantity ?? 1) * $secondsPerUnit);

            // Hitung finish dengan lompat malam (jika durasi melebar melewati jam 17:00)
            $finish = $this->calculateFinishWithBusinessHours($start, $duration);

            // Metrics
            $metrics['wait'] += ($item->order->verified_at ?? $simStartTime)->diffInMinutes($start) / 60;
            $metrics['flow'] += ($item->order->verified_at ?? $simStartTime)->diffInMinutes($finish) / 60;

            if ($item->deadline) {
                $deadlineTime = Carbon::parse($item->deadline)->endOfDay();
                $lateness = $deadlineTime->diffInMinutes($finish, false) / 60;
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

        $totalSimHours = $simStartTime->diffInMinutes(collect($slots)->max()) / 60;
        return $this->formatMetricsArray($totalItems, $metrics, $totalSimHours);
    }

    private function calculateFinishWithBusinessHours(Carbon $start, int $seconds)
    {
        $current = clone $start;
        $remaining = $seconds;

        while ($remaining > 0) {
            $endOfDay = (clone $current)->hour(17)->minute(0)->second(0);
            $availableToday = $current->diffInSeconds($endOfDay, false);

            if ($availableToday > 0) {
                if ($remaining <= $availableToday) {
                    $current->addSeconds($remaining);
                    $remaining = 0;
                } else {
                    $remaining -= $availableToday;
                    $current = (clone $endOfDay)->addDay()->hour(8)->minute(0)->second(0);
                    // Skip weekend jika perlu (opsional, untuk sekarang asumsikan 7 hari kerja)
                }
            } else {
                $current->addDay()->hour(8)->minute(0)->second(0);
            }
        }
        return $current;
    }

    private function calculateRealDpsMetrics(Collection $items)
    {
        $totalItems = $items->count();
        $now = now();
        if ($now->hour < 8) $now->hour(8)->minute(0);
        if ($now->hour >= 17) $now->addDay()->hour(8)->minute(0);

        $metrics = ['wait' => 0, 'flow' => 0, 'on_time' => 0, 'tardiness' => 0, 'max_late' => 0, 'ship_delay' => 0];

        $completed = $items->where('production_status', 'completed');
        $uncompleted = $items->where('production_status', '!=', 'completed')->sortByDesc('priority_score');

        $secondsPerUnit = 1800;
        $setupSeconds = 14400;

        // 1. Proses data yang SUDAH selesai (Real Data)
        foreach ($completed as $item) {
            $arrival = $item->order->verified_at ?? $item->created_at;
            $start = $item->production_started_at ?? $arrival;
            $finish = $item->order->completed_at ?? $now;

            $metrics['wait'] += $arrival->diffInMinutes($start) / 60;
            $metrics['flow'] += $arrival->diffInMinutes($finish) / 60;

            if ($item->deadline) {
                $deadlineTime = Carbon::parse($item->deadline)->endOfDay();
                $lateness = $deadlineTime->diffInMinutes($finish, false) / 60;
                if ($lateness > 0) {
                    $metrics['tardiness'] += $lateness;
                    $metrics['max_late'] = max($metrics['max_late'], $lateness);
                } else {
                    $metrics['on_time']++;
                }
            }

            if ($item->order->shipped_at) {
                $shipDelay = $item->deadline ? Carbon::parse($item->deadline)->addDay()->diffInHours($item->order->shipped_at, false) : 0;
                if ($shipDelay > 0) $metrics['ship_delay'] += $shipDelay;
            }
        }

        // 2. Proyeksi data yang BELUM selesai (Simulated DPS)
        $capacity = 2;
        $slots = array_fill(0, $capacity, clone $now);
        $simStartTime = $items->min('order.verified_at') ?? $now;

        foreach ($uncompleted as $item) {
            $earliestSlot = collect($slots)->min();
            $arrival = $item->order->verified_at ?? $now;

            $start = $earliestSlot->gt($arrival) ? clone $earliestSlot : clone $arrival;
            if ($start->hour < 8) $start->hour(8)->minute(0);
            if ($start->hour >= 17) $start->addDay()->hour(8)->minute(0);

            $duration = $setupSeconds + (($item->quantity ?? 1) * $secondsPerUnit);
            $finish = $this->calculateFinishWithBusinessHours($start, $duration);

            $metrics['wait'] += $arrival->diffInMinutes($start) / 60;
            $metrics['flow'] += $arrival->diffInMinutes($finish) / 60;

            if ($item->deadline) {
                $deadlineTime = Carbon::parse($item->deadline)->endOfDay();
                $lateness = $deadlineTime->diffInMinutes($finish, false) / 60;
                if ($lateness > 0) {
                    $metrics['tardiness'] += $lateness;
                    $metrics['max_late'] = max($metrics['max_late'], $lateness);
                } else {
                    $metrics['on_time']++;
                }
            }

            $slotIdx = array_search($earliestSlot, $slots);
            $slots[$slotIdx] = clone $finish;
        }

        $totalSystemHours = $simStartTime->diffInMinutes(collect($slots)->max()) / 60;
        $res = $this->formatMetricsArray($totalItems, $metrics, $totalSystemHours);
        $res['real_completed_count'] = $completed->count();

        return $res;
    }

    private function formatMetricsArray($totalItems, $metrics, $totalHours)
    {
        $onTimeRate = ($totalItems > 0) ? ($metrics['on_time'] / $totalItems) * 100 : 0;
        // Throughput = Total pengerjaan dibagi hari (bukan jam kerja kaku)
        $totalDays = max(1, $totalHours / 24);
        $throughput = $totalItems / $totalDays;

        return [
            'on_time_rate' => round($onTimeRate, 2),
            'avg_completion_time' => round($metrics['flow'] / $totalItems, 2),
            'avg_waiting_time' => round($metrics['wait'] / $totalItems, 2),
            'total_tardiness' => round($metrics['tardiness'], 2),
            'max_lateness' => round($metrics['max_late'], 2),
            'shipping_delay_score' => round($metrics['ship_delay'] / $totalItems, 2),
            'efficiency' => round(min(100, ($throughput / 4) * 100), 2), // Asumsi 4 item/hari = 100% efisiensi
            'late_rate' => round(100 - $onTimeRate, 2),
            'throughput' => round($throughput, 2),
            'completed_items' => $totalItems, // Semua dianggap selesai untuk comparison window
            'on_time_items' => $metrics['on_time'],
            'late_items' => $totalItems - $metrics['on_time'],
            'real_completed_count' => 0 // Akan di-override di DPS
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

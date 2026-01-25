<?php

namespace App\Services;

use App\Models\OrderItem;
use App\Models\PriorityLog;
use App\Models\PriorityWeight;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PriorityCalculator
{
    /**
     * Calculate Dynamic Priority Score
     */
    public static function calculatePriorityScore(OrderItem $orderItem, ?Carbon $now = null): int
    {
        $now = $now ?? Carbon::now();
        $weights = PriorityWeight::getActive();

        if (!$weights) {
            throw new \Exception('No active priority weights configuration found');
        }

        // Calculate each factor (skala 0-100)
        $urgencyScore = self::calculateUrgencyScore($orderItem, $now);
        $complexityScore = self::normalizeComplexityScore($orderItem);
        $waitingTimeScore = self::calculateWaitingTimeScore($orderItem, $now);
        $quantityScore = self::calculateQuantityScore($orderItem);

        // Apply weights and calculate final score
        $priorityScore =
            ($urgencyScore * $weights->weight_urgency) +
            ($complexityScore * $weights->weight_complexity) +
            ($waitingTimeScore * $weights->weight_waiting_time) +
            ($quantityScore * $weights->weight_quantity);

        return (int) round($priorityScore);
    }

    /**
     * Calculate Urgency Score (0-100)
     * Pendekatan Interval Sederhana untuk Sisa Waktu (Deadline)
     */
    private static function calculateUrgencyScore(OrderItem $orderItem, Carbon $now): float
    {
        $deadline = Carbon::parse($orderItem->deadline);
        $remainingHours = $now->diffInHours($deadline, false);

        // Fallback waktu produksi untuk deteksi zona kritis
        $leadTime = $orderItem->produk->tipe_layanan === 'express' ? 24 : 72;
        $productionTime = (float) ($orderItem->produk->estimasi_waktu ?? $leadTime);

        // 1. Sudah Lewat Deadline atau masuk Zona Kritis (Sisa waktu < Waktu Produksi)
        if ($remainingHours <= $productionTime) {
            return 100;
        }

        // 2. Interval Berdasarkan Sisa Jam sampai Deadline
        if ($remainingHours <= 24)  return 90; // Sangat Urgent (1 hari lagi)
        if ($remainingHours <= 48)  return 75; // Urgent (2 hari lagi)
        if ($remainingHours <= 72)  return 60; // Menengah (3 hari lagi)
        if ($remainingHours <= 120) return 40; // Long (sampai 5 hari)
        if ($remainingHours <= 168) return 20; // Sangat Long (sampai 7 hari)

        return 10; // > 1 minggu
    }

    /**
     * Calculate Waiting Time Score (0-100)
     * Pendekatan Interval Sederhana untuk Waktu Tunggu (Senioritas)
     */
    private static function calculateWaitingTimeScore(OrderItem $orderItem, Carbon $now): float
    {
        $startTime = $orderItem->order->verified_at ?? $orderItem->created_at;
        $waitingHours = Carbon::parse($startTime)->diffInHours($now);

        // Update cache waiting_time_hours (Hanya jika kalkulasi real-time)
        if ($now->isCurrentSecond()) {
            $orderItem->waiting_time_hours = (int) $waitingHours;
            $orderItem->saveQuietly();
        }

        // Interval Berdasarkan Lama Menunggu (Senioritas)
        if ($waitingHours >= 120) return 100; // > 5 hari (Paling senior)
        if ($waitingHours >= 72)  return 80;  // 3-5 hari
        if ($waitingHours >= 48)  return 60;  // 2-3 hari
        if ($waitingHours >= 24)  return 40;  // 1-2 hari
        if ($waitingHours >= 12)  return 20;  // 12-24 jam

        return 10; // < 12 jam (Baru masuk)
    }

    /**
     * Normalize Complexity Score to 0-100
     */
    private static function normalizeComplexityScore(OrderItem $orderItem): float
    {
        $complexityScore = $orderItem->complexity_score ?? 0;
        return (float) ($complexityScore * 10);
    }

    /**
     * Calculate Quantity Score (0-100)
     */
    private static function calculateQuantityScore(OrderItem $orderItem): float
    {
        $quantity = $orderItem->quantity;

        if ($quantity >= 40) return 100;
        if ($quantity >= 30) return 80;
        if ($quantity >= 20) return 60;
        if ($quantity >= 10) return 40;
        if ($quantity >= 5)  return 25;
        return 15;
    }

    /**
     * Calculate and save priority score with logging
     */
    public static function calculateAndSave(OrderItem $orderItem, string $trigger = 'manual_recalc'): OrderItem
    {
        $oldScore = $orderItem->priority_score;
        $newScore = self::calculatePriorityScore($orderItem);
        $factors = self::getFactorsBreakdown($orderItem);

        $orderItem->priority_score = $newScore;
        $orderItem->last_priority_calculated_at = now();
        $orderItem->save();

        self::logPriorityChange($orderItem, $oldScore, $newScore, $factors, $trigger);

        return $orderItem;
    }

    /**
     * Get factors breakdown for logging and display
     */
    public static function getFactorsBreakdown(OrderItem $orderItem): array
    {
        $now = Carbon::now();
        $weights = PriorityWeight::getActive();

        $urgencyScore = self::calculateUrgencyScore($orderItem, $now);
        $complexityScore = self::normalizeComplexityScore($orderItem);
        $waitingTimeScore = self::calculateWaitingTimeScore($orderItem, $now);
        $quantityScore = self::calculateQuantityScore($orderItem);

        $remainingMinutes = $now->diffInMinutes($orderItem->deadline ?? $now, false);
        $remainingHours = round($remainingMinutes / 60, 2);

        $waitingTimeHours = $orderItem->waiting_time_hours ?? 0;
        if (!$waitingTimeHours && $orderItem->order->verified_at) {
            $waitingTimeHours = Carbon::parse($orderItem->order->verified_at)->diffInMinutes($now) / 60;
        }

        // Fallback for complexity if stored value is 0
        $originalComplexity = (float) ($orderItem->complexity_score ?? 0);
        if ($originalComplexity <= 0) {
            $originalComplexity = \App\Services\ComplexityCalculator::calculateAutoScore($orderItem);
        }

        return [
            'urgency' => [
                'raw_score' => (float) round($urgencyScore, 2),
                'weight' => (float) $weights->weight_urgency,
                'weighted_score' => (float) round($urgencyScore * $weights->weight_urgency, 2),
                'deadline' => $orderItem->deadline ? $orderItem->deadline->format('Y-m-d H:i') : '-',
                'remaining_hours' => (float) round($remainingHours, 2),
                'remaining_days' => (float) round($remainingHours / 24, 2),
            ],
            'complexity' => [
                'raw_score' => (float) round($complexityScore, 2),
                'weight' => (float) $weights->weight_complexity,
                'weighted_score' => (float) round($complexityScore * $weights->weight_complexity, 2),
                'complexity_score_original' => $originalComplexity,
            ],
            'waiting_time' => [
                'raw_score' => (float) round($waitingTimeScore, 2),
                'weight' => (float) $weights->weight_waiting_time,
                'weighted_score' => (float) round($waitingTimeScore * $weights->weight_waiting_time, 2),
                'waiting_hours' => (float) round($waitingTimeHours, 2),
            ],
            'quantity' => [
                'raw_score' => (float) round($quantityScore, 2),
                'weight' => (float) $weights->weight_quantity,
                'weighted_score' => (float) round($quantityScore * $weights->weight_quantity, 2),
                'quantity_value' => (int) ($orderItem->quantity ?? 0),
            ],
            'final_score' => self::calculatePriorityScore($orderItem),
        ];
    }

    /**
     * Log priority change
     */
    private static function logPriorityChange(OrderItem $orderItem, ?int $oldScore, int $newScore, array $factors, string $trigger)
    {
        PriorityLog::create([
            'order_item_id' => $orderItem->id,
            'old_score' => $oldScore,
            'new_score' => $newScore,
            'factors' => $factors,
            'trigger' => $trigger,
        ]);
    }

    /**
     * Recalculate priority for all eligible order items
     */
    public static function recalculateAll(string $trigger = 'scheduled_update'): int
    {
        $orderItems = OrderItem::whereHas('order', function ($query) {
            $query->whereIn('status', ['verified', 'in_production']);
        })
            ->whereIn('production_status', ['waiting', 'in_queue'])
            ->get();

        $count = 0;
        foreach ($orderItems as $orderItem) {
            try {
                self::calculateAndSave($orderItem, $trigger);
                $count++;
            } catch (\Exception $e) {
                Log::error("Failed to calculate priority for order item {$orderItem->id}: " . $e->getMessage());
            }
        }
        return $count;
    }

    /**
     * Get priority rank
     */
    public static function getPriorityRank(int $score): string
    {
        if ($score >= 80) return 'Sangat Tinggi';
        if ($score >= 60) return 'Tinggi';
        if ($score >= 40) return 'Menengah';
        if ($score >= 20) return 'Rendah';
        return 'Sangat Rendah';
    }

    /**
     * Get priority color
     */
    public static function getPriorityColor(int $score): string
    {
        if ($score >= 80) return 'danger';
        if ($score >= 60) return 'warning';
        if ($score >= 40) return 'info';
        return 'secondary';
    }

    /**
     * Calculate after return
     */
    public static function calculateAfterReturn(OrderItem $orderItem): OrderItem
    {
        $orderItem->returned_count += 1;
        $orderItem->save();
        return self::calculateAndSave($orderItem, 'after_return');
    }
}

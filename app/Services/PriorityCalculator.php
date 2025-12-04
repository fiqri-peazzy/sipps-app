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
    public static function calculatePriorityScore(OrderItem $orderItem): int
    {
        $weights = PriorityWeight::getActive();

        if (!$weights) {
            throw new \Exception('No active priority weights configuration found');
        }

        // Calculate each factor (skala 0-100)
        $urgencyScore = self::calculateUrgencyScore($orderItem);
        $complexityScore = self::normalizeComplexityScore($orderItem);
        $waitingTimeScore = self::calculateWaitingTimeScore($orderItem);
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
     * Semakin dekat deadline, semakin tinggi score
     */
    private static function calculateUrgencyScore(OrderItem $orderItem): float
    {
        $deadline = Carbon::parse($orderItem->deadline);
        $now = Carbon::now();

        // Jika sudah lewat deadline
        if ($now->greaterThan($deadline)) {
            return 100; // Prioritas tertinggi
        }

        // Hitung sisa waktu dalam jam
        $remainingHours = $now->diffInHours($deadline, false);

        // Estimasi waktu produksi (dari produk atau default 72 jam)
        $estimatedProductionHours = ($orderItem->produk->estimasi_hari ?? 3) * 24;

        // Urgency ratio
        $urgencyRatio = $remainingHours / $estimatedProductionHours;

        // Konversi ke skala 0-100 (terbalik: semakin sedikit waktu = semakin urgent)
        if ($urgencyRatio <= 0.25) return 100; // Sangat urgent (< 25% waktu tersisa)
        if ($urgencyRatio <= 0.50) return 80;  // Urgent (25-50%)
        if ($urgencyRatio <= 0.75) return 60;  // Menengah (50-75%)
        if ($urgencyRatio <= 1.00) return 40;  // Normal (75-100%)
        if ($urgencyRatio <= 1.50) return 20;  // Masih lama (100-150%)
        return 10; // Sangat lama (>150%)
    }

    /**
     * Normalize Complexity Score to 0-100
     */
    private static function normalizeComplexityScore(OrderItem $orderItem): float
    {
        $complexityScore = $orderItem->complexity_score ?? 0;

        // Complexity score dalam skala 0-10, normalize ke 0-100
        return $complexityScore * 10;
    }

    /**
     * Calculate Waiting Time Score (0-100)
     * Semakin lama menunggu, semakin tinggi score
     */
    private static function calculateWaitingTimeScore(OrderItem $orderItem): float
    {
        // Hitung dari verified_at (bukan created_at)
        $startTime = $orderItem->order->verified_at ?? $orderItem->created_at;
        $waitingHours = Carbon::parse($startTime)->diffInHours(now());

        // Update waiting_time_hours
        $orderItem->waiting_time_hours = $waitingHours;
        $orderItem->saveQuietly(); // Save tanpa trigger events

        // Score based on waiting hours
        if ($waitingHours < 24) return 10;   // < 1 hari
        if ($waitingHours < 48) return 30;   // 1-2 hari
        if ($waitingHours < 72) return 50;   // 2-3 hari
        if ($waitingHours < 120) return 70;  // 3-5 hari
        if ($waitingHours < 168) return 85;  // 5-7 hari
        return 100; // > 7 hari
    }

    /**
     * Calculate Quantity Score (0-100)
     * Quantity besar = prioritas lebih tinggi (ekonomi skala)
     */
    private static function calculateQuantityScore(OrderItem $orderItem): float
    {
        $quantity = $orderItem->quantity;

        // Score based on quantity
        if ($quantity >= 100) return 100;  // Bulk order
        if ($quantity >= 50) return 80;
        if ($quantity >= 25) return 60;
        if ($quantity >= 10) return 40;
        if ($quantity >= 5) return 25;
        return 15; // Small order
    }

    /**
     * Calculate and save priority score with logging
     */
    public static function calculateAndSave(OrderItem $orderItem, string $trigger = 'manual_recalc'): OrderItem
    {
        $oldScore = $orderItem->priority_score;

        // Calculate new score
        $newScore = self::calculatePriorityScore($orderItem);

        // Get factors breakdown
        $factors = self::getFactorsBreakdown($orderItem);

        // Update order item
        $orderItem->priority_score = $newScore;
        $orderItem->last_priority_calculated_at = now();
        $orderItem->save();

        // Log the change
        self::logPriorityChange($orderItem, $oldScore, $newScore, $factors, $trigger);

        return $orderItem;
    }

    /**
     * Get factors breakdown for logging and display
     */
    public static function getFactorsBreakdown(OrderItem $orderItem): array
    {
        $weights = PriorityWeight::getActive();

        $urgencyScore = self::calculateUrgencyScore($orderItem);
        $complexityScore = self::normalizeComplexityScore($orderItem);
        $waitingTimeScore = self::calculateWaitingTimeScore($orderItem);
        $quantityScore = self::calculateQuantityScore($orderItem);

        return [
            'urgency' => [
                'raw_score' => round($urgencyScore, 2),
                'weight' => (float) $weights->weight_urgency,
                'weighted_score' => round($urgencyScore * $weights->weight_urgency, 2),
                'deadline' => $orderItem->deadline->format('Y-m-d H:i'),
                'remaining_hours' => Carbon::now()->diffInHours($orderItem->deadline, false),
            ],
            'complexity' => [
                'raw_score' => round($complexityScore, 2),
                'weight' => (float) $weights->weight_complexity,
                'weighted_score' => round($complexityScore * $weights->weight_complexity, 2),
                'complexity_score_original' => $orderItem->complexity_score,
            ],
            'waiting_time' => [
                'raw_score' => round($waitingTimeScore, 2),
                'weight' => (float) $weights->weight_waiting_time,
                'weighted_score' => round($waitingTimeScore * $weights->weight_waiting_time, 2),
                'waiting_hours' => $orderItem->waiting_time_hours,
            ],
            'quantity' => [
                'raw_score' => round($quantityScore, 2),
                'weight' => (float) $weights->weight_quantity,
                'weighted_score' => round($quantityScore * $weights->weight_quantity, 2),
                'quantity_value' => $orderItem->quantity,
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
        // Get order items yang eligible untuk recalculate
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
     * Get priority rank (for display purposes)
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
     * Get priority color (for UI)
     */
    public static function getPriorityColor(int $score): string
    {
        if ($score >= 80) return 'danger';   // Red
        if ($score >= 60) return 'warning';  // Orange
        if ($score >= 40) return 'info';     // Blue
        return 'secondary'; // Gray
    }

    /**
     * Calculate priority after return (dengan penalty)
     */
    public static function calculateAfterReturn(OrderItem $orderItem): OrderItem
    {
        // Increment return count
        $orderItem->returned_count += 1;
        $orderItem->save();

        // Recalculate dengan trigger after_return
        // Return item bisa diberi boost priority karena sudah pernah dikerjakan
        return self::calculateAndSave($orderItem, 'after_return');
    }
}
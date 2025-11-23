<?php

namespace App\Services;

use App\Models\OrderItem;
use App\Models\ComplexityLog;
use Illuminate\Support\Facades\Auth;

class ComplexityCalculator
{
    /**
     * Calculate auto complexity score
     */
    public static function calculateAutoScore(OrderItem $orderItem): float
    {
        $designConfig = $orderItem->design_config;

        if (!$designConfig) {
            return 0;
        }

        // Parameter 1: Jumlah Area yang Ada Desain (bobot 0.25)
        $areaScore = self::calculateAreaScore($designConfig);

        // Parameter 2: Jumlah Object di Canvas (bobot 0.15)
        $objectScore = self::calculateObjectScore($designConfig);

        // Parameter 3: Ukuran File Total (bobot 0.10)
        $fileSizeScore = self::calculateFileSizeScore($designConfig);

        // Parameter 4: Ukuran Sablon (bobot 0.20)
        $sizeScore = self::calculateSablonSizeScore($orderItem);

        // Parameter 5: Jenis Sablon Factor (bobot 0.30)
        $typeScore = self::calculateSablonTypeScore($orderItem);

        // Total Auto Score (skala 0-10)
        $autoScore = ($areaScore * 0.25) +
            ($objectScore * 0.15) +
            ($fileSizeScore * 0.10) +
            ($sizeScore * 0.20) +
            ($typeScore * 0.30);

        return round($autoScore, 2);
    }

    /**
     * Calculate area score (0-10)
     */
    private static function calculateAreaScore($designConfig): float
    {
        $hasDesign = $designConfig['has_design'] ?? [];
        $areaCount = 0;

        foreach ($hasDesign as $area => $has) {
            if ($has) {
                $areaCount++;
            }
        }

        // 1 area = 2.5, 2 area = 5, 3 area = 7.5, 4 area = 10
        return min($areaCount * 2.5, 10);
    }

    /**
     * Calculate object score (0-10)
     */
    private static function calculateObjectScore($designConfig): float
    {
        $fileMetadata = $designConfig['file_metadata'] ?? [];
        $totalObjects = 0;

        foreach ($fileMetadata as $area => $files) {
            $totalObjects += count($files);
        }

        // 1-2 object = 3, 3-4 = 5, 5-6 = 7, 7+ = 10
        if ($totalObjects <= 2) return 3;
        if ($totalObjects <= 4) return 5;
        if ($totalObjects <= 6) return 7;
        return 10;
    }

    /**
     * Calculate file size score (0-10)
     */
    private static function calculateFileSizeScore($designConfig): float
    {
        $fileMetadata = $designConfig['file_metadata'] ?? [];
        $totalFileSize = 0;

        foreach ($fileMetadata as $area => $files) {
            foreach ($files as $file) {
                if ($file['type'] === 'image' && isset($file['file_size'])) {
                    $totalFileSize += $file['file_size'];
                }
            }
        }

        // Convert to MB
        $totalFileSizeMB = $totalFileSize / (1024 * 1024);

        // < 1MB = 2, 1-3MB = 5, 3-5MB = 7, > 5MB = 10
        if ($totalFileSizeMB < 1) return 2;
        if ($totalFileSizeMB < 3) return 5;
        if ($totalFileSizeMB < 5) return 7;
        return 10;
    }

    /**
     * Calculate sablon size score (0-10)
     */
    private static function calculateSablonSizeScore(OrderItem $orderItem): float
    {
        $ukuranNama = $orderItem->produk->ukuran->nama ?? 'A4';

        return match ($ukuranNama) {
            'A5' => 3,
            'A4' => 5,
            'A3' => 7,
            'A3+' => 9,
            default => 5
        };
    }

    /**
     * Calculate sablon type score (0-10)
     */
    private static function calculateSablonTypeScore(OrderItem $orderItem): float
    {
        $jenisSablon = $orderItem->produk->jenisSablon->nama ?? '';

        // Customize sesuai kompleksitas jenis sablon Anda
        if (str_contains(strtolower($jenisSablon), 'dtf')) return 8;
        if (str_contains(strtolower($jenisSablon), 'polyflex')) return 6;
        if (str_contains(strtolower($jenisSablon), 'rubber')) return 7;
        if (str_contains(strtolower($jenisSablon), 'plastisol')) return 9;

        return 5; // Default
    }

    /**
     * Calculate hybrid score (auto + manual)
     */
    public static function calculateHybridScore(OrderItem $orderItem): float
    {
        $autoScore = $orderItem->auto_complexity_score ?? 0;
        $manualScore = $orderItem->manual_complexity_score;

        // Jika belum ada manual score, return auto score
        if (is_null($manualScore)) {
            return $autoScore;
        }

        // Hybrid: 60% auto + 40% manual
        $hybridScore = ($autoScore * 0.6) + ($manualScore * 0.4);

        return round($hybridScore, 2);
    }

    /**
     * Calculate and save complexity score
     */
    public static function calculateAndSave(OrderItem $orderItem, ?float $manualScore = null, ?string $notes = null): OrderItem
    {
        $oldComplexityScore = $orderItem->complexity_score;

        // Calculate auto score
        $autoScore = self::calculateAutoScore($orderItem);
        $orderItem->auto_complexity_score = $autoScore;

        // If manual score provided, save it
        if (!is_null($manualScore)) {
            $orderItem->manual_complexity_score = $manualScore;
            $orderItem->complexity_reviewed_by = Auth::id();
            $orderItem->complexity_reviewed_at = now();
            $changeType = 'manual_override';
        } else {
            $changeType = 'auto_calculated';
        }

        // Calculate hybrid score
        $hybridScore = self::calculateHybridScore($orderItem);
        $orderItem->complexity_score = $hybridScore;

        $orderItem->save();

        // Log the change
        self::logComplexityChange($orderItem, $oldComplexityScore, $hybridScore, $changeType, $notes);

        return $orderItem;
    }

    /**
     * Log complexity change
     */
    private static function logComplexityChange(OrderItem $orderItem, ?float $oldScore, float $newScore, string $changeType, ?string $notes)
    {
        $calculationDetails = [
            'auto_score' => $orderItem->auto_complexity_score,
            'manual_score' => $orderItem->manual_complexity_score,
            'hybrid_formula' => '(auto × 0.6) + (manual × 0.4)',
            'design_areas' => array_keys(array_filter($orderItem->design_config['has_design'] ?? [])),
            'total_objects' => self::countTotalObjects($orderItem->design_config),
        ];

        ComplexityLog::create([
            'order_item_id' => $orderItem->id,
            'old_score' => $oldScore,
            'new_score' => $newScore,
            'change_type' => $changeType,
            'calculation_details' => $calculationDetails,
            'changed_by' => Auth::id(),
            'notes' => $notes,
        ]);
    }

    /**
     * Count total objects in design
     */
    private static function countTotalObjects($designConfig): int
    {
        $fileMetadata = $designConfig['file_metadata'] ?? [];
        $total = 0;

        foreach ($fileMetadata as $area => $files) {
            $total += count($files);
        }

        return $total;
    }

    /**
     * Get calculation breakdown for display
     */
    public static function getCalculationBreakdown(OrderItem $orderItem): array
    {
        $designConfig = $orderItem->design_config;

        return [
            'area_score' => [
                'value' => self::calculateAreaScore($designConfig),
                'weight' => 0.25,
                'label' => 'Jumlah Area Desain',
                'detail' => self::getAreaDetail($designConfig)
            ],
            'object_score' => [
                'value' => self::calculateObjectScore($designConfig),
                'weight' => 0.15,
                'label' => 'Jumlah Object',
                'detail' => self::countTotalObjects($designConfig) . ' object'
            ],
            'filesize_score' => [
                'value' => self::calculateFileSizeScore($designConfig),
                'weight' => 0.10,
                'label' => 'Ukuran File',
                'detail' => self::getFileSizeDetail($designConfig)
            ],
            'size_score' => [
                'value' => self::calculateSablonSizeScore($orderItem),
                'weight' => 0.20,
                'label' => 'Ukuran Sablon',
                'detail' => $orderItem->produk->ukuran->nama ?? 'N/A'
            ],
            'type_score' => [
                'value' => self::calculateSablonTypeScore($orderItem),
                'weight' => 0.30,
                'label' => 'Jenis Sablon',
                'detail' => $orderItem->produk->jenisSablon->nama ?? 'N/A'
            ],
        ];
    }

    private static function getAreaDetail($designConfig): string
    {
        $hasDesign = $designConfig['has_design'] ?? [];
        $areas = [];

        $areaLabels = [
            'front' => 'Depan',
            'back' => 'Belakang',
            'left_sleeve' => 'Lengan Kiri',
            'right_sleeve' => 'Lengan Kanan'
        ];

        foreach ($hasDesign as $area => $has) {
            if ($has) {
                $areas[] = $areaLabels[$area] ?? $area;
            }
        }

        return implode(', ', $areas) ?: 'Tidak ada';
    }

    private static function getFileSizeDetail($designConfig): string
    {
        $fileMetadata = $designConfig['file_metadata'] ?? [];
        $totalFileSize = 0;

        foreach ($fileMetadata as $area => $files) {
            foreach ($files as $file) {
                if ($file['type'] === 'image' && isset($file['file_size'])) {
                    $totalFileSize += $file['file_size'];
                }
            }
        }

        $totalFileSizeMB = $totalFileSize / (1024 * 1024);
        return number_format($totalFileSizeMB, 2) . ' MB';
    }
}

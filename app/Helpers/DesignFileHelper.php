<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use ZipArchive;

class DesignFileHelper
{
    /**
     * Create ZIP file dari multiple files
     */
    public static function createZip(array $files, string $zipFileName)
    {
        $zip = new ZipArchive();
        $zipPath = storage_path('app/temp/' . $zipFileName);

        // Create temp directory if not exists
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            foreach ($files as $file) {
                $filePath = Storage::disk('public')->path($file['path']);

                if (file_exists($filePath)) {
                    // Add file ke zip dengan nama yang jelas
                    $zip->addFile($filePath, $file['name_in_zip']);
                }
            }

            $zip->close();
            return $zipPath;
        }

        return false;
    }

    /**
     * Extract files dari design config
     */
    public static function extractFilesFromDesignConfig($designConfig, $area = null)
    {
        $files = [];
        $fileMetadata = $designConfig['file_metadata'] ?? [];

        // Jika area spesifik
        if ($area) {
            if (isset($fileMetadata[$area])) {
                foreach ($fileMetadata[$area] as $index => $file) {
                    if ($file['type'] === 'image') {
                        $files[] = [
                            'path' => $file['original_path'],
                            'name' => $file['original_name'],
                            'area' => $area,
                            'index' => $index
                        ];
                    }
                }
            }
        } else {
            // Semua area
            foreach ($fileMetadata as $areaName => $areaFiles) {
                foreach ($areaFiles as $index => $file) {
                    if ($file['type'] === 'image') {
                        $files[] = [
                            'path' => $file['original_path'],
                            'name' => $file['original_name'],
                            'area' => $areaName,
                            'index' => $index
                        ];
                    }
                }
            }
        }

        return $files;
    }

    /**
     * Generate metadata text file
     */
    public static function generateMetadataFile($designConfig, $orderItem)
    {
        $content = "=== DESIGN SPECIFICATIONS ===\n\n";
        $content .= "Order Number: {$orderItem->order->order_number}\n";
        $content .= "Item: {$orderItem->produk->jenisSablon->nama}\n";
        $content .= "Ukuran Sablon: {$orderItem->produk->ukuran->nama}\n";
        $content .= "Ukuran Kaos: {$orderItem->ukuran_kaos}\n";
        $content .= "Warna Kaos: {$orderItem->warna_kaos}\n";
        $content .= "Quantity: {$orderItem->quantity}\n\n";

        $fileMetadata = $designConfig['file_metadata'] ?? [];

        foreach ($fileMetadata as $area => $files) {
            if (count($files) > 0) {
                $content .= "=== " . strtoupper($area) . " ===\n\n";

                foreach ($files as $index => $file) {
                    $content .= "File #" . ($index + 1) . ":\n";

                    if ($file['type'] === 'image') {
                        $content .= "  Type: Image\n";
                        $content .= "  Filename: {$file['original_name']}\n";
                        $content .= "  Size: " . number_format($file['file_size'] / 1024, 2) . " KB\n";
                        $content .= "  Position X: {$file['position']['left']}px\n";
                        $content .= "  Position Y: {$file['position']['top']}px\n";
                        $content .= "  Scale X: {$file['position']['scaleX']}\n";
                        $content .= "  Scale Y: {$file['position']['scaleY']}\n";
                        $content .= "  Rotation: {$file['position']['angle']}°\n";
                    } elseif ($file['type'] === 'text') {
                        $content .= "  Type: Text\n";
                        $content .= "  Content: \"{$file['text']}\"\n";
                        $content .= "  Font: {$file['fontFamily']}\n";
                        $content .= "  Font Size: {$file['fontSize']}px\n";
                        $content .= "  Color: {$file['fill']}\n";
                        $content .= "  Position X: {$file['position']['left']}px\n";
                        $content .= "  Position Y: {$file['position']['top']}px\n";
                        $content .= "  Rotation: {$file['position']['angle']}°\n";
                    }

                    $content .= "\n";
                }
            }
        }

        $tempPath = storage_path('app/temp/design-specs-' . time() . '.txt');
        file_put_contents($tempPath, $content);

        return $tempPath;
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Helpers\DesignFileHelper;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class DesignFileController extends Controller
{

    /**
     * Download single file dari specific area
     */
    public function downloadSingleFile($orderId, $itemId, $area, $fileIndex)
    {
        $orderItem = OrderItem::where('order_id', $orderId)
            ->where('id', $itemId)
            ->with('order')
            ->firstOrFail();

        $designConfig = $orderItem->design_config;

        if (!isset($designConfig['file_metadata'][$area][$fileIndex])) {
            abort(404, 'Design file not found');
        }

        $file = $designConfig['file_metadata'][$area][$fileIndex];

        if ($file['type'] !== 'image') {
            abort(400, 'Only image files can be downloaded');
        }

        $filePath = $file['original_path'];
        $fileName = $file['original_name'];

        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'File not found in storage');
        }

        /** @var \Illuminate\Filesystem\FilesystemAdapter $storage */
        $storage = Storage::disk('public');

        return $storage->download($filePath, $fileName);
    }

    /**
     * Download semua files dari specific area (ZIP jika multiple)
     */
    public function downloadAreaFiles($orderId, $itemId, $area)
    {
        $orderItem = OrderItem::where('order_id', $orderId)
            ->where('id', $itemId)
            ->with('order')
            ->firstOrFail();

        $designConfig = $orderItem->design_config;
        $files = DesignFileHelper::extractFilesFromDesignConfig($designConfig, $area);

        if (count($files) === 0) {
            abort(404, 'No design files found for this area');
        }

        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('public');

        return $disk->download($files[0]['path'], $files[0]['name']);


        // Multiple files - buat ZIP
        $zipFiles = [];
        foreach ($files as $index => $file) {
            $zipFiles[] = [
                'path' => $file['path'],
                'name_in_zip' => ($index + 1) . '-' . $file['name']
            ];
        }

        $zipFileName = "design-{$orderItem->order->order_number}-item{$itemId}-{$area}-" . time() . ".zip";
        $zipPath = DesignFileHelper::createZip($zipFiles, $zipFileName);

        if (!$zipPath) {
            abort(500, 'Failed to create ZIP file');
        }

        return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
    }

    /**
     * Download semua design files dari 1 item (semua area)
     */
    public function downloadItemDesigns($orderId, $itemId)
    {
        $orderItem = OrderItem::where('order_id', $orderId)
            ->where('id', $itemId)
            ->with(['order', 'produk.jenisSablon', 'produk.ukuran'])
            ->firstOrFail();

        $designConfig = $orderItem->design_config;
        $files = DesignFileHelper::extractFilesFromDesignConfig($designConfig);

        if (count($files) === 0) {
            abort(404, 'No design files found');
        }

        // Buat ZIP dengan struktur folder per area
        $zipFiles = [];

        foreach ($files as $file) {
            $areaLabel = match ($file['area']) {
                'front' => 'Depan',
                'back' => 'Belakang',
                'left_sleeve' => 'Lengan-Kiri',
                'right_sleeve' => 'Lengan-Kanan',
                default => $file['area']
            };

            $zipFiles[] = [
                'path' => $file['path'],
                'name_in_zip' => "{$areaLabel}/{$file['name']}"
            ];
        }

        // Tambahkan metadata text file
        $metadataPath = DesignFileHelper::generateMetadataFile($designConfig, $orderItem);
        $zipFiles[] = [
            'path' => $metadataPath,
            'name_in_zip' => 'DESIGN-SPECIFICATIONS.txt'
        ];

        $zipFileName = "design-{$orderItem->order->order_number}-item{$itemId}-complete-" . time() . ".zip";
        $zipPath = DesignFileHelper::createZip($zipFiles, $zipFileName);

        // Delete metadata temp file
        if (file_exists($metadataPath)) {
            unlink($metadataPath);
        }

        if (!$zipPath) {
            abort(500, 'Failed to create ZIP file');
        }

        return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
    }

    /**
     * Download semua design files dari entire order (semua items)
     */
    public function downloadOrderDesigns($orderId)
    {
        $order = Order::with(['items.produk.jenisSablon', 'items.produk.ukuran'])
            ->findOrFail($orderId);

        $zipFiles = [];

        foreach ($order->items as $itemIndex => $orderItem) {
            $designConfig = $orderItem->design_config;
            $files = DesignFileHelper::extractFilesFromDesignConfig($designConfig);

            foreach ($files as $file) {
                $areaLabel = match ($file['area']) {
                    'front' => 'Depan',
                    'back' => 'Belakang',
                    'left_sleeve' => 'Lengan-Kiri',
                    'right_sleeve' => 'Lengan-Kanan',
                    default => $file['area']
                };

                $itemLabel = "Item-" . ($itemIndex + 1) . "-" . $orderItem->produk->jenisSablon->nama;

                $zipFiles[] = [
                    'path' => $file['path'],
                    'name_in_zip' => "{$itemLabel}/{$areaLabel}/{$file['name']}"
                ];
            }

            // Add metadata per item
            $metadataPath = DesignFileHelper::generateMetadataFile($designConfig, $orderItem);
            $zipFiles[] = [
                'path' => $metadataPath,
                'name_in_zip' => "{$itemLabel}/SPECIFICATIONS.txt"
            ];
        }

        $zipFileName = "design-complete-{$order->order_number}-" . time() . ".zip";
        $zipPath = DesignFileHelper::createZip($zipFiles, $zipFileName);

        if (!$zipPath) {
            abort(500, 'Failed to create ZIP file');
        }

        return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
    }
}

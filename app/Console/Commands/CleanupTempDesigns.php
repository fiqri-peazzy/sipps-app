<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CleanupTempDesigns extends Command
{
    protected $signature = 'designs:cleanup-temp';
    protected $description = 'Cleanup temporary design files older than 24 hours';

    public function handle()
    {
        $tempPath = 'designs/temp';
        $deletedCount = 0;
        $deletedSize = 0;

        if (!Storage::disk('public')->exists($tempPath)) {
            $this->info('No temp folder found.');
            return 0;
        }

        // Get all user folders in temp
        $userFolders = Storage::disk('public')->directories($tempPath);

        foreach ($userFolders as $userFolder) {
            $files = Storage::disk('public')->files($userFolder);

            foreach ($files as $file) {
                $lastModified = Storage::disk('public')->lastModified($file);
                $fileAge = Carbon::createFromTimestamp($lastModified);

                // Hapus file yang lebih dari 24 jam
                if ($fileAge->diffInHours(now()) > 24) {
                    $size = Storage::disk('public')->size($file);
                    Storage::disk('public')->delete($file);
                    $deletedCount++;
                    $deletedSize += $size;
                }
            }

            // Hapus folder user jika kosong
            if (empty(Storage::disk('public')->files($userFolder))) {
                Storage::disk('public')->deleteDirectory($userFolder);
            }
        }

        $this->info("Cleanup completed:");
        $this->info("- Files deleted: {$deletedCount}");
        $this->info("- Space freed: " . number_format($deletedSize / 1024 / 1024, 2) . " MB");

        return 0;
    }
}

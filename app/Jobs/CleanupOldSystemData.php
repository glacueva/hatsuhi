<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CleanupOldSystemData implements ShouldQueue
{
    use Queueable;

    /**
     * The number of seconds the job can run before timing out.
     */
    public int $timeout = 300;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        // You can pass parameters here if needed
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $cutoffDate = Carbon::now()();

        Log::info('Starting Filament v4 cleanup job', ['cutoff_date' => $cutoffDate]);

        try {
            // 1. Clean up notification records (using database table directly)
            $this->cleanupNotifications($cutoffDate);

            // 2. Clean up import records and files
            $this->cleanupImports($cutoffDate);

            // 3. Clean up export records and files
            $this->cleanupExports($cutoffDate);

            // 4. Clean up temporary Livewire files
            $this->cleanupLivewireTmp($cutoffDate);

            // 5. Clean up old Filament export files
            $this->cleanupFilamentExportFiles($cutoffDate);

            Log::info('Filament v4 cleanup job completed successfully');

        } catch (\Exception $e) {
            Log::error('Filament v4 cleanup job failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e; // Re-throw to mark job as failed
        }
    }

    /**
     * Clean up notification records
     * Assuming notifications are stored in 'notifications' table
     */
    protected function cleanupNotifications(Carbon $cutoffDate): void
    {
        $deleted = DB::table('notifications')
            ->where('created_at', '<', $cutoffDate)
            ->delete();

        Log::info("Deleted {$deleted} old notification records");
    }

    /**
     * Clean up import records and files
     * Assuming imports are stored in 'imports' table with 'file_path' column
     */
    protected function cleanupImports(Carbon $cutoffDate): void
    {
        $imports = DB::table('imports')
            ->where('created_at', '<', $cutoffDate)
            ->get(['id', 'file_path']);

        $deletedCount = 0;

        foreach ($imports as $import) {
            // Delete associated file
            if ($import->file_path && Storage::exists($import->file_path)) {
                Storage::delete($import->file_path);
            }

            // Also check for common import file paths
            $possiblePaths = [
                "private/imports/{$import->id}",
                "imports/{$import->id}",
                "app/private/imports/{$import->id}",
            ];

            foreach ($possiblePaths as $path) {
                if (Storage::exists($path)) {
                    Storage::delete($path);
                }
            }

            $deletedCount++;
        }

        // Delete the database records
        DB::table('imports')
            ->where('created_at', '<', $cutoffDate)
            ->delete();

        Log::info("Cleaned up {$deletedCount} old imports");
    }

    /**
     * Clean up export records and files
     * Assuming exports are stored in 'exports' table with 'file_path' column
     */
    protected function cleanupExports(Carbon $cutoffDate): void
    {
        $exports = DB::table('exports')
            ->where('created_at', '<', $cutoffDate)
            ->get(['id', 'file_path']);

        $deletedCount = 0;

        foreach ($exports as $export) {
            // Delete associated file
            if ($export->file_path && Storage::exists($export->file_path)) {
                Storage::delete($export->file_path);
            }

            $deletedCount++;
        }

        // Delete the database records
        DB::table('exports')
            ->where('created_at', '<', $cutoffDate)
            ->delete();

        Log::info("Cleaned up {$deletedCount} old exports");
    }

    /**
     * Clean up Livewire temporary files in storage/app/private/livewire-tmp
     */
    protected function cleanupLivewireTmp(Carbon $cutoffDate): void
    {
        $tmpPath = 'private/livewire-tmp';

        if (! Storage::exists($tmpPath)) {
            return;
        }

        $deletedFiles = 0;
        $files = Storage::allFiles($tmpPath);

        foreach ($files as $file) {
            $lastModified = Storage::lastModified($file);
            if ($lastModified < $cutoffDate->timestamp) {
                Storage::delete($file);
                $deletedFiles++;
            }
        }

        // Clean up empty directories
        $directories = Storage::allDirectories($tmpPath);
        rsort($directories); // Start from deepest directories

        foreach ($directories as $directory) {
            if (empty(Storage::allFiles($directory))) {
                Storage::deleteDirectory($directory);
            }
        }

        Log::info("Deleted {$deletedFiles} old Livewire temporary files");
    }

    /**
     * Clean up Filament export files in storage/app/private/filament-exports
     */
    protected function cleanupFilamentExportFiles(Carbon $cutoffDate): void
    {
        $exportPath = 'private/filament-exports';

        if (! Storage::exists($exportPath)) {
            return;
        }

        $deletedFiles = 0;
        $files = Storage::allFiles($exportPath);

        foreach ($files as $file) {
            $lastModified = Storage::lastModified($file);
            if ($lastModified < $cutoffDate->timestamp) {
                Storage::delete($file);
                $deletedFiles++;
            }
        }

        // Clean up empty directories
        $directories = Storage::allDirectories($exportPath);
        rsort($directories); // Start from deepest directories

        foreach ($directories as $directory) {
            if (empty(Storage::allFiles($directory))) {
                Storage::deleteDirectory($directory);
            }
        }

        Log::info("Deleted {$deletedFiles} old Filament export files");
    }
}

<?php

namespace App\Console\Commands;

use App\Jobs\CleanupOldSystemData;
use Illuminate\Console\Command;
use Illuminate\Support\Sleep;

class CleanFilamentData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'filament:cleanup 
                            {--dry-run : Show what would be deleted without actually deleting}
                            {--force : Skip confirmation prompt}
                            {--days=7 : Delete records older than X days}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up old Filament notifications, imports, exports and files';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        if ($this->option('dry-run')) {
            $this->info('DRY RUN MODE - No files or records will be deleted');
            $this->newLine();
            
            // Add dry run logic here if needed
            $this->dispatchJob(true);
        } else {
            if (!$this->option('force') && !$this->confirm('This will permanently delete files and records older than 1 week. Continue?')) {
                $this->info('Cleanup cancelled.');
                return;
            }
            
            $this->dispatchJob(false);
        }
    }
    
    protected function dispatchJob(bool $dryRun): void
    {
        $this->info('Dispatching Filament cleanup job...');
        
        // In Laravel 12, you can dispatch with parameters
        CleanupOldSystemData::dispatch($dryRun, $this->option('days'));
        
        $this->info('Cleanup job dispatched successfully!');
        $this->info('Check your logs for details.');
        
        if (!$dryRun) {
            $this->info('The job will run in the background.');
            Sleep::for(1)->second();
            $this->call('queue:work', ['--once' => true, '--stop-when-empty' => true]);
        }
    }
}
<?php

namespace App\Console\Commands;

use App\Models\Chat;
use Illuminate\Console\Command;

class CleanupOldChats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chat:cleanup {--days=3 : Number of days to keep chat history}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete old chat messages older than specified days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');
        $cutoffDate = now()->subDays($days);
        
        $deleted = Chat::where('created_at', '<', $cutoffDate)->delete();
        
        $this->info("Deleted {$deleted} old chat messages older than {$days} days.");
        
        \Log::info("Chat cleanup executed: {$deleted} messages deleted older than {$days} days");
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Report;

class CheckReportStatus extends Command
{
    protected $signature = 'check:reports';
    protected $description = 'Check report statuses in database';

    public function handle()
    {
        $reports = Report::select('id', 'tracking_code', 'status', 'nama_murid')->orderBy('id', 'desc')->limit(10)->get();
        
        $this->line("\n=== REPORTS IN DATABASE ===\n");
        foreach($reports as $r) {
            $this->line("Code: {$r->tracking_code}, Status: '{$r->status}'");
        }
        $this->line("");
    }
}

<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Delete chats older than 3 days
// This cleans up data for privacy as promised in the UI
Artisan::command('chat:cleanup', function () {
    $count = \App\Models\Chat::where('created_at', '<', now()->subDays(3))->delete();
    $this->info("Deleted {$count} old chat messages.");
})->purpose('Delete chat messages older than 3 days')->daily();

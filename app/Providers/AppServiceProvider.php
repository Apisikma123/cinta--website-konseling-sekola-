<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // log mail deliveries so we can debug delivery timing
        Event::listen(MessageSent::class, function (MessageSent $event) {
            $to = implode(',', array_map(fn($addr) => $addr->getAddress(), $event->message->getTo()));
            Log::info("Mail sent to {$to} subject={$event->message->getSubject()}");
        });
    }
}

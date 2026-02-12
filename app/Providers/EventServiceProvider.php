<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        \App\Events\ReportCreated::class => [
            \App\Listeners\NotifyTeachersOfNewReport::class,
        ],
        \App\Events\ReportStatusChanged::class => [
            \App\Listeners\NotifyStudentOfStatusChange::class,
        ],
    ];

    public function boot()
    {
        parent::boot();
    }
}
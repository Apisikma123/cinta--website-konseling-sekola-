<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        \App\Models\Report::class => \App\Policies\ReportPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();

        // additional gates
        Gate::define('approve-teacher', function ($user) {
            return $user->isAdmin();
        });
    }
}
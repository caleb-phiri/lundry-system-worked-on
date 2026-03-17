<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Define gates for roles
        Gate::define('is-client', function ($user) {
            return $user->role === 'client';
        });
        
        Gate::define('is-driver', function ($user) {
            return $user->role === 'driver';
        });
        
        Gate::define('is-admin', function ($user) {
            return $user->role === 'admin';
        });
    }
}
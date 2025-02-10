<?php

namespace App\Providers;

use App\Models\Post;
use App\Policies\PostPolicy;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        Post::class => PostPolicy::class
    ];
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
        //
        Gate::define('visitAdminPages', function ($user) {
            
            return $user->isAdmin === 1; // Make sure 'isAdmin' is a property or column in your User model
        });
    }
}

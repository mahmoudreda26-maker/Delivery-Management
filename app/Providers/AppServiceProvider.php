<?php

namespace App\Providers;

use App\Events\LocationUpdated;
use App\Listeners\ProcessLocationListener;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

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
        Event::listen(
            LocationUpdated::class,
            ProcessLocationListener::class
        );

        RateLimiter::for('login', function ($request) {
            return Limit::perMinute(5)
                ->by(
                    $request->string('email')->lower() . '|' . $request->ip()
                );
        });
    }
}

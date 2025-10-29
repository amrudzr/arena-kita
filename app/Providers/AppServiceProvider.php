<?php

namespace App\Providers;

use App\Services\VenueService;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('venue', function ($app) {
           return new VenueService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });

        // Log slow queries (only in non-production environments)
        if (! app()->isProduction()) {

            DB::listen(function ($query) {

                // Set the time threshold (in milliseconds)
                $slowQueryThreshold = 3000; // 3 seconds

                if ($query->time > $slowQueryThreshold) {

                    Log::warning(
                        'Slow Query Detected: '.$query->sql, // The slow SQL query
                        [
                            'time_ms' => $query->time, // Execution time in ms
                            'bindings' => $query->bindings, // Query parameters
                        ]
                    );
                }
            });
        }
    }
}

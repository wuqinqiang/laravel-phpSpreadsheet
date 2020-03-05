<?php

namespace Remember\LaravelPhpSpreadsheet;

use Illuminate\Support\ServiceProvider;

class LaravelPhpSpreadsheetServiceProvider extends ServiceProvider
{

    protected $defer = true;

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/config.php' => config_path('laravel-phpSpreadsheet.php'),
        ], 'config');
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'laravel-phpSpreadsheet');

        // Register the main class to use with the facade
        $this->app->singleton('laravel-phpSpreadsheet', function () {
            return new LaravelPhpSpreadsheet;
        });
    }

    public function provides()
    {
        return [LaravelPhpSpreadsheet::class, 'laravel-phpSpreadsheet'];
    }
}

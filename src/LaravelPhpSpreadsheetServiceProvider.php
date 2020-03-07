<?php

namespace Remember\LaravelPhpSpreadsheet;

use Illuminate\Support\ServiceProvider;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

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
        $this->app->singleton('laravel-phpSpreadsheet', function () {
            return new LaravelPhpSpreadsheet(new Spreadsheet());
        });
    }

    public function provides()
    {
        return [LaravelPhpSpreadsheet::class, 'xls'];
    }
}

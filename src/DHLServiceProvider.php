<?php

namespace xGrz\Dhl24;

use Illuminate\Support\ServiceProvider;
use xGrz\Dhl24\Providers\DHLEventServiceProvider;

class DHLServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(DHLEventServiceProvider::class);
    }

    public function boot(): void
    {
        self::setupPackageConfig();
        self::setupMigrations();
    }

    private function setupPackageConfig(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('dhl24.php'),
            ], 'dhl24-config');
            $this->publishes([
                __DIR__ . '/../lang' => $this->app->langPath('vendor/dhl24')
            ], 'dhl24-lang');
        }
    }

    private function setupMigrations(): void
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        }
    }
}

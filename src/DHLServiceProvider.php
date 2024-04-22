<?php

namespace xGrz\Dhl24;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use xGrz\Dhl24\Livewire\ShipmentItem;
use xGrz\Dhl24\Livewire\ShipmentList;
use xGrz\PayU\Services\ConfigService;

class DHLServiceProvider extends ServiceProvider
{

    public function register(): void
    {
    }

    public function boot(): void
    {
        self::setupPackageConfig();
        self::setupMigrations();
        self::setupWebRouting();
        self::setupTranslations();
        Livewire::component('shipment-list', ShipmentList::class);
        Livewire::component('shipment-item', ShipmentItem::class);
    }

    private function setupMigrations(): void
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        }
    }

    private function setupWebRouting(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'dhl');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
    }

    private function setupTranslations(): void
    {
        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'dhl');
    }

    private function setupPackageConfig(): void
    {
        $this->app->singleton(ConfigService::class, fn() => new ConfigService());

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('dhl24.php'),
            ], 'dhl24-config');
            $this->publishes([
                __DIR__ . '/../lang' => $this->app->langPath('vendor/dhl24')
            ], 'dhl24-lang');
        }

    }
}

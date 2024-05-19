<?php

namespace xGrz\Dhl24;


use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use xGrz\Dhl24\Livewire\CreateShipment;
use xGrz\Dhl24\Livewire\Settings\Contents\ContentCreate;
use xGrz\Dhl24\Livewire\Settings\Contents\ContentDelete;
use xGrz\Dhl24\Livewire\Settings\Contents\ContentEdit;
use xGrz\Dhl24\Livewire\Settings\Contents\ContentsListing;
use xGrz\Dhl24\Livewire\Settings\CostsCenter\CostCenterCreate;
use xGrz\Dhl24\Livewire\Settings\CostsCenter\CostCenterDelete;
use xGrz\Dhl24\Livewire\Settings\CostsCenter\CostCenterEdit;
use xGrz\Dhl24\Livewire\Settings\CostsCenter\CostCenterListing;
use xGrz\Dhl24\Livewire\Settings\TrackingEvents\TrackingEventEdit;
use xGrz\Dhl24\Livewire\Settings\TrackingEvents\TrackingEventListing;
use xGrz\Dhl24\Livewire\ShipmentListItem;
use xGrz\Dhl24\Livewire\ShipmentServices;
use xGrz\Dhl24\Providers\EventServiceProvider;
use xGrz\PayU\Services\ConfigService;

class DHLServiceProvider extends ServiceProvider
{


    public function register(): void
    {
        $this->app->register(EventServiceProvider::class);
    }
    public function boot(): void
    {
        self::setupPackageConfig();
        self::setupMigrations();
        self::setupWebRouting();
        self::setupTranslations();

        Livewire::component('create-shipment', CreateShipment::class);
        Livewire::component('shipment-item', ShipmentListItem::class);
        Livewire::component('shipment-services', ShipmentServices::class);
        Livewire::component('costs-center-listing', CostCenterListing::class);
        Livewire::component('cost-center-create', CostCenterCreate::class);
        Livewire::component('cost-center-edit', CostCenterEdit::class);
        Livewire::component('cost-center-delete', CostCenterDelete::class);
        Livewire::component('contents-listing', ContentsListing::class);
        Livewire::component('content-create', ContentCreate::class);
        Livewire::component('content-edit', ContentEdit::class);
        Livewire::component('content-delete', ContentDelete::class);
        Livewire::component('tracking-events-listing', TrackingEventListing::class);
        Livewire::component('tracking-event-edit', TrackingEventEdit::class);
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

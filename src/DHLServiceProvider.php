<?php

namespace xGrz\Dhl24;


use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;
use xGrz\Dhl24\Jobs\DispatchTrackingJob;
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
        self::setupTranslations();


        $this->app->booted(function () {
            $schedule = app(Schedule::class);
            self::setupScheduler($schedule);
        });

    }

    private function setupMigrations(): void
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        }
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

    private
    function setupScheduler(Schedule $schedule): void
    {
        $schedule
            ->call(fn() => DispatchTrackingJob::dispatch())
            ->name('DHL Tracking | Track shipments')
            ->between('6:00', '22:00')
            ->everyMinute();
    }


}

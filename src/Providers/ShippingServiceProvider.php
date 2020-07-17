<?php

namespace DFM\Shipping\Providers;

use DFM\Shipping\Console\Commands\ImportCoupePrices;
use Illuminate\Support\ServiceProvider;

class ShippingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/carriers.php', 'carriers'
        );

        $this->mergeConfigFrom(
            __DIR__ . '/../../config/system.php', 'core'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'dfm-shipping');

        if ($this->app->runningInConsole()) {
            $this->commands([
                ImportCoupePrices::class,
            ]);
        }

        $this->publishes([
            __DIR__.'/../../resources/imports' => public_path('imports'),
        ], 'public');
    }
}

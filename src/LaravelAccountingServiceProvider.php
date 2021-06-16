<?php

namespace Medianova\LaravelAccounting;

use Illuminate\Support\ServiceProvider;
use Medianova\LaravelAccounting\Providers\ProviderManager;

class LaravelAccountingServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/Config/accounting.php' => config_path('accounting.php'),
        ]);

        $this->app->singleton('accounting', function ($app) {
            return new ProviderManager;
        });
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/Config/accounting.php',
            'accounting'
        );
    }
}

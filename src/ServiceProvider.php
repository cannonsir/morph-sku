<?php

namespace Gtd\Sku;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/sku.php', 'sku');
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

            $this->publishes([
                __DIR__ . '/../config/sku.php' => config_path('sku.php')
            ], 'sku-config');

            $this->publishes([
                __DIR__.'/../database/migrations/' => database_path('migrations')
            ], 'sku-migrations');
        }
    }
}
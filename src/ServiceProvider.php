<?php

namespace Gtd\Product;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/product_sku.php', 'product_sku');
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

            $this->publishes([
                __DIR__.'/../config/product_sku.php' => config_path('product_sku.php')
            ], 'product-sku-config');

            $this->publishes([
                __DIR__.'/../database/migrations/' => database_path('migrations')
            ], 'product-sku-migrations');
        }
    }
}
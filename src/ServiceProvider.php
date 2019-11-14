<?php

namespace Gtd\MorphSku;

use Illuminate\Support\Collection;
use Illuminate\Filesystem\Filesystem;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/morph-sku.php', 'morph-sku');
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/morph-sku.php' => config_path('morph-sku.php')
            ], 'morph-sku-config');

            $this->publishes([
                __DIR__.'/../database/migrations/create_morph_sku_tables.php.stub' => $this->getMigrationFileName()
            ], 'morph-sku-migrations');
        }
    }

    protected function getMigrationFileName()
    {
        $timestamp = date('Y_m_d_His');

        return Collection::make($this->app->databasePath().DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR)
            ->flatMap(function ($path) {
                return (new Filesystem)->glob($path.'*_create_morph_sku_tables.php');
            })
            ->push($this->app->databasePath()."/migrations/{$timestamp}_create_morph_sku_tables.php")
            ->first();
    }
}
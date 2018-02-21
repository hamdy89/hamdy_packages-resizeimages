<?php

namespace Hamdy_Packages\Resizeimages;

use Illuminate\Support\ServiceProvider;

class ResizeimagesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        include __DIR__ . '/routes.php';
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //Register Our Migration
        $this->loadMigrationsFrom(__DIR__.'Hamdy_Packages\Resizeimages\2018_02_21_125504_create_resizeimages_table');
    }
}

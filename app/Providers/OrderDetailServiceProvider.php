<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class OrderDetailServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            \App\Repositories\OrderDetails\OrderDetailRepository::class,
            \App\Repositories\OrderDetails\DbOrderDetailRepository::class
        );
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ReservationExportServiceProvider extends ServiceProvider
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
        $this->app->bind(
            'reservation_export', // キー名
            'App\Services\ReservationExportService' // クラス名
        );
    }
}

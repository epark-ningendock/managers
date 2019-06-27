<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class CsvTestServiceProvider extends ServiceProvider
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
            'csv_test', // キー名
            'App\Services\CsvTestService' // クラス名
        );
    }
}

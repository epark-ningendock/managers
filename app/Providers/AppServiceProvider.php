<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // it's for debug
        // if (config('app.env') !== 'production') {
        //     \DB::listen(function ($query) {
        //         $sql = $query->sql;
        //         for ($i = 0; $i < count($query->bindings); $i++) {
        //             $sql = preg_replace("/\?/", $query->bindings[$i], $sql, 1);
        //         }
        //         \Log::info($sql);
        //     });
        // }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

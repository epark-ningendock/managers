<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Resources\Json\Resource;
use URL;
use Log;
use DB;


class AppServiceProvider extends ServiceProvider
{
    /**
     * サービスの初期起動後に、登録内容を処理
     *
     * @return void
     */
    public function boot()
    {
        Resource::withoutWrapping();
        if(config('app.env') === 'production' or config('app.env') === 'staging'){
            URL::forceScheme('https');
            DB::listen(function ($query) {
                Log::info("Query Time:{$query->time}s] $query->sql");
            });
        }
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

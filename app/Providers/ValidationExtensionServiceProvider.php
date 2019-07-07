<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Validation\ValidatorExtended;


class ValidationExtensionServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->validator->resolver( function( $translator, $data, $rules, $messages = array(), $customAttributes = array() ) {
            return new ValidatorExtended( $translator, $data, $rules, $messages, $customAttributes );
        } );
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
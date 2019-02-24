<?php

namespace Elnooronline\LaravelApiAuthentication\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider as Provider;

class ServiceProvider extends Provider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('onesignal_player_id', function ($attribute, $value, $parameters, $validator) {
            return preg_match("/[a-z0-9]{8}-([a-z0-9]{4}-){3}[a-z0-9]{12}/", $value);
        });

        $this->publishes([
            __DIR__.'/../../database/migrations' => database_path('migrations')
        ], 'api-authentication:migration');

        $this->publishes([
            __DIR__.'/../../config/api-authentication.php' => config_path('api-authentication.php')
        ], 'api-authentication:config');

        $this->mergeConfigFrom(
            __DIR__.'/../../config/api-authentication.php', 'api-authentication'
        );

        $this->loadTranslationsFrom(
           __DIR__.'/../../resources/lang', 'authentication'
        );

        $this->loadRoutesFrom(__DIR__.'/../../routes/api.php');

        $this->app['config']->set([
            'auth.guards.api.driver' => 'passport'
        ]);
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
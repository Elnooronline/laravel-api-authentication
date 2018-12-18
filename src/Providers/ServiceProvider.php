<?php

namespace Elnooronline\LaravelApiAuthentication\Providers;

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
<?php

namespace Tests;

use Laravel\Passport\PersonalAccessTokenResult;
use Mockery;
use Tests\Models\User;
use Laravel\Passport\PassportServiceProvider;
use Laravel\Passport\PersonalAccessTokenFactory;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Elnooronline\LaravelApiAuthentication\Providers\ServiceProvider;

class TestCase extends OrchestraTestCase
{
    /**
     * Setup the test environment.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->loadLaravelMigrations(['--database' => 'testbench']);

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    /**
     * Load package service provider
     *
     * @param  \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class,
            PassportServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['config']->set('auth.providers.users.model', User::class);
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function overrideApplicationBindings($app)
    {
        return [
            PersonalAccessTokenFactory::class => function () {
                return Mockery::mock(PersonalAccessTokenFactory::class, function ($mock) {
                    $mock->shouldReceive('make')->andReturn(
                        new PersonalAccessTokenResult('test_access_token', 'test_token')
                    );
                });
            }];
    }
}
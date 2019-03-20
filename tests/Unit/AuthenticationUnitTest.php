<?php

namespace Tests\Unit;

use Laravel\Passport\PersonalAccessTokenFactory;
use Laravel\Passport\PersonalAccessTokenResult;
use Mockery;
use Tests\TestCase;
use Tests\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthenticationUnitTest extends TestCase
{
    public function test_login()
    {
        $this->app->instance(
            PersonalAccessTokenFactory::class,
            Mockery::mock(PersonalAccessTokenFactory::class, function ($mock) {
                $mock->shouldReceive('make')->andReturn(
                    new PersonalAccessTokenResult('test_access_token', 'token')
                );
            })
        );

        User::create([
            'name' => 'Elnooronline',
            'email' => 'admin@elnooronline.com',
            'password' => Hash::make('secret'),
        ])->markEmailAsVerified();

        $response = $this->postJson('/api/login', []);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email', 'password']);

        $response = $this->postJson('/api/login', [
            'email' => 'admin@elnooronline.com',
            'password' => 'secret',
        ]);
        $response->assertSuccessful();
    }
}
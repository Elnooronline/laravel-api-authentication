<?php

namespace Tests\Unit;

use Tests\TestCase;
use Tests\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthenticationUnitTest extends TestCase
{
    public function test_login()
    {
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
        $response->dump()->assertSuccessful();
    }
}
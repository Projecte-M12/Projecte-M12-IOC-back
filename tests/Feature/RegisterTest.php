<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a user can register.
     */
    public function test_user_can_register()
    {
        $userData = [
            "name" => "Test User",
            "email" => "testuser@example.com",
            "password" => "password",
            "password_confirmation" => "password",
        ];

        $response = $this->json('POST', '/api/register', $userData);

        $response->assertStatus(200)
                 ->assertJsonStructure(['token']);
    }
}
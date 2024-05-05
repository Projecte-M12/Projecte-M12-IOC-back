<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testLogin()
    {
        // Crea un usuario de prueba
        $user = User::factory()->create([
            'password' => bcrypt($password = 'i-love-laravel'),
        ]);

        // Intenta iniciar sesión con las credenciales del usuario de prueba
        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        // Asegúrate de que la respuesta tiene un código de estado 200
        $response->assertStatus(200);

        // Asegúrate de que la respuesta tiene un token
        $this->assertArrayHasKey('token', $response->json());
    }
}

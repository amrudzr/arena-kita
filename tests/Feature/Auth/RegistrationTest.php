<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_users_can_register_via_api(): void
    {
        $this->markTestSkipped('Tes ini dinonaktifkan sementara menunggu refactor ke API.');

        $userData = [
            'full_name' => 'Test User',
            'email' => 'test@example.com',
            'phone_number' => '08123456789',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/api/v1/auth/user/register', $userData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => ['id', 'full_name', 'email', 'phone_number', 'role'],
            ])
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.email', 'test@example.com');

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);
    }
}

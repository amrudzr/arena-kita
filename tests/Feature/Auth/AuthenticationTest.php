<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('users can authenticate via api with correct credentials', function () {
    $user = User::factory()->create([
        'email' => 'user@example.com',
        'password' => 'password123',
    ]);

    $response = $this->postJson('/api/v1/auth/user/login', [
        'email' => 'user@example.com',
        'password' => 'password123',
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'status',
            'message',
            'data' => ['token', 'user'],
        ])
        ->assertJsonPath('status', 'success');
});

test('users can not authenticate via api with invalid password', function () {
    $user = User::factory()->create([
        'email' => 'user@example.com',
        'password' => 'password123',
    ]);

    $response = $this->postJson('/api/v1/auth/user/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $response->assertStatus(422)
        ->assertJsonPath('status', 'error')
        ->assertJsonPath('message', 'Validasi gagal')
        ->assertJsonStructure(['errors' => ['email']]);
});

test('users can logout via api', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user, 'api_user')
        ->postJson('/api/v1/auth/logout');

    $response->assertStatus(200)
        ->assertJsonPath('status', 'success')
        ->assertJsonPath('message', 'Logout berhasil.');
});

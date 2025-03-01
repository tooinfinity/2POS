<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\Password;

test('new password screen can be rendered', function (): void {
    $user = User::factory()->create();

    $token = Password::createToken($user);

    $this->get(route('password.reset', [
        'token' => $token,
        'email' => $user->email,
    ]))->assertStatus(200);
});

test('password can be reset with valid token', function (): void {
    $user = User::factory()->create();

    $token = Password::createToken($user);

    $response = $this->post(route('password.store'), [
        'token' => $token,
        'email' => $user->email,
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(route('login'));
});

test('password can be reset with invalid token', function (): void {
    $user = User::factory()->create();

    $response = $this->post(route('password.store'), [
        'token' => 'invalid-token',
        'email' => $user->email,
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertSessionHasErrors(['email']);
    $response->assertRedirect();
});

<?php

declare(strict_types=1);

use App\Models\User;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

test('registration screen can be rendered', function (): void {

    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function (): void {
    $this->withoutExceptionHandling();

    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $user = User::where('email', 'test@example.com')->first();

    expect($user)->not->toBeNull()
        ->and($user->name)->toBe('Test User')
        ->and($user->email)->toBe('test@example.com')
        ->and(Hash::check('password', $user->password))->toBeTrue()
        ->and($user->hasRole('Administrator'))->toBeTrue();

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});

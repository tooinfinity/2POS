<?php

declare(strict_types=1);

use App\Models\User;

test('language index is displayed', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get('/settings/language');

    $response->assertOk();
});

test('default to en when config is not a string', function () {
    $user = User::factory()->create();

    config(['app.locale' => null]);

    $response = $this
        ->actingAs($user)
        ->get('/settings/language');

    $response->assertOk();
    expect(session('locale'))->toBe('en');
    $this->assertDatabaseHas('settings', [
        'key' => 'locale',
        'value' => 'en',
    ]);

});

test('can update the language', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->from('/settings/language')
        ->put('/settings/language', [
            'language' => 'fr',
        ]);

    $response->assertRedirect();
    $response->assertSessionHas('locale', 'fr');
    $this->assertDatabaseHas('settings', [
        'key' => 'locale',
        'value' => 'fr',
    ]);
});

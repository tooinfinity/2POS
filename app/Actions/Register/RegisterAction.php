<?php

declare(strict_types=1);

namespace App\Actions\Register;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;

final class RegisterAction
{
    /**
     * @param  array{name: string, email: string, password: string}  $data
     */
    public function handle(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'email_verified_at' => now(),
        ]);

        event(new Registered($user));

        $user->assignRole(RoleEnum::Administrator->value);

        return $user;
    }
}

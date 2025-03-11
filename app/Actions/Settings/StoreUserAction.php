<?php

declare(strict_types=1);

namespace App\Actions\Settings;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;

final readonly class StoreUserAction extends AbstractUserAction
{
    /**
     * @param  array{name: string, email: string, password: string, role?: string, permissions?: array<int>}  $data
     *
     * @throws Throwable
     */
    public function handle(array $data): User
    {
        return DB::transaction(function () use ($data): User {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'email_verified_at' => now(),
            ]);

            $this->handleRoleAndPermissions($user, $data);

            return $user;
        });
    }
}

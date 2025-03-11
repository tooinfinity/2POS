<?php

declare(strict_types=1);

namespace App\Actions\Settings;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RuntimeException;
use Throwable;

final readonly class UpdateUserAction extends AbstractUserAction
{
    /**
     * @param  array{name: string, email: string, password?: string, role?: string, permissions?: array<int>}  $data
     *
     * @throws Throwable
     */
    public function handle(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data): User {
            $updateData = [
                'name' => $data['name'],
                'email' => $data['email'],
            ];

            if (isset($data['password'])) {
                $updateData['password'] = Hash::make($data['password']);
            }

            $user->update($updateData);

            $this->handleRoleAndPermissions($user, $data);

            $freshUser = $user->fresh();

            if ($freshUser === null) {
                throw new RuntimeException('Failed to refresh user data');
            }

            return $freshUser;
        });
    }
}

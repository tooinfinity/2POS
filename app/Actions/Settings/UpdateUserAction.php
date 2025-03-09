<?php

declare(strict_types=1);

namespace App\Actions\Settings;

use App\Actions\Permissions\UpdateRoleAction;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;

final readonly class UpdateUserAction
{
    public function __construct(
        private UpdateRoleAction $roleAction
    ) {}

    /**
     * @throws Throwable
     */
    public function handle(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {
            $updateData = [
                'name' => $data['name'],
                'email' => $data['email'],
            ];

            if (isset($data['password'])) {
                $updateData['password'] = Hash::make($data['password']);
            }

            $user->update($updateData);

            if (isset($data['role'])) {
                $role = Role::where('name', $data['role'])->firstOrFail();
                $permission = new Permission();

                $this->roleAction->handle(
                    role: $role,
                    permission: $permission,
                    user: $user,
                    data: [
                        'role' => $data['role'],
                        'permissions' => $data['permissions'] ?? [],
                    ]
                );
            }

            return $user->fresh();
        });
    }
}

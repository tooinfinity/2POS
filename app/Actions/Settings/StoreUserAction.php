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

final readonly class StoreUserAction
{
    public function __construct(
        private UpdateRoleAction $roleAction
    ) {}

    /**
     * @throws Throwable
     */
    public function handle(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'email_verified_at' => now(),
            ]);

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

            return $user;
        });
    }
}

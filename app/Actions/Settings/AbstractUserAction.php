<?php

declare(strict_types=1);

namespace App\Actions\Settings;

use App\Actions\Permissions\UpdateRoleAction;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Throwable;

abstract readonly class AbstractUserAction
{
    public function __construct(
        protected UpdateRoleAction $roleAction
    ) {}

    /**
     * @param  array{role?: string, permissions?: array<int>}  $data
     *
     * @throws Throwable
     */
    protected function handleRoleAndPermissions(User $user, array $data): void
    {
        if (! isset($data['role'])) {
            return;
        }

        /** @var Role $role */
        $role = Role::query()
            ->where('name', $data['role'])
            ->firstOrFail();

        $this->roleAction->handle(
            role: $role,
            permission: new Permission(),
            user: $user,
            data: [
                'role' => $data['role'],
                'permissions' => $data['permissions'] ?? [],
            ]
        );
    }
}
